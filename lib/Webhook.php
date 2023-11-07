<?php


namespace Safepay;

abstract class Webhook
{
  /**
   * Returns an Event instance using the provided JSON payload. Throws an
   * Exception\UnexpectedValueException if the payload is not valid JSON, and
   * an Exception\SignatureVerificationException if the signature
   * verification fails for any reason.
   *
   * @param string $payload the payload sent by Safepay
   * @param string $sigHeader the contents of the signature header sent by
   *  Safepay
   * @param string $secret secret used to generate the signature
   * @throws Exception\UnexpectedValueException if the payload is not valid JSON,
   * @throws Exception\SignatureVerificationException if the verification fails
   *
   * @return Event the Event instance
   */
  public static function constructEvent($payload, $sigHeader, $secret)
  {
    WebhookSignature::verifyHeader($payload, $sigHeader, $secret);

    $data = \json_decode($payload, true);
    $jsonError = \json_last_error();
    if (null === $data && \JSON_ERROR_NONE !== $jsonError) {
      $msg = "Invalid payload: {$payload} "
        . "(json_last_error() was {$jsonError})";

      throw new Exception\UnexpectedValueException($msg);
    }

    return Event::constructFrom($data);
  }
}
