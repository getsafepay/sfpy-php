<?php


namespace Safepay;

/**
 * Events are our way of letting you know when something interesting happens in
 * your account. When an interesting event occurs, we create a new <code>Event</code>
 * object. For example, when a payment succeeds, we create a <code>payment.succeeded</code>
 * event, and when a payment attempt fails, we create an
 * <code>payment.failed</code> event. Certain API requests might create multiple
 * events. For example, if you create a new subscription for a
 * customer, you receive both a <code>subscription.created</code> event and a
 * <code>payment.succeeded</code> event.
 *
 *
 * This class includes constants for the possible string representations of
 * event types.
 *
 * @property string $token Unique identifier for the object.
 * @property null|string $merchant_api_key The connected account that originates the event.
 * @property null|string $version The Safepay API version used to render <code>data</code>.
 * @property int $created_at Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property \Safepay\SafepayObject $data
 * @property string $type Description of the event (for example, <code>payment.succeeded</code> or <code>payment.refunded</code>).
 */
class Event extends ApiResource
{
  const OBJECT_NAME = 'event';

  const PAYMENT_SUCCEEDED = 'payment.succeeded';
  const PAYMENT_FAILED = 'payment.failed';
  const PAYMENT_REFUNDED = 'payment.refunded';
  const AUTHORIZATION_REVERSED = 'authorization.reversed';
  const VOID_SUCCEEDED = 'void.succeeded';

  const SUBSCRIPTION_CREATED = 'subscription.created';
  const SUBSCRIPTION_CANCELED = 'subscription.canceled';
  const SUBSCRIPTION_ENDED = 'subscription.ended';
  const SUBSCRIPTION_PAUSED = 'subscription.paused';
  const SUBSCRIPTION_RESUMED = 'subscription.resumed';
  const SUBSCRIPTION_PAYMENT_SUCCEEDED = 'subscription.payment.succeeded';
}
