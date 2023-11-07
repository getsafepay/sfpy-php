<?php

namespace Safepay\Service;

class PassportService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'passport';

  /**
   * Creates a Time Based Token (TBT). 
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Passport
   */
  public function create($params = null, $opts = null)
  {
    return $this->request(\Safepay\Passport::OBJECT_NAME, 'post', '/client/passport/v1/token', $params, $opts);
  }
}
