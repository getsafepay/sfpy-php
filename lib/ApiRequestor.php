<?php

namespace Safepay;
/**
 * Class ApiRequestor.
 */
class ApiRequestor
{

  /**
   * @var null|string
   */
  private $_authType;

  /**
   * @var null|string
   */
  private $_apiKey;

  /**
   * @var string
   */
  private $_apiBase;

  /**
   * @var HttpClient\ClientInterface
   */
  private static $_httpClient;

  private static $OPTIONS_KEYS = ['api_key', 'api_base'];

  /**
   * ApiRequestor constructor.
   *
   * @param null|string $apiKey
   * @param null|string $apiBase
   */
  public function __construct($authType = null, $apiKey = null, $apiBase = null)
  {
    $this->_authType = $authType;
    $this->_apiKey = $apiKey;
    if (!$apiBase) {
      $apiBase = Safepay::$apiBase;
    }
    $this->_apiBase = $apiBase;
  }

  /**
   * @static
   *
   * @param ApiResource|array|bool|mixed $d
   *
   * @return ApiResource|array|mixed|string
   */
  private static function _encodeObjects($d)
  {
    if ($d instanceof ApiResource) {
      return Util\Util::utf8($d->id);
    }

    if (\is_array($d)) {
      $res = [];
      foreach ($d as $k => $v) {
        $res[$k] = self::_encodeObjects($v);
      }

      return $res;
    }

    return Util\Util::utf8($d);
  }

  /**
   * @param 'delete'|'get'|'post'|'put' $method
   * @param string     $url
   * @param null|array $params
   * @param null|array $headers
   *
   * @throws Exception\ApiErrorException
   *
   * @return array tuple containing (ApiReponse, API key)
   */
  public function request($method, $url, $params = null, $headers = null)
  {
    $params = $params ?: [];
    $headers = $headers ?: [];
    list($rbody, $rcode, $rheaders, $myApiKey) =
      $this->_requestRaw($method, $url, $params, $headers);
    $json = $this->_interpretResponse($rbody, $rcode, $rheaders);
    $resp = new ApiResponse($rbody, $rcode, $rheaders, $json);

    return [$resp, $myApiKey];
  }

  /**
   * @static
   *
   * @param string $apiKey
   * @param null   $clientInfo
   *
   * @return array
   */
  private static function _defaultHeaders($authType, $apiKey, $clientInfo = null)
  {
    if (null === $apiKey) {
        return [];
    }
    if ('secret' === $authType) {
      return [
        'X-SFPY-MERCHANT-SECRET' => $apiKey
       ];
    } else if ('jwt' === $authType) {
      return [
        'Authorization' => "Bearer " . $apiKey
      ];
    } else {
        return [];
    }
  }

  private function _prepareRequest($method, $url, $params, $headers)
  {
    $myAuthType = $this->_authType;
    $myApiKey = $this->_apiKey;

    if (!$myApiKey) {
      $myApiKey = Safepay::$apiKey;
    }

    $publicPaths = [
      '/user/v2/',
      '/auth/v2/user/login'
    ];

    if (!$myApiKey && !in_array($url, $publicPaths, true)) {
      $msg = 'No API key provided.  (HINT: set your API key using '
        . '"Safepay::setApiKey(<API-KEY>)").  You can generate API keys from '
        . 'the Safepay web interface.  Email support@getsafepay.com if you have any questions.';

      throw new Exception\AuthenticationException($msg);
    }

    if ($params && \is_array($params)) {
      $optionKeysInParams = \array_filter(
        self::$OPTIONS_KEYS,
        function ($key) use ($params) {
          return \array_key_exists($key, $params);
        }
      );
      if (\count($optionKeysInParams) > 0) {
        $message = \sprintf('Options found in $params: %s. Options should '
          . 'be passed in their own array after $params. (HINT: pass an '
          . 'empty array to $params if you do not have any.)', \implode(', ', $optionKeysInParams));
        \trigger_error($message, \E_USER_WARNING);
      }
    }

    $absUrl = $this->_apiBase . $url;
    $params = self::_encodeObjects($params);

    $defaultHeaders = $this->_defaultHeaders($myAuthType, $myApiKey);

    print_r($defaultHeaders);

    $defaultHeaders['Content-Type'] = 'application/json';

    $combinedHeaders = \array_merge($defaultHeaders, $headers);
    $rawHeaders = [];

    foreach ($combinedHeaders as $header => $value) {
      $rawHeaders[] = $header . ': ' . $value;
    }

    return [$absUrl, $rawHeaders, $params, $myApiKey];
  }

