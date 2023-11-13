<?php


namespace Safepay;

abstract class WebhookSignature
{

  /**
   * Verifies the signature header sent by Safepay. Throws an
   * Exception\SignatureVerificationException exception if the verification fails for
   * any reason.
   *
   * @param string $payload the payload sent by Safepay
   * @param string $signature the contents of the signature signature sent by
   *  Safepay
   * @param string $secret secret used to generate the signature
   *
   * @throws Exception\SignatureVerificationException if the verification fails
   *
   * @return bool
   */
  public static function verifyHeader($payload, $signature, $secret)
  {

    if (empty($signature)) {
      throw Exception\SignatureVerificationException::factory(
        'No signature found with expected scheme',
        $payload,
        $signature
      );
    }

    // Check if expected signature is found in list of signatures from
    // header
    $signedPayload = $payload;
    $expectedSignature = self::computeSignature($signedPayload, $secret);
    $signatureFound = false;
    if (Util\Util::secureCompare($expectedSignature, $signature)) {
      $signatureFound = true;
    }

    if (!$signatureFound) {
      throw Exception\SignatureVerificationException::factory(
        'No signatures found matching the expected signature for payload',
        $payload,
        $signature
      );
    }

    return true;
  }

  /**
   * Computes the signature for a given payload and secret.
   *
   * The current scheme used by Safepay ("X-SFPY-SIGNATURE") is HMAC/SHA-512.
   *
   * @param string $payload the payload to sign
   * @param string $secret the secret used to generate the signature
   *
   * @return string the signature as a string
   */
  private static function computeSignature($payload, $secret)
  {
    return \hash_hmac('sha512', $payload, $secret);
  }
}
