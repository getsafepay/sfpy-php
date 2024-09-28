<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::post('/sfpy-webhook', function (Request $request) {
    // You can find your shared webhook secret in the Developer
    // section of the Safepay dashboard under the Endpoints tab
    $webhook_secret = '8c3f108cb9ba794799d9f7693eb9f59e6c52aad7249e4838c9da2ad8f962ea04';

    // Retrieve the signature from the request header
    if (! $request->hasHeader('X-SFPY-SIGNATURE')) {
        return response('Missing signature', 400);
    }
    $signature = $request->header('X-SFPY-SIGNATURE');

    // Get the payload
    $payload = json_encode($request->input(), JSON_UNESCAPED_SLASHES);
    
    // Verify the signature    
    $event = null;
    try {
        $event = \Safepay\Webhook::constructEvent($payload, $signature, $webhook_secret);
    } catch(\UnexpectedValueException $e) {
        Log::error($e);
        return response('error parsing payload', 400);
    } catch(\Safepay\Exception\SignatureVerificationException $e) {
        Log::error($e);
        return response('Error verifying webhook signature', 400);
    }

    // Handle the webhook event
    switch ($event->type) {
        case 'payment.succeeded':
            $payment = $event->data;

            // Your code goes here. You may want to mark this payment as complete in your database.

            break;
        case 'payment.failed':
            $payment = $event->data;

            // Your code goes here. You may want to mark this payment as failed in your database.

            break;
        
        // Handle other event types
        default:
            break;
    }

    return response('Ok', 200);
});
