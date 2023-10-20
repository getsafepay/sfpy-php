<?php

// File generated from our OpenAPI spec

namespace Safepay\Service;

class OrderService extends \Safepay\Service\AbstractService
{
  const OBJECT_NAME = 'order';

  /**
   * Creates a new tracker object.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Order
   */
  public function setup($params = null, $opts = null)
  {
    return $this->request(OrderService::OBJECT_NAME, 'post', '/order/payments/v3/', $params, $opts);
  }
}
