<?php

/**
 * Users management example
 * ------------------------------------
 */

require_once(__DIR__ . '/../../../vendor/autoload.php');

/* 
  Instantiate the Safepay PHP SDK with a Base URL and empty API Key.
  Options for Base URL are:
  1. 'https://dev.api.getsafepay.com' (development environment for beta features)
  2. 'https://sandbox.api.getsafepay.com' (sandbox environment for stable features)
  3. 'https://api.getsafepay.com' (production/live environment)
*/

$email = 'test' . time() . '@getsafepay.com';
$phone = '+4917320782' . rand(10,99);
$password = "abc12345";

$safepay = new \Safepay\SafepayClient([
  'auth_type' => null,  
  'api_key' => null,
  'api_base' => 'https://sandbox.api.getsafepay.com'
]);

header('Content-Type: text/plain');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

try {
  $register = $safepay->user->register([
    "first_name" => "Thomas",
    "last_name" => "Smith",
    "email" => $email,
    "phone"=> $phone,
    "password" => $password
  ]);
} catch (Exception $e) {
  print_r($e);
}

$login = $safepay->auth->login([
  "type" => "password",
  "email" => $email,
  "password" => $password
]);

$safepay = new \Safepay\SafepayClient([
  'auth_type' => 'jwt',  
  'api_key' => $login->session,
  'api_base' => 'https://sandbox.api.getsafepay.com'
]);

$new_password = "abc123456";

$changePassword = $safepay->user->changePassword([
  "old_password" => $password,
  "new_password" => $new_password,
  "confirm_new_password" => $new_password,
  "is_signout_of_all_session" => false
]);
