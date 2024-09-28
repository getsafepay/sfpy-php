# Consuming Safepay Webhooks

This project demonstrates how you can consume and process Safepay Webhooks in your application. It serves an API endpoint that serves as the webhook handler. The endpoint URL for this API may be configured in your Safepay dashboard to subscribe to various events e.g. when payments are made or when a customer has subscribed to a recurring plan. This example is built using [Laravel](https://laravel.com/) with version `11.x`.

## Running the example

Update dependencies using:

```
composer update
```

For running the server locally, use:

```
php artisan serve
```

This is useful for testing with example payloads.

## Deployment

To test webhooks originating from Safepay (production or otherwise), this server must be hosted on a URL that is accessible on the internet. You may need to configure your hosting or cloud provier's whitelists to allow requests from Safepay. Our relevant domains are `api.getsafepay.com` and `sandbox.api.getsafepay.com`.