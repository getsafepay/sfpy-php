<?php

namespace Safepay;

/**
 * Client used to send requests to Safepay's API.
 *
 * 
 */
class SafepayClient extends BaseSafepayClient
{
  /**
   * @var \Safepay\Service\CoreServiceFactory
   */
  private $coreServiceFactory;

  public function __get($name)
  {
    return $this->getService($name);
  }

  public function getService($name)
  {
    if (null === $this->coreServiceFactory) {
      $this->coreServiceFactory = new \Safepay\Service\CoreServiceFactory($this);
    }

    return $this->coreServiceFactory->getService($name);
  }
}
