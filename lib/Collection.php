<?php

namespace Safepay;

/**
 * Class Collection.
 *
 * @template TSafepayObject of SafepayObject
 * @template-implements \Countable<TSafepayObject>
 *
 * @property string $object
 * @property TSafepayObject[] $data
 */
class Collection extends SafepayObject implements \Countable
{
  const OBJECT_NAME = 'list';
  /**
   * @return mixed
   */
  #[\ReturnTypeWillChange]
  public function offsetGet($k)
  {
    if (\is_string($k)) {
      return parent::offsetGet($k);
    }
    $msg = "You tried to access the {$k} index, but Collection " .
      'types only support string keys. (HINT: List calls ' .
      'return an object with a `data` (which is the data ' .
      "array). You likely want to call ->data[{$k}])";

    throw new Exception\InvalidArgumentException($msg);
  }

  /**
   * @return int the number of objects in the current page
   */
  #[\ReturnTypeWillChange]
  public function count()
  {
    return $this->count;
  }
}
