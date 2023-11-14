<?php


namespace Safepay;

class Passport extends ApiResource
{
  const OBJECT_NAME = 'passport';

  /**
   * We need to override this because $values is 
   * a string. So we need to convert this into an 
   * object where token => tbt to conform to the rest
   * of the objects
   * @param array $values
   * @param null|array|string|Util\RequestOptions $opts
   *
   * @return static the passport object constructed from the given values
   */
  public static function constructFrom($values, $opts = null)
  {
    $dict = array();
    if (\is_string($values)) {
      $dict = array(
        "token" => $values
      );
    } else if (\is_array($values)) {
      $dict = $values;
    }

    $obj = new static($dict);
    $obj->refreshFrom($dict, $opts);

    return $obj;
  }
}
