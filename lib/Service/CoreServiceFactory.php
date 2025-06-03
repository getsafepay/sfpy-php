<?php

namespace Safepay\Service;

use Safepay\PaymentMethod;

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
    'order' => OrderService::class,
    'customer' => CustomerService::class,
    'payment_method' => PaymentMethodService::class,
    'passport' => PassportService::class,
    'address' => AddressService::class,
    'plan' => PlanService::class,
    'subscription' => SubscriptionService::class,
    'transaction' => TransactionService::class,
    'user' => UserService::class,
    'auth' => AuthService::class,
  ];

  protected function getServiceClass($name)
  {
    return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
  }
}
