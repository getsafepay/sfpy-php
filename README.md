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

If you do not wish to use Composer, you can download the [latest release](https://github.com/getsafepay/safepay-php/releases). Then, to use the bindings, include the `init.php` file.

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
