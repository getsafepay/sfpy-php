<?php

namespace Safepay\ApiOperations;

use Safepay\Collection;

/**
 * Trait for listable resources. Adds a `all()` static method to the class.
 *
 * This trait should only be applied to classes that derive from SafepayObject.
 */
trait All
{
  /**
   * @param null|array $params
   * @param null|array|string $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Collection of ApiResources
   */
  public static function all($params = null, $opts = null)
  {
    self::_validateParams($params);
    $url = static::classUrl();

    list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
    $obj = \Safepay\Util\Util::convertToSafepayObject(Collection::OBJECT_NAME, $response->json, $opts);
    if (!($obj instanceof \Safepay\Collection)) {
      throw new \Safepay\Exception\UnexpectedValueException(
        'Expected type ' . \Safepay\Collection::class . ', got "' . \get_class($obj) . '" instead.'
      );
    }
    //$obj->setLastResponse($response);

    return $obj;
  }
}
