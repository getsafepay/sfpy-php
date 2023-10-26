<?php

namespace Safepay\HttpClient;

use Safepay\Exception;
use Safepay\Safepay;
use Safepay\Util;

// @codingStandardsIgnoreStart
// PSR2 requires all constants be upper case. Sadly, the CURL_SSLVERSION
// constants do not abide by those rules.

// Note the values come from their position in the enums that
// defines them in cURL's source code.

// Available since PHP 5.5.19 and 5.6.3
if (!\defined('CURL_SSLVERSION_TLSv1_2')) {
  \define('CURL_SSLVERSION_TLSv1_2', 6);
}
// @codingStandardsIgnoreEnd

// Available since PHP 7.0.7 and cURL 7.47.0
if (!\defined('CURL_HTTP_VERSION_2TLS')) {
  \define('CURL_HTTP_VERSION_2TLS', 4);
}

class CurlClient implements ClientInterface
{
  protected static $instance;

  public static function instance()
  {
    if (!static::$instance) {
      static::$instance = new static();
    }

    return static::$instance;
  }

  protected $defaultOptions;

  protected $curlHandle;

  protected $requestStatusCallback;

  /**
   * CurlClient constructor.
   *
   * Pass in a callable to $defaultOptions that returns an array of CURLOPT_* values to start
   * off a request with, or an flat array with the same format used by curl_setopt_array() to
   * provide a static set of options. Note that many options are overridden later in the request
   * call, including timeouts, which can be set via setTimeout() and setConnectTimeout().
   *
   * Note that request() will silently ignore a non-callable, non-array $defaultOptions, and will
   * throw an exception if $defaultOptions returns a non-array value.
   *
   * @param null|array|callable $defaultOptions
   * @param null|\Safepay\Util\RandomGenerator $randomGenerator
   */
  public function __construct($defaultOptions = null, $randomGenerator = null)
  {
    $this->defaultOptions = $defaultOptions;
  }

  public function __destruct()
  {
    $this->closeCurlHandle();
  }

  public function getDefaultOptions()
  {
    return $this->defaultOptions;
  }

  /**
   * @return null|callable
   */
  public function getRequestStatusCallback()
  {
    return $this->requestStatusCallback;
  }

  /**
   * Sets a callback that is called after each request. The callback will
   * receive the following parameters:
   * <ol>
   *   <li>string $rbody The response body</li>
   *   <li>integer $rcode The response status code</li>
   *   <li>\Safepay\Util\CaseInsensitiveArray $rheaders The response headers</li>
   *   <li>integer $errno The curl error number</li>
   *   <li>string|null $message The curl error message</li>
   *   <li>boolean $shouldRetry Whether the request will be retried</li>
   *   <li>integer $numRetries The number of the retry attempt</li>
   * </ol>.
   *
   * @param null|callable $requestStatusCallback
   */
  public function setRequestStatusCallback($requestStatusCallback)
  {
    $this->requestStatusCallback = $requestStatusCallback;
  }

  // USER DEFINED TIMEOUTS

  const DEFAULT_TIMEOUT = 80;

  private $timeout = self::DEFAULT_TIMEOUT;

  public function setTimeout($seconds)
  {
    $this->timeout = (int) \max($seconds, 0);

    return $this;
  }

  public function getTimeout()
  {
    return $this->timeout;
  }

  // END OF USER DEFINED TIMEOUTS

