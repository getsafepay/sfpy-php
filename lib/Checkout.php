<?php


namespace Safepay;

abstract class Checkout
{
  const DEV_BASE_URL = "https://dev.api.getsafepay.com";
  const SANDBOX_BASE_URL = "https://sandbox.api.getsafepay.com";
  const PROD_BASE_URL = "https://getsafepay.com";

  const REQUIRED_OPTIONS = ['environment', 'tracker', 'customer', 'tbt'];

  private static function validateOptions($options)
  {
    foreach (self::REQUIRED_OPTIONS as $key => $option) {
      if (!isset($options[$option])) {
        $msg = "{$option} is missing.";
        throw new Exception\UnexpectedValueException($msg);
      }
    }
  }

  /**
   * Returns a URL that when redirected to, will render Safepay Checkout for
   * the provided `tracker` and `customer`. 
   * Exception\UnexpectedValueException if the options are not valid
   *
   * @param array $options the options to pass to the Checkout URL
   * Supported parameters that are required are
   * 1. `tracker`: The Tracker ID that is generated
   * 2. `environment`: One of 'development', 'sandbox' or 'production'
   * 3. `customer`: The Customer ID that represents the customer making the purchase
   * 4. `tbt`: The Time Based Authentication token
   * 
   * Optional parameters are
   * 1. `source`: Either 'mobile' if you are rendering in a mobile webview or 'custom'
   * 2. `address`: The Address ID if you wish to prefil the customer's billing address.
   * @throws Exception\UnexpectedValueException if the payload is not valid JSON,
   *
   * @return string the Checkout URL
   */
  public static function constructURL($options)
  {
    self::validateOptions($options);

    $env = $options['environment'];
    $baseURL = "";
    if ("development" === $env) {
      $baseURL = self::DEV_BASE_URL;
    } else if ("sandbox" === $env) {
      $baseURL = self::SANDBOX_BASE_URL;
    } else {
      $baseURL = self::PROD_BASE_URL;
    }

    $params = array(
      "env" => $env,
      "tracker" => $options["tracker"],
      "source" => $options["source"] ?? "custom",
      "tbt" => $options["tbt"],
      "user_id" => $options["customer"]
    );

    if (isset($options["address"])) {
      $params["address"] = $options["address"];
    }

    $encoded = \http_build_query($params);
    return $baseURL . "/embedded?" . $encoded;
  }
}
