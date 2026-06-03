<?php


namespace Safepay;

class User extends ApiResource
{
  const OBJECT_NAME = 'user';
  const OBJECT_PATH = 'user.v2';

  public function instanceUrl()
  {
      return '/user/v2/';
  }
}
