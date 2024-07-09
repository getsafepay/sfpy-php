<?php

/**
 * Save a payment method for a Customer
 * ------------------------------------
 * 
 * Customers and their wallets (payment methods) are managed by merchants
 * and merchants can initiate transactions on their customers' behalf.
 * 
 * This example shows how you can create a new Customer and create a
 * checkout session so that the user may save their card information and
 * add the new payment method to their wallet.
 * 
 * Note that while transactions can be initiated by merchants, the customer
 * must save their payment method details by going through the Safepay Checkout
 * journey. For cards, the customer will also be prompted to attempt the 3DS
 * challenge sent by their issuing bank.
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
  // You need to generate a tracker with mode 'instrument'
  // to tell Safepay that you wish to set up a tracker to
  // tokenize a customer's card
  $session = $safepay->order->setup([
    "merchant_api_key" => $safepayAPIKey,
    "intent" => "CYBERSOURCE",
    "mode" => "instrument",
    "currency" => "PKR"
  ]);

  // You need to either create a customer or retreive the customer
  // from your backend so you have access to the customer ID
  $customer = $safepay->customer->create([
    "first_name" => "Hassan",
    "last_name" => "Zaidi",
    "email" => "hzaidi@getsafepay.com",
    "phone_number" => "+923331234567",
    "country" => "PK"
  ]);

  // You can optionally create an address object if you have
  // access to the customer's billing details
  $address = $safepay->address->create([
    // Required
    "street1" => "3A-2 7th South Street",
    "city" => "Karachi",
    "country" => "PK",
    
    // Optional
    "postal_code" => "75500",
    "state" => "Sindh"
  ]);

  // You need to create a Time Based Authentication token for the checkout session
  $tbt = $safepay->passport->create();

  // Finally, you can create the Checkout URL
  $checkoutURL = \Safepay\Checkout::constructURL([
    "environment" => "sandbox", // one of "development", "sandbox" or "production"
    "tracker" => $session->tracker->token,
    "customer" => $customer->token,
    "tbt" => $tbt->token,
    //"address" => $address->token,
    "source" => "mobile" // important for rendering in a WebView
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
