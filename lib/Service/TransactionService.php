<?php

namespace Safepay\Service;

class TransactionService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'transaction';

  /**
   * Filter all transactions objects.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Collection<\Safepay\Transaction>
   */
  public function all($params = null, $opts = null)
  {
    return $this->requestCollection('get', 'client/transactions/v1/search', $params, $opts);
  }

  /**
   * Retrieves a Transaction object.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Transaction
   */
  public function retrieve($id, $params = null, $opts = null)
  {
    return $this->request(TransactionService::OBJECT_NAME, 'get', $this->buildPath('/client/transactions/v1/%s', $id), $params, $opts);
  }

  /**
   * Refunds a Transaction.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Transaction
   */
  public function refund($id, $params = null, $opts = null)
  {
    return $this->request(TransactionService::OBJECT_NAME, 'post', $this->buildPath('/client/transactions/v1/%s/refund', $id), $params, $opts);
  }
}