  private function constructRequest($method, $absUrl, $headers, $params)
  {
    $method = \strtolower($method);

    $opts = [];
    if (\is_callable($this->defaultOptions)) { // call defaultOptions callback, set options to return value
      $opts = \call_user_func_array($this->defaultOptions, \func_get_args());
      if (!\is_array($opts)) {
        throw new Exception\UnexpectedValueException('Non-array value returned by defaultOptions CurlClient callback');
      }
    } elseif (\is_array($this->defaultOptions)) { // set default curlopts from array
      $opts = $this->defaultOptions;
    }

    $params = Util\Util::objectsToIds($params);

    if ('get' === $method) {
      $opts[\CURLOPT_HTTPGET] = 1;
      if (\count($params) > 0) {
        $encoded = Util\Util::encodeParameters($params);
        $absUrl = "{$absUrl}?{$encoded}";
      }
    } elseif ('post' === $method) {
      $opts[\CURLOPT_POST] = 1;
      $opts[\CURLOPT_POSTFIELDS] = \json_encode($params);
    } elseif ('delete' === $method) {
      $opts[\CURLOPT_CUSTOMREQUEST] = 'DELETE';
      if (\count($params) > 0) {
        $opts[\CURLOPT_POSTFIELDS] = \json_encode($params);
      }
    } elseif ('put' === $method) {
      $opts[\CURLOPT_CUSTOMREQUEST] = 'PUT';
      $opts[\CURLOPT_POSTFIELDS] = \json_encode($params);
    } else {
      throw new Exception\UnexpectedValueException("Unrecognized method {$method}");
    }

    // By default for large request body sizes (> 1024 bytes), cURL will
    // send a request without a body and with a `Expect: 100-continue`
    // header, which gives the server a chance to respond with an error
    // status code in cases where one can be determined right away (say
    // on an authentication problem for example), and saves the "large"
    // request body from being ever sent.
    //
    // Unfortunately, the bindings don't currently correctly handle the
    // success case (in which the server sends back a 100 CONTINUE), so
    // we'll error under that condition. To compensate for that problem
    // for the time being, override cURL's behavior by simply always
    // sending an empty `Expect:` header.
    $headers[] = 'Expect: ';

    $absUrl = Util\Util::utf8($absUrl);
    $opts[\CURLOPT_URL] = $absUrl;
    $opts[\CURLOPT_RETURNTRANSFER] = true;
    $opts[\CURLOPT_TIMEOUT] = $this->timeout;
    $opts[\CURLOPT_HTTPHEADER] = $headers;

    return [$opts, $absUrl];
  }

  public function request($method, $absUrl, $headers, $params)
  {
    list($opts, $absUrl) = $this->constructRequest($method, $absUrl, $headers, $params);

    list($rbody, $rcode, $rheaders) = $this->executeRequestWithRetries($opts, $absUrl);

    return [$rbody, $rcode, $rheaders];
  }

  /**
   * Curl permits sending \CURLOPT_HEADERFUNCTION, which is called with lines
   * from the header and \CURLOPT_WRITEFUNCTION, which is called with bytes
   * from the body. You usually want to handle the body differently depending
   * on what was in the header.
   *
   * This function makes it easier to specify different callbacks depending
   * on the contents of the heeder. After the header has been completely read
   * and the body begins to stream, it will call $determineWriteCallback with
   * the array of headers. $determineWriteCallback should, based on the
   * headers it receives, return a "writeCallback" that describes what to do
   * with the incoming HTTP response body.
   *
   * @param array $opts
   * @param callable $determineWriteCallback
   *
   * @return array
   */
  private function useHeadersToDetermineWriteCallback($opts, $determineWriteCallback)
  {
    $rheaders = new Util\CaseInsensitiveArray();
    $headerCallback = function ($curl, $header_line) use (&$rheaders) {
      return self::parseLineIntoHeaderArray($header_line, $rheaders);
    };

    $writeCallback = null;
    $writeCallbackWrapper = function ($curl, $data) use (&$writeCallback, &$rheaders, &$determineWriteCallback) {
      if (null === $writeCallback) {
        $writeCallback = \call_user_func_array($determineWriteCallback, [$rheaders]);
      }

      return \call_user_func_array($writeCallback, [$curl, $data]);
    };

    return [$headerCallback, $writeCallbackWrapper];
  }

