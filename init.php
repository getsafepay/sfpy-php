<?php

// Safepay singleton
require __DIR__ . '/lib/Safepay.php';

// Utilities
require __DIR__ . '/lib/Util/CaseInsensitiveArray.php';
require __DIR__ . '/lib/Util/RequestOptions.php';
require __DIR__ . '/lib/Util/Set.php';
require __DIR__ . '/lib/Util/Util.php';
require __DIR__ . '/lib/Util/ObjectTypes.php';

// HttpClient
require __DIR__ . '/lib/HttpClient/ClientInterface.php';
require __DIR__ . '/lib/HttpClient/CurlClient.php';

// Exceptions
require __DIR__ . '/lib/Exception/ExceptionInterface.php';
require __DIR__ . '/lib/Exception/ApiErrorException.php';
require __DIR__ . '/lib/Exception/ApiConnectionException.php';
require __DIR__ . '/lib/Exception/AuthenticationException.php';
require __DIR__ . '/lib/Exception/BadMethodCallException.php';
require __DIR__ . '/lib/Exception/InvalidArgumentException.php';
require __DIR__ . '/lib/Exception/InvalidRequestException.php';
require __DIR__ . '/lib/Exception/UnexpectedValueException.php';
require __DIR__ . '/lib/Exception/UnknownApiErrorException.php';

// API operations
require __DIR__ . '/lib/ApiOperations/Create.php';
require __DIR__ . '/lib/ApiOperations/Delete.php';
require __DIR__ . '/lib/ApiOperations/Request.php';
require __DIR__ . '/lib/ApiOperations/Retrieve.php';
require __DIR__ . '/lib/ApiOperations/Update.php';

// Plumbing
require __DIR__ . '/lib/ApiResponse.php';
require __DIR__ . '/lib/SafepayObject.php';
require __DIR__ . '/lib/ApiRequestor.php';
require __DIR__ . '/lib/ApiResource.php';
require __DIR__ . '/lib/Service/AbstractService.php';
require __DIR__ . '/lib/Service/AbstractServiceFactory.php';

require __DIR__ . '/lib/ErrorObject.php';

// SafepayClient
require __DIR__ . '/lib/BaseSafepayClientInterface.php';
require __DIR__ . '/lib/SafepayClientInterface.php';
require __DIR__ . '/lib/BaseSafepayClient.php';
require __DIR__ . '/lib/SafepayClient.php';

require __DIR__ . '/lib/Collection.php';
require __DIR__ . '/lib/Order.php';
require __DIR__ . '/lib/Tracker.php';
require __DIR__ . '/lib/Customer.php';
require __DIR__ . '/lib/PaymentMethod.php';
require __DIR__ . '/lib/Passport.php';
require __DIR__ . '/lib/Address.php';
require __DIR__ . '/lib/Plan.php';

require __DIR__ . '/lib/Service/CoreServiceFactory.php';
require __DIR__ . '/lib/Service/OrderService.php';
require __DIR__ . '/lib/Service/CustomerService.php';
require __DIR__ . '/lib/Service/PaymentMethodService.php';
require __DIR__ . '/lib/Service/PassportService.php';
require __DIR__ . '/lib/Service/AddressService.php';
require __DIR__ . '/lib/Service/PlanService.php';

// Webhooks
require __DIR__ . '/lib/Webhook.php';
require __DIR__ . '/lib/WebhookSignature.php';

// Checkout
require __DIR__ . '/lib/Checkout.php';
require __DIR__ . '/lib/Subscribe.php';
