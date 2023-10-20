<?php

namespace Safepay\Service;

/**
 * Service factory class for API resources in the root namespace.
 *
 
 */
class CoreServiceFactory extends \Safepay\Service\AbstractServiceFactory
{
  /**
   * @var array<string, string>
   */
  private static $classMap = [

    'order' => OrderService::class
  ];

  protected function getServiceClass($name)
  {
    return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
  }
}
