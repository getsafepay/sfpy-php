<?php

/**
 * Create a Checkout session
 * ------------------------------------
 * 
 * This example shows how you can create a new Checkout session
 * to accept payments from your customers.
 * 
 * The code in this example may be triggered from the UI.
 */

/* 
  Instantiate the Safepay PHP SDK by passing in your 
  API Secret Key and the appropriate base URL to target
  Options for Base URL are:
  1. 'https://dev.api.getsafepay.com' (development environment for beta features)
  2. 'https://sandbox.api.getsafepay.com' (sandbox environment for stable features)
  3. 'https://api.getsafepay.com' (production/live environment)
*/

require_once '../vendor/autoload.php';
require_once '../secrets.php';

$safepay = new \Safepay\SafepayClient([
  'api_key' => $safepaySecretKey,
  'api_base' => 'https://sandbox.api.getsafepay.com'
]);


header('Content-Type: application/json');

try {
  // You need to generate a tracker with mode 'payment'
  // to tell Safepay that you wish to set up a tracker to
  // accept a payment from your customer.
  $session = $safepay->order->setup([
    "merchant_api_key" => $safepayAPIKey,
    "intent" => "CYBERSOURCE",
    "mode" => "payment",
    "currency" => "PKR",
    "amount" => 10000 // In minor units of the currency e.g. in paisas
  ]);

  // Optional. You may create an address object if you have
  // access to the customer's billing details. If not, the
  // customer will be prompted to enter their address details
  // on the checkout UI.
  $address = $safepay->address->create([
    // Required
    "street1" => "3A-2 7th South Street",
    "city" => "Karachi",
    "country" => "PK",
    
    // Optional
    "postal_code" => "75500",
    "state" => "Sindh"
  ]);

  // You need to create a Time Based Authentication token for the checkout session.
  $tbt = $safepay->passport->create();

  // Finally, you can create the Checkout URL
  $checkoutURL = \Safepay\Checkout::constructURL([
    "environment" => "sandbox", // one of "development", "sandbox" or "production"
    "tracker" => $session->tracker->token,
    "tbt" => $tbt->token,
    "customer" => "cus_nfsdknfjiasdnfasdf",
    "cancel_url" => "https://example.com",
    "redirect_url" => "https://www.google.com",
    //"address" => $address->token, // If you wish to save the customer from having to enter their address details
    "source" => "mobile" // Important for rendering in a mobile WebView but you may enter "custom" otherwise
  ]);
  echo ($checkoutURL);
  return $checkoutURL;
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