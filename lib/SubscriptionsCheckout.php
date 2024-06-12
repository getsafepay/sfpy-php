<?php


namespace Safepay;

abstract class SubscriptionsCheckout
{
  const DEV_BASE_URL = "https://dev.api.getsafepay.com";
  const SANDBOX_BASE_URL = "https://sandbox.api.getsafepay.com";
  const PROD_BASE_URL = "https://getsafepay.com";

  const REQUIRED_OPTIONS = ['environment', 'plan_id', 'cancel_url', 'redirect_url', 'tbt'];

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
   * the provided `Plan ID` allowing any customer to subscribe to the provided
   * plan. 
   * Exception\UnexpectedValueException if the options are not valid
   *
   * @param array $options the options to pass to the Checkout URL
   * Supported parameters that are required are
   * 1. `plan_id`: The Plan ID of the plan that you want the customer to subscribe to
   * 2. `environment`: One of 'development', 'sandbox' or 'production'
   * 3. `cancel_url`: The fully qualified URL to return the customer back to if they decide to cancel
   *     the flow
   * 4. `redirect_url`: The fully qualified URL to return the customer back to afer they have subscribed. 
   *     This is usually a URL that returns the customer to a success page
   * 
   * @throws Exception\UnexpectedValueException if the payload is not valid JSON,
   *
   * @return string the Checkout Subscription URL
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
      "plan_id" => $options["plan_id"],
      "auth_token" => $options["tbt"],
      "cancel_url" => $options["cancel_url"],
      "redirect_url" => $options["redirect_url"],
    );

    $encoded = \http_build_query($params);
    return $baseURL . "/subscribe?" . $encoded;
  }
}
