<?php


namespace Safepay;

class Subscription extends ApiResource
{
  const OBJECT_NAME = 'subscription';
  const OBJECT_PATH = 'client.subscriptions.v1';

  public function instanceUrl()
  {
    if (null === $this['token']) {
      return '/client/subscriptions/v1/';
    }

    return parent::instanceUrl();
  }
}
