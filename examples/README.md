# Safepay PHP Examples

## Instructions

### Setup

Configure your Safepay API key and secret inside `secrets.php`. You may find these keys in the **Developer** section on Safepay dashboard.

### Running the server

- Run `composer update`
- Navigate to `./public`
- Run `php -S 127.0.0.1:8000`
- Navigate to [localhost:8000](http://localhost:8000) on your browser

## Examples: Customers

### Customers and Payment Methods

Communicate with the Customers service and try out its CRUD methods. This example also shows CRUD methods for performing actions on a Customer's payment methods.

Code: [customers.php](/public/customers.php)

### Save a payment method for a customer
 
Create a new `Customer` and create a checkout session so that the user may save their card information and add the new payment method to their wallet. Executing the example code yields a checkout URL.
 
Note that while transactions can be initiated by merchants, the customer must save their payment method details by going through the Safepay Checkout journey. For cards, the customer will also be prompted to attempt the 3DS challenge sent by their issuing bank.

The code in this example may be triggered from the UI to generate a checkout session URL.

Code: [customers-instrument.php](/public/customers-instrument.php)

### Charge a Customer on their saved payment method

Perform a charge on your customer's behalf using a payment method from their wallet.

Code: [customers-payment.php](/public/customers-payment.php)

## Examples: Subscriptions

### Subscribe to a plan with the Subscriptions Checkout

Safepay supports native subscriptions by allowing a customer to subscribe to a plan. In order for this to happen, your system will need to generate a secure URL to which the customer must be redirected to in order to complete the subscription.

Safepay has created a secure, hosted page to collect sensitive information from your customer and allow them to subscribe to your plan. The code below shows how you can generate a Subscriptions Checkout URL through which your customer can subscribe to your plan.

The code in this example may be triggered from the UI to generate a subscriptions checkout URL.

Code: [subscriptions-checkout.php](/public/subscriptions-checkout.php)
