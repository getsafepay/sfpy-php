# Safepay PHP bindings

The Safepay PHP library provides convenient access to the Safepay API from
applications written in the PHP language. It includes a pre-defined set of
classes for API resources that initialize themselves dynamically from API
responses which makes it compatible with a wide range of versions of the Safepay
API.

## Requirements

PHP 5.6.0 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require getsafepay/sfpy-php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once 'vendor/autoload.php';
```

## Manual Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/getsafepay/sfpy-php/releases). Then, to use the bindings, include the `init.php` file.

```php
require_once '/path/to/safepay-php/init.php';
```

## Dependencies

The bindings require the following extensions in order to work properly:

- [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
- [`json`](https://secure.php.net/manual/en/book.json.php)
- [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Getting Started

Simple usage looks like:

```php
$safepay = new \Safepay\SafepayClient('BQokikJOvBiI2HlWgH4olfQ2');
$tracker = $safepay->order->setup([
    "merchant_api_key" => "sec_8dcac601-4b70-442d-b198-03aadd28f12b",
    "intent" => "CYBERSOURCE",
    "mode" => "payment",
    "currency" => "PKR",
    "amount" => 600000 // in the lowest denomination
]);
echo $tracker;
```

## Sandbox

To use the SDK in a sandbox environment set the `base_url` to `https://sandbox.api.getsafepay.com`.

```php
$safepay = new \Safepay\SafepayClient([
  'api_key' => 'BQokikJOvBiI2HlWgH4olfQ2',
  'api_base' => 'https://sandbox.api.getsafepay.com'
]);
```

## Checkout URLs

When integrating Safepay, you may want to collect payment details from your customer to either securely tokenize their card or make a payment. For all of these different scenarios, your application will need to generate a URL to redirect your customers to so that they can complete the required steps on their side. This code snippet shows how you can generate a Checkout URL through which your customer can save their card on file.

```php

$safepay = new \Safepay\SafepayClient('BQokikJOvBiI2HlWgH4olfQ2');

try {
    // You need to generate a tracker with mode 'instrument'
    // to tell Safepay that you wish to set up a tracker to
    // tokenize a customer's card
    $session = $safepay->order->setup([
        "merchant_api_key" => "sec_8dcac601-4b70-442d-b198-03aadd28f12b",
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
        // required
        "street1" => "3A-2 7th South Street",
        "city" => "Karachi",
        "country" => "PK",
        // optional
        "postal_code" => "75500",
        "province" => "Sindh"
    ]);
    // You need to create a Time Based Authentication token
    $tbt = $safepay->passport->create();

    // Finally, you can create the Checkout URL
    $checkoutURL = \Safepay\Checkout::constructURL([
        "environment" => "production", // one of "development", "sandbox" or "production"
        "tracker" => $session->tracker->token,
        "user_id" => $customer->token,
        "tbt" => $tbt,
        "address" => $address->token,
        "source" => "mobile" // important for rendering in a WebView
    ]);
    echo($checkoutURL);
    return $checkoutURL;
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
}

```

## Subscription URLs

Safepay supports native subscriptions by allowing a customer to subscribe to a plan. In order for this to happen, your system will need to generate a secure URL to which the customer must be redirected to in order to complete the subscription. Safepay has created a secure, hosted page to collect sensitive information from your customer and allow them to subscribe to your plan. The code snippet shows how you can generate a Subscribe URL through which your customer can subscribe to your plan.

```php

$safepay = new \Safepay\SafepayClient('BQokikJOvBiI2HlWgH4olfQ2');

try {
    // Change this ID to reflect the ID of the Plan you have created
    // either through the merchant dashboard or through the API.
    $plan_id = "plan_d4869a78-0036-4d66-97bd-6afeb5282bcd"

    // You need to create a Time Based Authentication token
    $tbt = $safepay->passport->create();

    // You need to change that to your internally generated reference (e.g order_id)
    // to match subscription details upon receiving create subscription webhook.
    $reference = "0950fa13-1a28-4529-80bf-89f6f4e830a5"

    // Finally, you can create the Subscribe URL
    $subscribeURL = \Safepay\Subscribe::constructURL([
        "environment" => "production", // one of "development", "sandbox" or "production"
        "plan_id" => $plan_id,
        "tbt" => $tbt,
        "reference" => $reference
        "cancel_url" => "https://mywebiste.com/subscribe/cancel",
        "redirect_url" => "https://mywebiste.com/subscribe/success",
    ]);
    echo($subscribeURL);
    return $subscribeURL;
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
}

```

## Webhooks

When building Safepay integrations, you might want your applications to receive events as they occur in your Safepay accounts, so that your backend systems can execute actions accordingly.

To enable webhook events, you need to register webhook endpoints. After you register them, Safepay can push real-time event data to your application’s webhook endpoint when events happen in your Safepay account. Safepay uses HTTPS to send webhook events to your app as a JSON payload that includes an Event object.

Receiving webhook events are particularly useful for listening to asynchronous events such as when a customer’s bank confirms a payment, a customer disputes a charge, a recurring payment succeeds, or when collecting subscription payments.

Once you have set up an endpoint on your server to receive webhooks from Safepay, you will want to execute certain actions based on the type of the event received. Once done with your execution, you must send a `200` response code back to Safepay to acknowledge receipt of the webhook. If Safepay does not receive a `200` response code, the webhook will be fired again.

This code snippet is a webhook function configured to check that the event type was received, to handle the event, and return a 200 response.

```php
$payload = @file_get_contents('php://input');
$event = null;

try {
    $event = \Safepay\Event::constructFrom(
        json_decode($payload, true)
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'payment.succeeded':
        $payment = $event->data;
        break;
    case 'payment.failed':
        $payment = $event->data;
        break;
    // ... handle other event types
    default:
        echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);
```

## Securing Webhooks

> **Note**
> Safepay requires the raw body of the request to perform signature verification. If you’re using a framework, make sure it doesn’t manipulate the raw body. Any manipulation to the raw body of the request causes the verification to fail.

```php
// Set your secret key. Remember to switch to your live secret key in production.
// See your keys here: https://getsafepay.com/dashboard/developers
\Safepay\Safepay::setApiKey('sec_4eC39HqLyjWDarjtT1zdp7dc');

// You can ind your endpoint's secret in your webhook settings in the Developer Dashboard
$webhook_secret = '234hjkasd....';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['X-SFPY-SIGNATURE'];
$event = null;

try {
    $event = \Safepay\Webhook::constructEvent(
        $payload, $sig_header, $webhook_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
  http_response_code(400);
  echo json_encode(['Error parsing payload: ' => $e->getMessage()]);
  exit();
} catch(\Safepay\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    echo json_encode(['Error verifying webhook signature: ' => $e->getMessage()]);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'payment.succeeded':
        $payment = $event->data;
        break;
    case 'payment.failed':
        $payment = $event->data;
        break;
    // ... handle other event types
    default:
        echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);

```
