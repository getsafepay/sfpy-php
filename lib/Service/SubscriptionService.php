<?php

namespace Safepay\Service;

class SubscriptionService extends \Safepay\Service\AbstractService
{

  const OBJECT_NAME = 'subscription';

  /**
   * Retrieves a Subscription object.
   *
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Subscription
   */
  public function retrieve($id, $params = null, $opts = null)
  {
    return $this->request(SubscriptionService::OBJECT_NAME, 'get', $this->buildPath('/client/subscriptions/v1/%s', $id), $params, $opts);
  }

  /**
   * Filter all subscriptions objects.
   *
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\Collection<\Safepay\Subscription>
   */
  public function all($params = null, $opts = null)
  {
    return $this->requestCollection('get', 'client/subscriptions/v1/search', $params, $opts);
  }

  /**
   * Updates an existing subscription to match the specified parameters.
   * Its important to understand how updates to a subscription work especially when it comes 
   * to pausing a subscription, the proration behavior and how they work together. 
   * 
   * Changing the billing_cycle_anchor will change the date the subscription is billed. 
   * Setting the value to now resets the subscription’s billing cycle anchor to the current time (in UTC).
   * 
   * Changing the trial_end will chage the date the trial_period ends. This will always overwrite any trials 
   * that might apply via a subscribed plan. If set, trial_end will override the default trial period of 
   * the plan the customer is being subscribed to. The special value now can be provided to end the 
   * customer’s trial immediately.
   * 
   * Setting the trial_end to a future date and billing_cycle_anchor to now will result in an error since that is not allowed.
   * 
   * If the subscription is currently in a trial period, setting the billing_cycle_anchor to now will result in an error
   * 
   * If pause_collection is specified, payment collection for this subscription will be paused. based on the payment_collection_behavior
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
    return $this->request(SubscriptionService::OBJECT_NAME, 'put', $this->buildPath('/client/subscriptions/v1/%s', $id), $params, $opts);
  }

  /**
   * Initiates resumption of a paused subscription, optionally resetting the billing cycle anchor 
   * and creating prorations. If a resumption transaction is generated, it must be paid or 
   * marked uncollectible before the subscription will be unpaused. If payment succeeds the 
   * subscription will become active, and if payment fails the subscription will be past_due. 
   * The resumption invoice will void automatically if not paid by the expiration date. 
   * 
   * If the subscription is in a trial phase and you set the billing_cycle_anchor to now the 
   * subscription status remains Paused till the new payment transaction is authorized.
   * 
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\BasePlanResponse
   */
  public function resume($id, $params = null, $opts = null)
  {
    return $this->request(SubscriptionService::OBJECT_NAME, 'put', $this->buildPath('/client/subscriptions/v1/%s/resumption', $id), $params, $opts);
  }

  /**
   * Cancels a customer’s subscription immediately. The customer will not be charged again for the subscription.
   * 
   * Note, however, that any pending transaction items that have been created will still be charged for at the end of the period,
   * By default, upon subscription cancellation, Safepay will stop automatic collection of all finalized payments for the customer. 
   * This is intended to prevent unexpected payment attempts after the customer has canceled a subscription.
   * 
   * Once cancelled, subscriptions cannot be resumed and a new subscription will have to be created. 
   * If you would like to temporarily pause payment collection for a subscription, you should Update Subscription
   * and pass in the appropriate pause_collection_behavior parameter. 
   * 
   * Paused subscriptions can be resumed at any time but cancelled subscriptions are terminal.
   * 
   * Additionally, only subscriptions that are in the following states can be cancelled
   * - TRAILING
   * - INCOMPLETE
   * - ACTIVE
   * 
   * @param string $id
   * @param null|array $params
   * @param null|array|\Safepay\Util\RequestOptions $opts
   *
   * @throws \Safepay\Exception\ApiErrorException if the request fails
   *
   * @return \Safepay\BasePlanResponse
   */
  public function cancel($id, $params = null, $opts = null)
  {
    return $this->request(SubscriptionService::OBJECT_NAME, 'post', $this->buildPath('/client/subscriptions/v1/%s/cancel', $id), $params, $opts);
  }
}
