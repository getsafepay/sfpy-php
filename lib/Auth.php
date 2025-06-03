<?php


namespace Safepay;

class Auth extends ApiResource
{
  const OBJECT_NAME = 'auth';
  const OBJECT_PATH = 'auth.v2.user';

  public function instanceUrl()
  {
      return '/auth/v2/user/';
  }
}
