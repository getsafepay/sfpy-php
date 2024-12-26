<?php

/**
 * Subscriptions: Management
 * ------------------------------------
 *
 * Use Safepay Subscriptions APIs to manage existing recurring payment subscriptions.
 * This example shows how a subscription may be retrieved and canceled.
 *
 */

require_once "../vendor/autoload.php";
require_once "../secrets.php";

$safepay = new \Safepay\SafepayClient([
  "api_key" => $safepaySecretKey,
  "api_base" => "https://sandbox.api.getsafepay.com",
]);

header("Content-Type: application/json");

try {
  // Identifier for an active subscription in your store
  $sub = "sub_b939ecff-84e1-4fce-bdf7-6502bb55b5ba";

  // Retrieve an existing subscription using its identifier/token
  $subscription = $safepay->subscription->retrieve($sub);

  // Cancel the subscription
  $subscription = $safepay->subscription->cancel($sub);
  print_r($subscription);
} catch (\Safepay\Exception\InvalidRequestException $e) {
  echo "Status is:" . $e->getHttpStatus() . '\n';
  echo "Message is:" . $e->getError() . '\n';
} catch (\Safepay\Exception\AuthenticationException $e) {
  echo "Status is:" . $e->getHttpStatus() . '\n';
  echo "Message is:" . $e->getError() . '\n';
} catch (\Safepay\Exception\UnknownApiErrorException $e) {
  echo "Status is:" . $e->getHttpStatus() . '\n';
  echo "Message is:" . $e->getError() . '\n';
} catch (Exception $e) {
  // Something else happened, completely unrelated to Safepay
  print_r($e);
}
