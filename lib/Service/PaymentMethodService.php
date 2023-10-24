<?php

namespace Safepay\Service;

class PaymentMethodService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'payment_method';

  /**
   * Lists all payment method objects.
   *
   * @param string $parentId
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Collection<\Safepay\PaymentMethod>
   */
  public function all($parentId, $params = null, $opts = null)
  {
    return $this->requestCollection('get', $this->buildPath(':4040/customers/v1/%s/wallet/', $parentId), $params, $opts);
  }

  /**
   * Retrieves a PaymentMethod object.
   *
   * @param string $parentId
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\PaymentMethod
   */
  public function retrieve($parentId, $id, $params = null, $opts = null)
  {
    return $this->request(PaymentMethodService::OBJECT_NAME, 'get', $this->buildPath(':4040/customers/v1/%s/wallet/%s', $parentId, $id), $params, $opts);
  }

  /**
   * Permanently deletes a payment method. It cannot be undone
   *
   * @param string $parentId
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\BaseDeleted
   */
  public function delete($parentId, $id, $params = null, $opts = null)
  {
    return $this->request(\Safepay\BaseDeleted::OBJECT_NAME, 'delete', $this->buildPath(':4040/customers/v1/%s/wallet/%s', $parentId, $id), $params, $opts);
  }
}
