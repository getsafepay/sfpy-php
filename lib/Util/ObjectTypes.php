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
    \Safepay\Tracker::OBJECT_NAME => \Safepay\Tracker::class,
    \Safepay\Customer::OBJECT_NAME => \Safepay\Customer::class,
    \Safepay\PaymentMethod::OBJECT_NAME => \Safepay\PaymentMethod::class,
    \Safepay\Passport::OBJECT_NAME => \Safepay\Passport::class,
    \Safepay\Event::OBJECT_NAME => \Safepay\Event::class,
    \Safepay\Address::OBJECT_NAME => \Safepay\Address::class,
  ];
}
