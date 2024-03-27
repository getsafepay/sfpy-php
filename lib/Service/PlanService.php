<?php

namespace Safepay\Service;

use Safepay\BasePlanResponse;

class PlanService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'plan';

  /**
   * Filter all plans objects.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Collection<\Safepay\Plan>
   */
  public function all($params = null, $opts = null)
  {
    return $this->requestCollection('get', 'client/plans/v1/search', $params, $opts);
  }

  /**
   * Creates a new plan object.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\BasePlanResponse
   */
  public function create($params = null, $opts = null)
  {
    return $this->request(\Safepay\BasePlanResponse::OBJECT_NAME, 'post', '/client/plans/v1/', $params, $opts);
  }

  /**
   * Updates the specified plan by setting the values of the parameters passed.
   * Any parameters not provided will be left unchanged.
   *
   * This request accepts mostly the same arguments as the plan creation call.
   * 
   * By setting the `active` parameter to `false` no new subscriptions will be allowed to subscribe 
   * to this plan but existing subscriptions will continue to be charged until each subscription 
   * either runs its course or is explicitly paused or cancelled. 
   * 
   * By changing the value of `trial_period_days` only new subscriptions will be affected. 
   * Existing subscriptions will continue to refer to the previous `trial_period_days` value.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\BasePlanResponse
   */
  public function update($id, $params = null, $opts = null)
  {
    return $this->request(\Safepay\BasePlanResponse::OBJECT_NAME, 'put', $this->buildPath('/client/plans/v1/%s', $id), $params, $opts);
  }

  /**
   * Retrieves a Plan object.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Plan
   */
  public function retrieve($id, $params = null, $opts = null)
  {
    return $this->request(PlanService::OBJECT_NAME, 'get', $this->buildPath('/client/plans/v1/%s', $id), $params, $opts);
  }

  /**
   * Archiving plans means new subscribers can’t be added. 
   * Existing subscribers aren’t affected. By passing in the `plan_id` in the URL, you can 
   * archive a plan that you no longer need. Archived plans will not show up in your 
   * search results. 
   * 
   * Archiving a plan is a one-way action, meaning that once a plan is archived, it can not be unarchived.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\BasePlanResponse
   */
  public function delete($id, $params = null, $opts = null)
  {
    return $this->request(\Safepay\BasePlanResponse::OBJECT_NAME, 'delete', $this->buildPath('/client/plans/v1/%s', $id), $params, $opts);
  }
}
