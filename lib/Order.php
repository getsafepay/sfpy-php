<?php


namespace Safepay;

class Order extends ApiResource
{
  const OBJECT_NAME = 'order';
  const OBJECT_PATH = ':4010.payments.v3';


  use ApiOperations\Create;

  const MODE_PAYMENT = 'payment';
  const MODE_INSTRUMENT = 'instrument';
  const MODE_COF = 'cof';
  const MODE_UNSCHEDULED_COF = 'unscheduled_cof';

  public function instanceUrl()
  {
    if (null === $this->tracker->token) {
      return ':4010/payments/v3/';
    }

    $this->token = $this->tracker->token;
    return parent::instanceUrl();
  }

  public function charge($params = null, $opts = null)
  {
    $url = $this->instanceUrl();
    list($response, $opts) = $this->_request('post', $url, $params, $opts);
    $obj = \Safepay\Util\Util::convertToSafepayObject(Order::OBJECT_NAME, $response, $opts);
    $obj->setLastResponse($response);

    return $obj;
  }
}
