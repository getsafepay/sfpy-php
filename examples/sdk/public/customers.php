<?php

/**
 * Customers and Customer Payment Methods
 * ------------------------------------
 * 
 * Customers and their wallets (payment methods) are managed by merchants
 * and merchants can initiate transactions on their customers' behalf.
 * 
 * This example shows how you can communicate with the Customers service
 * and access its CRUD methods.
 * 
 * It also shows CRUD methods for performing actions on a Customer's
 * payment methods.
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
  If your app has user accounts, we recommend creating a Safepay Customer and 
  linking the Safepay Customer `token` with your own user.
  
  To create a Safepay Customer call the `create` function on the Customer
  service and pass in the required parameters. To see the full list of
  parameters required while creating a customer, please consult our API
  documentation available here:
  https://apidocs.getsafepay.com/#67d144ef-fad9-43ea-8595-6f710f203305
*/
try {
  $customer = $safepay->customer->create([
    // Required
    "first_name" => "Hassan",
    "last_name" => "Zaidi",
    "email" => "hzaidi@getsafepay.com",
    "phone_number" => "+923331234567",
    "country" => "PK",

    // Optional
    "is_guest" => false
  ]);

  echo $customer->token;
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
  If you have already created a customer before and have associated 
  their `token` with your customer, you can perform the following 
  actions:

  1. Retrieve
  2. Update
  3. Delete

  Note that error handling has been ommitted for brevity.
*/

// Retrieve the customer using their token
$customer = $safepay->customer->retrieve($customer->token);

// Update the customer
$customer = $safepay->customer->update($customer->token, [
  "first_name" => "Ziyad",
  "last_name" => "Parekh"
]);

// Delete the customer once you no longer require it
$response = $safepay->customer->delete($customer->token);

/* 
  Customers can also have saved payment methods that can be used
  to make purchases. Saving a payment method to a customer's wallet
  allows them to make payments quickly without having to re-enter 
  their information each time.
  
  To enable your customers to create payment methods, please refer to our
  embedded checkout integration  guide:
  https://safepaydocs.netlify.app/integrations/checkout/embedded/elements/save-card
  
  You may also follow the example inside `customers-instrument.php` to
  create a Safepay Checkout session for a customer that lets them save
  their card to their wallet.
  
  After a customer has saved payment methods to their wallet, you may
  use the SDK to perform certain actions with them Note that error
  handling has been ommitted for brevity. You may:

  1. Retrieve a single payment method
  2. List all payment methods
  3. Delete a payment method

  To read more about Payment Methods, please refer to our API documentation 
  here: https://apidocs.getsafepay.com/#32303749-a3cc-446d-8866-c17915d11f6b
*/

// You may want to replace the token here with yours
$someCustomerToken = "cus_8156b632-bc14-4d18-806d-53fd78b5cbb1";

// List all payment methods
$paymentMethods = $safepay->payment_method->all($someCustomerToken);

// Once you have a collection of paymentMethods, you
// can also index into to find a specific payment method
if ($paymentMethods->count() > 0) {
  $paymentMethod = $paymentMethods->wallet[0];
}

// Otherwise, if you have the `token` of a payment method, you can use the `retrieve` method to fetch it
$paymentMethod = $safepay->payment_method->retrieve($someCustomerToken, $paymentMethod->token);

// If your customer wants to delete their saved payment method,
// you can call the `delete` method to remove it from their wallet
$response = $safepay->payment_method->delete($customer->token, $paymentMethod->token);