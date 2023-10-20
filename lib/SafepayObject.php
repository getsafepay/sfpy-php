<?php

namespace Safepay;

/**
 * Class SafepayObject.
 *
 * @property null|string $id
 */
class SafepayObject implements \ArrayAccess, \Countable, \JsonSerializable
{
  /** @var Util\RequestOptions */
  protected $_opts;

  /** @var array */
  protected $_values;

  /** @var null|array */
  protected $_retrieveOptions;

  /** @var null|ApiResponse */
  protected $_lastResponse;

  /**
   * @return Util\Set Attributes that should not be sent to the API because
   *    they're not updatable (e.g. Token).
   */
  public static function getPermanentAttributes()
  {
    static $permanentAttributes = null;
    if (null === $permanentAttributes) {
      $permanentAttributes = new Util\Set([
        'token',
      ]);
    }

    return $permanentAttributes;
  }

  public function __construct($id = null, $opts = null)
  {
    list($id, $this->_retrieveOptions) = Util\Util::normalizeId($id);
    $this->_opts = Util\RequestOptions::parse($opts);
    $this->_values = [];
    if (null !== $id) {
      $this->_values['token'] = $id;
    }
  }

  /**
   * This unfortunately needs to be public to be used in Util\Util.
   *
   * @param array $values
   * @param null|array|string|Util\RequestOptions $opts
   *
   * @return static the object constructed from the given values
   */
  public static function constructFrom($values, $opts = null)
  {
    $obj = new static(isset($values['token']) ? $values['token'] : null);
    $obj->refreshFrom($values, $opts);

    return $obj;
  }

  /**
   * Refreshes this object using the provided values.
   *
   * @param array $values
   * @param null|array|string|Util\RequestOptions $opts
   * @param bool $partial defaults to false
   */
  public function refreshFrom($values, $opts, $partial = false)
  {
    $this->_opts = Util\RequestOptions::parse($opts);

    if ($values instanceof SafepayObject) {
      $values = $values->toArray();
    }

    // Wipe old state before setting new.  This is useful for e.g. updating a
    // customer, where there is no persistent card parameter.  Mark those values
    // which don't persist as transient
    if ($partial) {
      $removed = new Util\Set();
    } else {
      $removed = new Util\Set(\array_diff(\array_keys($this->_values), \array_keys($values)));
    }

    foreach ($removed->toArray() as $k) {
      unset($this->{$k});
    }

    $this->updateAttributes($values, $opts);
  }

  /**
   * Mass assigns attributes on the model.
   *
   * @param array $values
   * @param null|array|string|Util\RequestOptions $opts
   * @param bool $dirty defaults to true
   */
  public function updateAttributes($values, $opts = null)
  {
    foreach ($values as $k => $v) {
      // Special-case metadata to always be cast as a SafepayObject
      // This is necessary in case metadata is empty, as PHP arrays do
      // not differentiate between lists and hashes, and we consider
      // empty arrays to be lists.
      $this->_values[$k] = SafepayObject::constructFrom($v, $opts);
    }
  }

  /**
   * @param mixed $k
   *
   * @return bool
   */
  public function __isset($k)
  {
    return isset($this->_values[$k]);
  }

  public function __unset($k)
  {
    unset($this->_values[$k]);
  }

  // ArrayAccess methods

  /**
   * @return void
   */
  #[\ReturnTypeWillChange]
  public function offsetSet($k, $v)
  {
    $this->{$k} = $v;
  }

  /**
   * @return bool
   */
  #[\ReturnTypeWillChange]
  public function offsetExists($k)
  {
    return \array_key_exists($k, $this->_values);
  }

  /**
   * @return void
   */
  #[\ReturnTypeWillChange]
  public function offsetUnset($k)
  {
    unset($this->{$k});
  }

  /**
   * @return mixed
   */
  #[\ReturnTypeWillChange]
  public function offsetGet($k)
  {
    return \array_key_exists($k, $this->_values) ? $this->_values[$k] : null;
  }

  /**
   * @return int
   */
  #[\ReturnTypeWillChange]
  public function count()
  {
    return \count($this->_values);
  }

  public function keys()
  {
    return \array_keys($this->_values);
  }

  public function values()
  {
    return \array_values($this->_values);
  }

  /**
   * @return mixed
   */
  #[\ReturnTypeWillChange]
  public function jsonSerialize()
  {
    return $this->toArray();
  }

  /**
   * Returns an associative array with the key and values composing the
   * Safepay object.
   *
   * @return array the associative array
   */
  public function toArray()
  {
    $maybeToArray = function ($value) {
      if (null === $value) {
        return null;
      }

      return \is_object($value) && \method_exists($value, 'toArray') ? $value->toArray() : $value;
    };

    return \array_reduce(\array_keys($this->_values), function ($acc, $k) use ($maybeToArray) {
      if ('_' === \substr((string) $k, 0, 1)) {
        return $acc;
      }
      $v = $this->_values[$k];
      if (Util\Util::isList($v)) {
        $acc[$k] = \array_map($maybeToArray, $v);
      } else {
        $acc[$k] = $maybeToArray($v);
      }

      return $acc;
    }, []);
  }

  /**
   * Produces a deep copy of the given object including support for arrays
   * and SafepayObjects.
   *
   * @param mixed $obj
   */
  protected static function deepCopy($obj)
  {
    if (\is_array($obj)) {
      $copy = [];
      foreach ($obj as $k => $v) {
        $copy[$k] = self::deepCopy($v);
      }

      return $copy;
    }
    if ($obj instanceof SafepayObject) {
      return $obj::constructFrom(
        self::deepCopy($obj->_values),
        clone $obj->_opts
      );
    }

    return $obj;
  }

  /**
   * @return null|ApiResponse The last response from the Safepay API
   */
  public function getLastResponse()
  {
    return $this->_lastResponse;
  }

  /**
   * Sets the last response from the Safepay API.
   *
   * @param ApiResponse $resp
   */
  public function setLastResponse($resp)
  {
    $this->_lastResponse = $resp;
  }

  /**
   * Indicates whether or not the resource has been deleted on the server.
   * Note that some, but not all, resources can indicate whether they have
   * been deleted.
   *
   * @return bool whether the resource is deleted
   */
  public function isDeleted()
  {
    return isset($this->_values['is_deleted']) ? $this->_values['is_deleted'] : false;
  }
}
