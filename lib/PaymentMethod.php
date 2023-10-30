<?php


namespace Safepay;

class PaymentMethod extends ApiResource
{
  const OBJECT_NAME = 'payment_method';

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
}
