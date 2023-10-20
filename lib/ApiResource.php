<?php

namespace Safepay;

/**
 * Class ApiResource.
 *
 * */
abstract class ApiResource extends SafepayObject
{
  use ApiOperations\Request;

  /**
   * @return string the base URL for the given class
   */
  public static function baseUrl()
  {
    return Safepay::$apiBase;
  }

  /**
   * @return string the endpoint URL for the given class
   */
  public static function classUrl()
  {
    // Replace dots with slashes for namespaced resources, e.g. if the object's name is
    // "order.payments.v3", then its URL will be "/order/payments/v3/".

    /** @phpstan-ignore-next-line */
    $base = \str_replace('.', '/', static::OBJECT_PATH);

    return "/{$base}/";
  }
  /**
   * @param null|string $id the Token of the resource
   *
   * @throws Exception\UnexpectedValueException if $id is null
   *
   * @return string the instance endpoint URL for the given class
   */
  public static function resourceUrl($id)
  {
    if (null === $id) {
      $class = static::class;
      $message = 'Could not determine which URL to request: '
        . "{$class} instance has invalid ID: {$id}";

      throw new Exception\UnexpectedValueException($message);
    }
    $id = Util\Util::utf8($id);
    $base = static::classUrl();
    $extn = \urlencode($id);

    return "{$base}/{$extn}";
  }

  /**
   * @return string the full API URL for this API resource
   */
  public function instanceUrl()
  {
    return static::resourceUrl($this['token']);
  }
}
