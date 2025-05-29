<?php

namespace Safepay\Service;

use Safepay\BaseDeleted;

class AuthService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'user';

  /**
   * Logs a new user in.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\User
   */
  public function login($params = null, $opts = null)
  {
    return $this->request(UserService::OBJECT_NAME, 'post', '/auth/v2/user/login', $params, $opts);
  }

}
