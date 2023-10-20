<?php

namespace Safepay\ApiOperations;

/**
 * Trait for retrievable resources. Adds a `retrieve()` static method to the
 * class.
 *
 * This trait should only be applied to classes that derive from SafepayObject.
 */
trait Retrieve
{
  /**
   * @param array|string $id the token of the API resource to retrieve,
   *     or an options array containing an `token` key
   * @param null|array|string $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return static
   */
  public static function retrieve($id, $opts = null)
  {
    $opts = \Safepay\Util\RequestOptions::parse($opts);
    $instance = new static($id, $opts);
    $instance->refresh();

    return $instance;
  }
}
