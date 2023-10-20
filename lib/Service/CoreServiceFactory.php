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

    // Class Map: The end of the section generated from our OpenAPI spec
  ];

  protected function getServiceClass($name)
  {
    return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
  }
}
