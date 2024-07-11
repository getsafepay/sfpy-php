<?php

/**
 * Customers: Card-on-file Payments
 * --------------------------------------------
 * 
 * Customers and their wallets (payment methods) are managed by merchants
 * and merchants can initiate transactions on their customers' behalf.
 * 
 * This example shows how you can have your customer perform a payment
 * using a saved payment method from their wallet.
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
    "mode" => "payment",
    "entry_mode" => "tms",                  // This specifies that the payment will be made with a saved payment method
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
  ommitted for brevity.
*/
$session = $safepay->order->metadata($session->tracker->token, [
  "data" => [
    "source" => "your-app",
    "order_id" => "Order-123456"
  ]
]);

/*
  Once you're ready to charge the customer, call the `charge` method
  on the Order service to perform payer authentication setup. At this
  stage you need to provide the payment method token for the card that
  the customer has selected.
  
  This action is documented here:
  https://apidocs.getsafepay.com/#43a20d43-75ea-4868-a912-dfd6c0a636dd
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
  At this point you must perform device data collection and obtain
  the device fingerprint session ID. If this ID is not passed in
  the next step, the 3DS challenge will not be triggered.

  To perform device data collection, you may follow this guide:
  https://developer.cybersource.com/docs/cybs/en-us/payer-authentication/developer/all/so/payer-auth/pa2-ccdc-ddc-intro.html
*/

/*
  Call the `charge` method again to perform payer authentication
  enrollment. You must provide the `device_fingerprint_session_id`
  and the success and failure URLs as shown below.

  The success and failure URLs are for where this action would
  redirect the response to in the event 3DS is successful or fails.
  If you receive an event on the `failure_url`, you may display an
  error message to the user (where the specific error is returned
  as a query parameter). If you receive an event on the `success_url`,
  you may continue the flow as detailed below.

  Note that the `success_url` and `failure_url` shown below are
  examples from Safepay Checkout.

  This action is documented here:
  https://apidocs.getsafepay.com/#98dbaee4-2946-4621-ba8f-06f182e6cb85
*/ 
$tracker = $safepay->order->charge($session->tracker->token, [
  "payload" => [
    "authorization" => [
      "do_capture" => false
    ],
    "authentication_setup" => [
      "success_url" => "https://sandbox.api.getsafepay.com/checkout/external/success?env=local&beacon=track_dccdef16-008e-4fae-81cc-1b7d81bf6c44&xcomponent=1",
      "failure_url" => "https://sandbox.api.getsafepay.com/checkout/external/failure?env=local&beacon=track_dccdef16-008e-4fae-81cc-1b7d81bf6c44&xcomponent=1",
      "device_fingerprint_session_id" => "2f66ecd3-88f4-4754-aa65-3b82a18c4506"
    ]
  ]
]);

/*
  Now you can present the 3DS challenge to the customer.

  You may follow this guide:
  https://developer.cybersource.com/docs/cybs/en-us/payer-authentication/developer/all/so/payer-auth/pa2-ccdc-stepup-frame-intro.html
*/

/*
  If enrollment does not yield a step-up URL (because the card is
  not enrolled in 3DS or a frictionless 3DS is possible), you would
  call the `charge` method to perform authorization. Note that this
  also performs capture if authorization is successful.

  This action is documented here:
  https://apidocs.getsafepay.com/#6145b078-c3e7-4621-88de-373187bf6147
*/
$tracker = $safepay->order->charge($session->tracker->token, [
  "payload" => [
    "authorization" => [
      "do_capture" => false
    ]
  ]
]);

/*
  If the customer was successfully taken through the 3DS flow, you
  instead would call the `charge` method with an empty payload to
  capture the payment.
*/ 
$tracker = $safepay->order->charge($session->tracker->token, new stdClass());