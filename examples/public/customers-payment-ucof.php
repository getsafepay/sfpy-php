<?php

/**
 * Customers: Unscheduled Card-on-file Payments
 * --------------------------------------------
 * 
 * Customers and their wallets (payment methods) are managed by merchants
 * and merchants can initiate transactions on their customers' behalf.
 * 
 * This example shows how you can perform a merchant initiate charge on your
 * customer's behalf using a payment method from their wallet.
 */

require_once '../vendor/autoload.php';
require_once '../secrets.php';

/* 
  Instantiate the Safepay PHP SDK by passing in your 
  API Secret Key and the appropriate base URL to target
  Options for Base URL are:
  1. 'https://dev.api.getsafepay.com' (development environment for beta features)
  2. 'https://sandbox.api.getsafepay.com' (sandbox environment for stable features)
  3. 'https://api.getsafepay.com' (production/live environment)
*/
$safepay = new \Safepay\SafepayClient([
  'api_key' => $safepaySecretKey,
  'api_base' => 'https://sandbox.api.getsafepay.com'
]);


header('Content-Type: application/json');

/* 
  A payment involves a customer, their payment method and the
  amount (in a supported currency) that they will pay. To
  initiate a payment, you must first `setup` a payment session.
 
  To create a payment session, call the `setup` function on the
  Order service and pass in the required parameters. To see the
  full list of parameters to initialize different payment modes
  please consult our API documentation available here:
  https://apidocs.getsafepay.com/#7fcfa13f-bf41-4b86-80c6-5ca178d80baa
*/

// Set your customer and their payment method tokens here
$customerToken = "cus_8156b632-bc14-4d18-806d-53fd78b5cbb1";
$paymentMethodToken = "pm_6ba6db88-09fb-4522-ac59-f91fe2abe5e0";

try {
  $session = $safepay->order->setup([
    "user" => $customerToken,
    "merchant_api_key" => $safepayAPIKey,
    "intent" => "CYBERSOURCE",
    "mode" => "unscheduled_cof",            // This specifies that the transaction is initated by the merchant
    "currency" => "PKR",
    "amount" => 600000                      // In the lowest denomination e.g. paisas
  ]);

  echo $session->tracker->token;
} catch (\Safepay\Exception\InvalidRequestException $e) {
  echo 'Status is:' . $e->getHttpStatus() . '\n';
  echo 'Message is:' . $e->getError() . '\n';
} catch (\Safepay\Exception\AuthenticationException $e) {
  echo 'Status is:' . $e->getHttpStatus() . '\n';
  echo 'Message is:' . $e->getError() . '\n';
} catch (\Safepay\Exception\UnknownApiErrorException $e) {
  echo 'Status is:' . $e->getHttpStatus() . '\n';
  echo 'Message is:' . $e->getError() . '\n';
} catch (Exception $e) {
  // Something else happened, completely unrelated to Safepay
  print_r($e);
}

/* 
  You can optionally associate a third-party order ID with your
  payment session for easy reconciliation. This can be done prior
  to the payment or after it. Once again, error handling has been
  ommitted for brevity
*/
$session = $safepay->order->metadata($session->tracker->token, [
  "data" => [
    "source" => "your-app",
    "order_id" => "Order-123456"
  ]
]);

/* 
  Once you're ready to charge the customer, call the `charge` method
  on the Order service to capture the transaction. To charge the saved 
  payment method, you must pass in the token of the saved payment method
  to be used for the payment.
*/
$tracker = $safepay->order->charge($session->tracker->token, [
  "payload" => [
    "payment_method" => [
      "tokenized_card" => [
        "token" => $paymentMethodToken
      ],
    ],
  ]
]);

/*
  For cancelling payments, you may perform refunds or voids.
*/

/* 
  Any transaction less than 24 hours old can be Voided to avoid settlement.
  To Void a transaction, you can call the `void` method on the order service
*/
// $session = $safepay->order->void($session->tracker->token);

/* 
  However, if the transaction has already been settled, you must call the 
  `refund` method to initiate the refund process.
*/
// $session = $safepay->order->refund($session->tracker->token);
