<?php

namespace Safepay\Service;

use Safepay\BaseDeleted;

class UserService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'user';

  /**
   * Registers a new user.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\User
   */
  public function register($params = null, $opts = null)
  {
    return $this->request(UserService::OBJECT_NAME, 'post', '/user/v2/', $params, $opts);
  }

  /**
   * Changes the user's password.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\User
   */
  public function changePassword($params = null, $opts = null)
  {
    return $this->request(UserService::OBJECT_NAME, 'post', '/user/v2/change-password', $params, $opts);
  }

}
