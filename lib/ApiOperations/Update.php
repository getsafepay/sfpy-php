<?php

namespace Safepay\ApiOperations;

/**
 * Trait for updatable resources. Adds an `update()` static method and a
 * `save()` method to the class.
 *
 * This trait should only be applied to classes that derive from SafepayObject.
 */
trait Update
{
  /**
   * @param string $id the ID of the resource to update
   * @param null|array $params
   * @param null|array|string $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return static the updated resource
   */
  public static function update($resource = null, $id, $params = null, $opts = null)
  {
    self::_validateParams($params);
    $url = static::resourceUrl($id);

    list($response, $opts) = static::_staticRequest('put', $url, $params, $opts);
    $obj = \Safepay\Util\Util::convertToSafepayObject($resource, $response->json, $opts);
    $obj->setLastResponse($response);

    return $obj;
  }
}
