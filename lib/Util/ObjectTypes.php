<?php

namespace Safepay\Util;

class ObjectTypes
{
  /**
   * @var array Mapping from object types to resource classes
   */
  const mapping =
  [
    \Safepay\Collection::OBJECT_NAME => \Safepay\Collection::class,
    \Safepay\Order::OBJECT_NAME => \Safepay\Order::class,
    \Safepay\Customer::OBJECT_NAME => \Safepay\Customer::class,
    \Safepay\PaymentMethod::OBJECT_NAME => \Safepay\PaymentMethod::class,
  ];
}
