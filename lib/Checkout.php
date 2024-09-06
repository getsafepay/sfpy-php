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
      if (isset($options["is_implicit"]) && $options["is_implicit"] !== "true") {
        $msg = "to use is_implicit, the value must be a string and set to true";
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
   * 3. `tbt`: The Time Based Authentication token
   * 
   * Optional parameters are
   * 1. `source`: Either 'mobile' if you are rendering in a mobile webview or 'custom'
   * 2. `address`: The Address ID if you wish to prefil the customer's billing address.
   * 3. `user_id`: The Customer ID that represents the customer making the purchase
   * 4. `is_implicit`: To make card saving mandatory. Instead of a checkbox to confirm 
   * whether to save the card or not, the customer is shown a disclaimer saying that their
   * card will be saved for all future charges. This must be set to a string with value "true" 
   * to be used.
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
      "environment" => $env,
      "tracker" => $options["tracker"],
      "source" => $options["source"] ?? "custom",
      "tbt" => $options["tbt"],
    );

    // Set customer and address if available
    if (isset($options["customer"])) {
      $params["user_id"] = $options["customer"];
    }
    if (isset($options["address"])) {
      $params["address"] = $options["address"];
    }

    // Set redirection URLs if available
    if (isset($options["redirect_url"])) {
      $params["redirect_url"] = $options["redirect_url"];
    }
    if (isset($options["cancel_url"])) {
      $params["cancel_url"] = $options["cancel_url"];
    }

    if (isset($options["is_implicit"])) {
      $params["is_implicit"] = $options["is_implicit"];
    }

    $encoded = \http_build_query($params);
    return $baseURL . "/embedded?" . $encoded;
  }
}
