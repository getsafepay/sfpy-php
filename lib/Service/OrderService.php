<?php

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

  /**
   * Returns the tracker object.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Order
   */
  public function charge($id, $params = null, $opts = null)
  {
    return $this->request(OrderService::OBJECT_NAME, 'post', $this->buildPath('/order/payments/v3/%s', $id), $params, $opts);
  }

  /**
   * Returns the tracker object.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Order
   */
  public function refund($id, $params = null, $opts = null)
  {
    return $this->request(OrderService::OBJECT_NAME, 'post', $this->buildPath('/order/payments/v3/%s/refund', $id), $params, $opts);
  }

  /**
   * Returns the tracker object.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Order
   */
  public function reverse($id, $params = null, $opts = null)
  {
    return $this->request(OrderService::OBJECT_NAME, 'post', $this->buildPath('/order/payments/v3/%s/reversal', $id), $params, $opts);
  }

  /**
   * Returns the tracker object.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Order
   */
  public function void($id, $params = null, $opts = null)
  {
    return $this->request(OrderService::OBJECT_NAME, 'post', $this->buildPath('/order/payments/v3/%s/void', $id), $params, $opts);
  }

  /**
   * Returns the tracker object.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Order
   */
  public function metadata($id, $params = null, $opts = null)
  {
    return $this->request(OrderService::OBJECT_NAME, 'post', $this->buildPath('/order/payments/v3/%s/metadata', $id), $params, $opts);
  }
}
