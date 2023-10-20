<?php

namespace Safepay\Util;

class ObjectTypes
{
  /**
   * @var array Mapping from object types to resource classes
   */
  const mapping =
  [
    \Safepay\Order::OBJECT_NAME => \Safepay\Order::class,
  ];
}
