<?php

/**
 * Retrieve a Checkout Tracker
 * ------------------------------------
 *
 * This example shows how you can retrieve the status and
 * associated metadata of a Safepay checkout tracker after it has
 * been created. You typically use this to confirm the status of
 * a transaction and review any customer or order data.
 *
 * This is useful in post-payment flows, such as confirming
 * payment success, reconciling order data, or triggering
 * business logic after a transaction.
 */

/*
  Instantiate the Safepay PHP SDK by passing in your
  API Secret Key and the appropriate base URL to target.
  Options for Base URL are:
  1. 'https://dev.api.getsafepay.com' (development environment for beta features)
  2. 'https://sandbox.api.getsafepay.com' (sandbox environment for stable features)
  3. 'https://api.getsafepay.com' (production/live environment)
*/

require_once "../vendor/autoload.php";
require_once "../secrets.php";

$safepay = new \Safepay\SafepayClient([
    "api_key" => $safepaySecretKey,
    "api_base" => "https://sandbox.api.getsafepay.com",
]);

header("Content-Type: application/json");

// Replace this with your actual tracker token
$trackerToken = "track_7233bcf0-bf8b-44a9-adfa-6e54e13cc538";

try {
    // Retrieve tracker details using the provided token
    $tracker = $safepay->reporter->retrieve($trackerToken);

    // Output the tracker data (as JSON or object depending on your needs)
    echo json_encode($tracker);
} catch (\Safepay\Exception\InvalidRequestException $e) {
    echo "Status is: " . $e->getHttpStatus() . '\n';
    echo "Message is: " . $e->getError() . '\n';
} catch (\Safepay\Exception\AuthenticationException $e) {
    echo "Status is: " . $e->getHttpStatus() . '\n';
    echo "Message is: " . $e->getError() . '\n';
} catch (\Safepay\Exception\UnknownApiErrorException $e) {
    echo "Status is: " . $e->getHttpStatus() . '\n';
    echo "Message is: " . $e->getError() . '\n';
} catch (Exception $e) {
    // Something else happened, completely unrelated to Safepay
    print_r($e);
}
