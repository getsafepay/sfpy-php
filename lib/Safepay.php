<?php

namespace Safepay;

class Safepay
{
  /** @var string The Safepay API key to be used for requests. */
  public static $apiKey;

  /** @var string The base URL for the Safepay API. */
  public static $apiBase = 'https://api.getsafepay.com';

  /**
   * @return string the API key used for requests
   */
  public static function getApiKey()
  {
    return self::$apiKey;
  }

  /**
   * Sets the API key to be used for requests.
   *
   * @param string $apiKey
   */
  public static function setApiKey($apiKey)
  {
    self::$apiKey = $apiKey;
  }

  /**
   * @return string the API key used for requests
   */
  public static function getApiBase()
  {
    return self::$apiBase;
  }

  /**
   * Sets the API key to be used for requests.
   *
   * @param string $apiBase
   */
  public static function setApiBase($apiBase)
  {
    self::$apiBase = $apiBase;
  }
}
