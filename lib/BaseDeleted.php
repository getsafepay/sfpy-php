<?php

namespace Safepay;

class BaseDeleted extends ApiResource
{
  const OBJECT_NAME = 'base_deleted';
  /**
   * Indicates whether or not the resource has been deleted on the server.
   * Note that some, but not all, resources can indicate whether they have
   * been deleted.
   *
   * @return bool whether the resource is deleted
   */
  public function isDeleted()
  {
    return isset($this->_values['deleted']) ? $this->_values['deleted'] : false;
  }

  /**
   * Returns the token of the deleted resource
   *
   * @return string the token of the deleted resource
   */
  public function token()
  {
    return isset($this->_values['token']) ? $this->_values['token'] : "";
  }
}
