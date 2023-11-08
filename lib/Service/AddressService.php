<?php

namespace Safepay\Service;

class AddressService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'address';

  /**
   * Creates a new customer address object.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Address
   */
  public function create($params = null, $opts = null)
  {
    return $this->request(AddressService::OBJECT_NAME, 'post', '/user/address/v2/', $params, $opts);
  }

  /**
   * Updates the specified address by setting the values of the parameters passed.
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
   * @return \Safepay\Address
   */
  public function update($id, $params = null, $opts = null)
  {
    return $this->request(AddressService::OBJECT_NAME, 'put', $this->buildPath('/user/address/v2/%s', $id), $params, $opts);
  }

  /**
   * Retrieves a Address object.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Address
   */
  public function retrieve($id, $params = null, $opts = null)
  {
    return $this->request(AddressService::OBJECT_NAME, 'get', $this->buildPath('/user/address/v2/%s', $id), $params, $opts);
  }
}