  private static function parseLineIntoHeaderArray($line, &$headers)
  {
    if (false === \strpos($line, ':')) {
      return \strlen($line);
    }
    list($key, $value) = \explode(':', \trim($line), 2);
    $headers[\trim($key)] = \trim($value);

    return \strlen($line);
  }

  /**
   * @param array $opts cURL options
   * @param string $absUrl
   */
  public function executeRequestWithRetries($opts, $absUrl)
  {
    $numRetries = 0;

    while (true) {
      $rcode = 0;
      $errno = 0;
      $message = null;

      // Create a callback to capture HTTP headers for the response
      $rheaders = new Util\CaseInsensitiveArray();
      $headerCallback = function ($curl, $header_line) use (&$rheaders) {
        return CurlClient::parseLineIntoHeaderArray($header_line, $rheaders);
      };
      $opts[\CURLOPT_HEADERFUNCTION] = $headerCallback;

      $this->resetCurlHandle();
      \curl_setopt_array($this->curlHandle, $opts);
      $rbody = \curl_exec($this->curlHandle);

      if (false === $rbody) {
        $errno = \curl_errno($this->curlHandle);
        $message = \curl_error($this->curlHandle);
      } else {
        $rcode = \curl_getinfo($this->curlHandle, \CURLINFO_HTTP_CODE);
      }

      $this->closeCurlHandle();

      if (\is_callable($this->getRequestStatusCallback())) {
        \call_user_func_array(
          $this->getRequestStatusCallback(),
          [$rbody, $rcode, $rheaders, $errno, $message, $numRetries]
        );
      }

      break;
    }

    if (false === $rbody) {
      $this->handleCurlError($absUrl, $errno, $message, $numRetries);
    }

    return [$rbody, $rcode, $rheaders];
  }

  /**
   * @param string $url
   * @param int $errno
   * @param string $message
   * @param int $numRetries
   *
   * @throws Exception\ApiConnectionException
   */
  private function handleCurlError($url, $errno, $message, $numRetries)
  {
    switch ($errno) {
      case \CURLE_COULDNT_CONNECT:
      case \CURLE_COULDNT_RESOLVE_HOST:
      case \CURLE_OPERATION_TIMEOUTED:
        $msg = "Could not connect to Safepay ({$url}).  Please check your "
          . 'internet connection and try again.  If this problem persists, '
          . "you should check Safepay's service status at "
          . 'https://safepay.betteruptime.com, or';

        break;

      case \CURLE_SSL_CACERT:
      case \CURLE_SSL_PEER_CERTIFICATE:
        $msg = "Could not verify Safepay's SSL certificate.  Please make sure "
          . 'that your network is not intercepting certificates.  '
          . "(Try going to {$url} in your browser.)  "
          . 'If this problem persists,';

        break;

      default:
        $msg = 'Unexpected error communicating with Safepay.  '
          . 'If this problem persists,';
    }
    $msg .= ' let us know at support@getsafepay.com.';

    $msg .= "\n\n(Network error [errno {$errno}]: {$message})";

    if ($numRetries > 0) {
      $msg .= "\n\nRequest was retried {$numRetries} times.";
    }

    throw new Exception\ApiConnectionException($msg);
  }





  /**
   * Initializes the curl handle. If already initialized, the handle is closed first.
   */
  private function initCurlHandle()
  {
    $this->closeCurlHandle();
    $this->curlHandle = \curl_init();
  }

  /**
   * Closes the curl handle if initialized. Do nothing if already closed.
   */
  private function closeCurlHandle()
  {
    if (null !== $this->curlHandle) {
      \curl_close($this->curlHandle);
      $this->curlHandle = null;
    }
  }

  /**
   * Resets the curl handle. If the handle is not already initialized, or if persistent
   * connections are disabled, the handle is reinitialized instead.
   */
  private function resetCurlHandle()
  {
    if (null !== $this->curlHandle) {
      \curl_reset($this->curlHandle);
    } else {
      $this->initCurlHandle();
    }
  }
}
