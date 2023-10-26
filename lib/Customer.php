<?php


namespace Safepay;

class Customer extends ApiResource
{
  const OBJECT_NAME = 'customer';
  const OBJECT_PATH = 'user.customers.v1';


  use ApiOperations\All;
  use ApiOperations\Retrieve;
  use ApiOperations\NestedResource;

  public function instanceUrl()
  {
    if (null === $this['token']) {
      return '/user/customers/v1/';
    }

    return parent::instanceUrl();
  }

  /**
   * @param string $payment_method
   * @param null|array $params
   * @param null|array|string $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\PaymentMethod the retrieved payment method
   */
  public function retrievePaymentMethod($payment_method, $params = null, $opts = null)
  {
    $url = $this->instanceUrl() . '/wallet/' . $payment_method;
    list($response, $opts) = $this->_request('get', $url, $params, $opts);
    $obj = \Safepay\Util\Util::convertToSafepayObject(PaymentMethod::OBJECT_NAME, $response, $opts);
    $obj->setLastResponse($response);

    return $obj;
  }

  const PATH_WALLET = '/wallet';

  /**
   * @param string $id
   * @param null|array $params
   * @param null|array|string $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Collection<\Safepay\PaymentMethod> list of payment methods
   */
  public function allPaymentMethods($id, $params = null, $opts = null)
  {
    $url = $this->instanceUrl() . '/wallet/';
    list($response, $opts) = $this->_request('get', $url, $params, $opts);
    $obj = \Safepay\Util\Util::convertToSafepayObject(Collection::OBJECT_NAME, $response, $opts);
    $obj->setLastResponse($response);

    return $obj;
  }

  /**
   * @param string $id the ID of the customer to which the payment method belongs
   * @param string $methodId the ID of the payment method to delete
   * @param null|array $params
   * @param null|array|string $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\SafepayObject
   */
  public static function deletePaymentMethod($id, $sourceId, $params = null, $opts = null)
  {
    return self::_deleteNestedResource(BaseDeleted::OBJECT_NAME, $id, static::PATH_WALLET, $sourceId, $params, $opts);
  }
}
