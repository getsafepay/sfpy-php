<?php


namespace Safepay;

class Transaction extends ApiResource
{
  const OBJECT_NAME = 'transaction';
  const OBJECT_PATH = 'client.transactions.v1';

  public function instanceUrl()
  {
    if (null === $this['token']) {
      return '/client/transactions/v1/';
    }

    return parent::instanceUrl();
  }
}
