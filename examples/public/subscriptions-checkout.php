<?php

/**
 * Subscriptions: Subscribe to a plan
 * ------------------------------------
 * 
 * Safepay supports native subscriptions by allowing a customer to subscribe
 * to a plan. In order for this to happen, your system will need to generate
 * a secure URL to which the customer must be redirected to in order to complete
 * the subscription.
 * 
 * Safepay has created a secure, hosted page to collect sensitive information
 * from your customer and allow them to subscribe to your plan. The code below
 * shows how you can generate a Subscriptions Checkout URL through which your
 * customer can subscribe to your plan.
 */

require_once '../vendor/autoload.php';
require_once '../secrets.php';

$safepay = new \Safepay\SafepayClient([
  'api_key' => $safepaySecretKey,
  'api_base' => 'https://sandbox.api.getsafepay.com'
]);

header('Content-Type: application/json');

try {
  // Change this ID to reflect the ID of the Plan you have created
  // either through the merchant dashboard or through the API.
  $plan_id = "plan_1d94acdb-a9cd-4cc2-972b-0e2264b1388c";

  // You need to create a Time Based Authentication token
  $tbt = $safepay->passport->create();

  // To ease reconciliation, you may associate a reference
  // that you generate in your system. This will be returned
  // in webhooks received when the subscription is created.
  $reference = "0950fa13-1a28-4529-80bf-89f6f4e830a5";

  // Finally, you can create the Subscribe URL
  $subscribeURL = \Safepay\SubscriptionsCheckout::constructURL([
      "environment" => "sandbox",
      "plan_id" => $plan_id,
      "tbt" => $tbt->token,
      // "reference" => $reference,
      "cancel_url" => "https://mywebiste.com/subscribe/cancel",
      "redirect_url" => "https://mywebiste.com/subscribe/success",
  ]);

  echo($subscribeURL);
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
}