  /**
   * @param 'delete'|'get'|'post'|'put' $method
   * @param string $url
   * @param array $params
   * @param array $headers
   *
   * @throws Exception\AuthenticationException
   * @throws Exception\ApiConnectionException
   *
   * @return array
   */
  private function _requestRaw($method, $url, $params, $headers)
  {
    list($absUrl, $rawHeaders, $params, $myApiKey) = $this->_prepareRequest($method, $url, $params, $headers);

    list($rbody, $rcode, $rheaders) = $this->httpClient()->request(
      $method,
      $absUrl,
      $rawHeaders,
      $params
    );

    return [$rbody, $rcode, $rheaders, $myApiKey];
  }

  /**
   * @param string $rbody a JSON string
   * @param int $rcode
   * @param array $rheaders
   * @param array $resp
   *
   * @throws Exception\UnexpectedValueException
   * @throws Exception\ApiErrorException
   */
  public function handleErrorResponse($rbody, $rcode, $rheaders, $resp)
  {

    if (!\is_array($resp) || !isset($resp['status']['errors'])) {
      $msg = "Invalid response object from API: {$rbody} "
        . "(HTTP response code was {$rcode})";

      throw new Exception\UnexpectedValueException($msg);
    }

    // errorData is the array of error messages
    $errorData = $resp['status']['errors'];
    $error = null;
    if (\is_array($errorData)) {
      $error = self::_specificAPIError($rbody, $rcode, $rheaders, $resp, $errorData);
    }

    throw $error;
  }

  /**
   * @static
   *
   * @param string $rbody
   * @param int    $rcode
   * @param array  $rheaders
   * @param array  $resp
   * @param array  $errorData
   *
   * @return Exception\ApiErrorException
   */
  private static function _specificAPIError($rbody, $rcode, $rheaders, $resp, $errorData)
  {
    $msg = count($errorData) === 1 ? $errorData[0] : \implode(",", $errorData);
    switch ($rcode) {
      case 400:
        return Exception\InvalidRequestException::factory($msg, $rcode, $rbody, $resp, $rheaders);
        // no break
      case 404:
        return Exception\InvalidRequestException::factory($msg, $rcode, $rbody, $resp, $rheaders);

      case 401:
        return Exception\AuthenticationException::factory($msg, $rcode, $rbody, $resp, $rheaders);

      case 403:
        return Exception\InvalidRequestException::factory($msg, $rcode, $rbody, $resp, $rheaders);

      default:
        return Exception\UnknownApiErrorException::factory($msg, $rcode, $rbody, $resp, $rheaders);
    }
  }

  /**
   * @param string $rbody
   * @param int    $rcode
   * @param array  $rheaders
   *
   * @throws Exception\UnexpectedValueException
   * @throws Exception\ApiErrorException
   *
   * @return array
   */
  private function _interpretResponse($rbody, $rcode, $rheaders)
  {
    $resp = \json_decode($rbody, true);
    $jsonError = \json_last_error();
    if (null === $resp && \JSON_ERROR_NONE !== $jsonError) {
      $msg = "Invalid response body from API: {$rbody} "
        . "(HTTP response code was {$rcode}, json_last_error() was {$jsonError})";

      throw new Exception\UnexpectedValueException($msg, $rcode);
    }

    if ($rcode < 200 || $rcode >= 300) {
      $this->handleErrorResponse($rbody, $rcode, $rheaders, $resp);
    }

    return $resp["data"];
  }

  /**
   * @return HttpClient\ClientInterface
   */
  private function httpClient()
  {
    if (!self::$_httpClient) {
      self::$_httpClient = HttpClient\CurlClient::instance();
    }

    return self::$_httpClient;
  }
}
