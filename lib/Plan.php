<?php


namespace Safepay;

class Plan extends ApiResource
{
  const OBJECT_NAME = 'plan';
  const OBJECT_PATH = 'client.plans.v1';

  public function instanceUrl()
  {
    if (null === $this['token']) {
      return '/client/plans/v1/';
    }

    return parent::instanceUrl();
  }
}
