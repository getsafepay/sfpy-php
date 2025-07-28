<?php

namespace Safepay\Service;

class ReporterService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'reporter';

  /**
   * Filter all payments objects.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Collection<\Safepay\Tracker>
   */
  public function all($params = null, $opts = null)
  {
    return $this->requestCollection('get', '/reporter/api/v1/payments', $params, $opts);
  }
  
  /**
   * Retrieves a Payment object.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Tracker
   */
  public function retrieve($id, $params = null, $opts = null)
  {
    return $this->request(ReporterService::OBJECT_NAME, 'get', $this->buildPath('/reporter/api/v1/payments/%s', $id), $params, $opts);
  }
}
