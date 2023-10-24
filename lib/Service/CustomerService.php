<?php

namespace Safepay\Service;

use Safepay\BaseDeleted;

class CustomerService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'customer';

  /**
   * Lists all customer objects.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Collection<\Safepay\Customer>
   */
  public function all($params = null, $opts = null)
  {
    return $this->requestCollection('get', ':4040/customers/v1/', $params, $opts);
  }

  /**
   * Creates a new customer object.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Customer
   */
  public function create($params = null, $opts = null)
  {
    return $this->request(CustomerService::OBJECT_NAME, 'post', ':4040/customers/v1/', $params, $opts);
  }

  /**
   * Updates the specified customer by setting the values of the parameters passed.
   * Any parameters not provided will be left unchanged. For example, if you pass the
   *
   * This request accepts mostly the same arguments as the customer creation call.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Customer
   */
  public function update($id, $params = null, $opts = null)
  {
    return $this->request(CustomerService::OBJECT_NAME, 'put', $this->buildPath(':4040/customers/v1/%s', $id), $params, $opts);
  }

  /**
   * Retrieves a Customer object.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Customer
   */
  public function retrieve($id, $params = null, $opts = null)
  {
    return $this->request(CustomerService::OBJECT_NAME, 'get', $this->buildPath(':4040/customers/v1/%s', $id), $params, $opts);
  }

  /**
   * Permanently deletes a customer. It cannot be undone
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\BaseDeleted
   */
  public function delete($id, $params = null, $opts = null)
  {
    return $this->request(\Safepay\BaseDeleted::OBJECT_NAME, 'delete', $this->buildPath(':4040/customers/v1/%s', $id), $params, $opts);
  }
}
