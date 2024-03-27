<?php

namespace Safepay;

class BasePlanResponse extends ApiResource
{
  const OBJECT_NAME = 'base_plan_response';
  /**
   * Provides the Request ID of the operation
   *
   * @return string the Request ID
   */
  public function requestId()
  {
    return isset($this->_values['request_id']) ? $this->_values['request_id'] : "";
  }

  /**
   * Returns the plan_id
   *
   * @return string
   */
  public function planId()
  {
    return isset($this->_values['plan_id']) ? $this->_values['plan_id'] : "";
  }
}
