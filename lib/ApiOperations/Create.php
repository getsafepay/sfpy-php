<?php

namespace Safepay\ApiOperations;

/**
 * Trait for creatable resources. Adds a `create()` static method to the class.
 *
 * This trait should only be applied to classes that derive from SafepayObject.
 */
trait Create
{
  /**
   * @param null|string $resource
   * @param null|array $params
   * @param null|array|string $options
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return static the created resource
   */
  public static function create($resource = null, $params = null, $options = null)
  {
    self::_validateParams($params);
    $url = static::classUrl();

    list($response, $opts) = static::_staticRequest('post', $url, $params, $options);
    $obj = \Safepay\Util\Util::convertToSafepayObject($resource, $response->json, $opts);
    $obj->setLastResponse($response);

    return $obj;
  }
}
