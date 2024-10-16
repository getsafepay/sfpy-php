<?php

namespace Safepay;

class BaseSafepayClient implements SafepayClientInterface
{
  /** @var string default base URL for Safepay's API */
  const DEFAULT_API_BASE = "https://api.getsafepay.com";

  /** @var array<string, null|string> */
  const DEFAULT_CONFIG = [
    "api_key" => null,
    "api_base" => self::DEFAULT_API_BASE,
  ];

  /** @var array<string, mixed> */
  private $config;

  /**
   * Initializes a new instance of the {@link BaseSafepayClient} class.
   *
   * The constructor takes a single argument. The argument can be a string, in which case it
   * should be the API key. It can also be an array with various configuration settings.
   *
   * Configuration settings include the following options:
   *
   * - api_key (null|string): the Safepay API key, to be used in regular API requests.
   *
   * The following configuration settings are also available, though setting these should rarely be necessary
   * (only useful if you want to send requests to a mock server like sandbox or development):
   *
   * - api_base (string): the base URL for regular API requests. Defaults to
   *   {@link DEFAULT_API_BASE}.
   *
   * @param array<string, mixed>|string $config the API key as a string, or an array containing
   *   the client configuration settings
   */
  public function __construct($config = [])
  {
    if (\is_string($config)) {
      $config = ["api_key" => $config];
    } elseif (!\is_array($config)) {
      throw new \Safepay\Exception\InvalidArgumentException(
        '$config must be a string or an array'
      );
    }

    $config = \array_merge(self::DEFAULT_CONFIG, $config);
    $this->validateConfig($config);

    $this->config = $config;
  }

  /**
   * Gets the API key used by the client to send requests.
   *
   * @return null|string the API key used by the client to send requests
   */
  public function getApiKey()
  {
    return $this->config["api_key"];
  }

  /**
   * Gets the base URL for Safepay's API.
   *
   * @return string the base URL for Safepay's API
   */
  public function getApiBase()
  {
    return $this->config["api_base"];
  }

  /**
   * Sends a request to Safepay's API.
   *
   * @param 'delete'|'get'|'post'|'put' $method the HTTP method
   * @param string $path the path of the request
   * @param array $params the parameters of the request
   * @param array|\Safepay\Util\RequestOptions $opts the special modifiers of the request
   *
   * @return \Safepay\SafepayObject the object returned by Safepay's API
   */
  public function request($resource, $method, $path, $params, $opts)
  {
    $options = \Safepay\Util\RequestOptions::parse($opts);
    $baseUrl = $opts->apiBase ?: $this->getApiBase();
    $requestor = new \Safepay\ApiRequestor(
      $this->apiKeyForRequest($opts),
      $baseUrl
    );
    list($response, $opts->apiKey) = $requestor->request(
      $method,
      $path,
      $params,
      $opts->headers
    );
    $obj = \Safepay\Util\Util::convertToSafepayObject(
      $resource,
      $response->json,
      $opts
    );
    $obj->setLastResponse($response);

    return $obj;
  }

  /**
   * Sends a request to Safepay's API.
   *
   * @param 'delete'|'get'|'post' $method the HTTP method
   * @param string $path the path of the request
   * @param array $params the parameters of the request
   * @param array|\Safepay\Util\RequestOptions $opts the special modifiers of the request
   *
   * @return \Safepay\Collection of ApiResources
   */
  public function requestCollection($method, $path, $params, $opts)
  {
    $obj = $this->request(
      \Safepay\Collection::OBJECT_NAME,
      $method,
      $path,
      $params,
      $opts
    );
    if (!($obj instanceof \Safepay\Collection)) {
      $received_class = \get_class($obj);
      $msg = "Expected to receive `Safepay\\Collection` object from Safepay API. Instead received `{$received_class}`.";

      throw new \Safepay\Exception\UnexpectedValueException($msg);
    }

    return $obj;
  }

  /**
   * @param \Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\AuthenticationException
   *
   * @return string
   */
  private function apiKeyForRequest($opts)
  {
    $apiKey = $opts->apiKey ?: $this->getApiKey();

    if (null === $apiKey) {
      $msg =
        "No API key provided. Set your API key when constructing the " .
        "SafepayClient instance, or provide it on a per-request basis " .
        'using the `api_key` key in the $opts argument.';

      throw new \Safepay\Exception\AuthenticationException($msg);
    }

    return $apiKey;
  }

  /**
   * @param array<string, mixed> $config
   *
   * @throws \Safepay\Exception\InvalidArgumentException
   */
  private function validateConfig($config)
  {
    // api_key
    if (null !== $config["api_key"] && !\is_string($config["api_key"])) {
      throw new \Safepay\Exception\InvalidArgumentException(
        "api_key must be null or a string"
      );
    }

    if (null !== $config["api_key"] && "" === $config["api_key"]) {
      $msg = "api_key cannot be the empty string";

      throw new \Safepay\Exception\InvalidArgumentException($msg);
    }

    if (
      null !== $config["api_key"] &&
      \preg_match("/\s/", $config["api_key"])
    ) {
      $msg = "api_key cannot contain whitespace";

      throw new \Safepay\Exception\InvalidArgumentException($msg);
    }

    // api_base
    if (!\is_string($config["api_base"])) {
      throw new \Safepay\Exception\InvalidArgumentException(
        "api_base must be a string"
      );
    }

    // check absence of extra keys
    $extraConfigKeys = \array_diff(
      \array_keys($config),
      \array_keys(self::DEFAULT_CONFIG)
    );
    if (!empty($extraConfigKeys)) {
      // Wrap in single quote to more easily catch trailing spaces errors
      $invalidKeys = "'" . \implode("', '", $extraConfigKeys) . "'";

      throw new \Safepay\Exception\InvalidArgumentException(
        "Found unknown key(s) in configuration array: " . $invalidKeys
      );
    }
  }
}
