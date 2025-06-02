<?php

namespace Safepay\ApiOperations;

/**
 * Trait for resources that need to make API requests.
 *
 * This trait should only be applied to classes that derive from SafepayObject.
 */
trait Request
{
  /**
   * @param null|array|mixed $params The list of parameters to validate
   *
   * @throws \Safepay\Exception\InvalidArgumentException if $params exists and is not an array
   */
  protected static function _validateParams($params = null)
  {
    if ($params && !\is_array($params)) {
      $message = 'You must pass an array as the first argument to Safepay API '
        . 'method calls.  (HINT: an example call to create a customer '
        . "would be: \"Safepay\\Customer::create(['first_name' => 'ziyad', "
        . "'last_name' => 'parekh', 'email' => 'ziyad@safepay.com'])\")";

      throw new \Safepay\Exception\InvalidArgumentException($message);
    }
  }

  /**
   * @param 'delete'|'get'|'post' $method HTTP method ('get', 'post', etc.)
   * @param string $url URL for the request
   * @param array $params list of parameters for the request
   * @param null|array|string $options
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return array tuple containing (the JSON response, $options)
   */
  protected function _request($method, $url, $params = [], $options = null)
  {
    $opts = $this->_opts->merge($options);
    list($resp, $options) = static::_staticRequest($method, $url, $params, $opts);
    $this->setLastResponse($resp);

    return [$resp->json, $options];
  }

  /**
   * @param 'delete'|'get'|'post' $method HTTP method ('get', 'post', etc.)
   * @param string $url URL for the request
   * @param array $params list of parameters for the request
   * @param null|array|string $options
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return array tuple containing (the JSON response, $options)
   */
  protected static function _staticRequest($method, $url, $params, $options)
  {
    $opts = \Safepay\Util\RequestOptions::parse($options);
    $baseUrl = isset($opts->apiBase) ? $opts->apiBase : static::baseUrl();
    $requestor = new \Safepay\ApiRequestor($opts->authType, $opts->apiKey, $baseUrl);
    list($response, $opts->apiKey) = $requestor->request($method, $url, $params, $opts->headers);

    return [$response, $opts];
  }
}
