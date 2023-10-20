<?php


namespace Safepay;

class Order extends ApiResource
{
  const OBJECT_NAME = 'order';
  const OBJECT_PATH = 'order.payments.v3';


  use ApiOperations\Create;

  const MODE_PAYMENT = 'payment';
  const MODE_INSTRUMENT = 'instrument';
  const MODE_COF = 'cof';
  const MODE_UNSCHEDULED_COF = 'unscheduled_cof';

  public function instanceUrl()
  {
    if (null === $this['token']) {
      return '/order/payments/v3/';
    }

    return parent::instanceUrl();
  }
}
