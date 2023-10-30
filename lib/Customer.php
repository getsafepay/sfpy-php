<?php


namespace Safepay;

class Customer extends ApiResource
{
  const OBJECT_NAME = 'customer';
  const OBJECT_PATH = 'user.customers.v1';

  public function instanceUrl()
  {
    if (null === $this['token']) {
      return '/user/customers/v1/';
    }

    return parent::instanceUrl();
  }
}
