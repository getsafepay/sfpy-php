<?php


namespace Safepay;

class PaymentMethod extends ApiResource
{
  const OBJECT_NAME = 'payment_method';

  use ApiOperations\Delete;

  public function instanceUrl()
  {
    $token = $this['token'];
    $customer = $this['customer'];
    if (!$token) {
      throw new Exception\UnexpectedValueException(
        "Could not determine which URL to request: class instance has invalid ID: {$token}",
        null
      );
    }
    $token = Util\Util::utf8($token);
    $customer = Util\Util::utf8($customer);
    $base = Customer::classUrl();
    $customerExtn = \urlencode($customer);
    $extn = \urlencode($token);

    return "{$base}/{$customerExtn}/wallet/{$extn}";
  }

  /**
   * @param array|string $_id
   * @param null|array|string $_opts
   *
   * @throws \Safepay\Exception\BadMethodCallException
   */
  public static function retrieve($_id, $_opts = null)
  {
    $msg = 'Payment Methods cannot be retrieved without a ' .
      'customer ID. Retrieve a Payment Method using ' .
      "`Customer::retrievePaymentMethod('customer_id', " .
      "'payment_method')`.";

    throw new Exception\BadMethodCallException($msg);
  }
}
