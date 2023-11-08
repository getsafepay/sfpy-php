<?php


namespace Safepay;

class Address extends ApiResource
{
  const OBJECT_NAME = 'address';
  const OBJECT_PATH = 'user.address.v2';

  public function instanceUrl()
  {
    if (null === $this['token']) {
      return '/user/address/v2/';
    }

    return parent::instanceUrl();
  }
}
