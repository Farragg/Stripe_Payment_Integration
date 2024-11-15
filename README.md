# <div align="center">Hey 👋, I'm Farrag!</div>

----

## <div align="center"> Connect with me

<div align="center">
 <a href="https://github.com/Farragg" target="_blank">
<img src=https://img.shields.io/badge/github-%2324292e.svg?&style=for-the-badge&logo=github&logoColor=white alt=github style="margin-bottom: 5px;" />
<a href="https://linkedin.com/in/farrag-mohamed" target="_blank">
<img src=https://img.shields.io/badge/linkedin-%231E77B5.svg?&style=for-the-badge&logo=linkedin&logoColor=white alt=linkedin style="margin-bottom: 5px;" />
</a>
</a>  
</div>

## Welcome! Glad to see you here


## Technical Task

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Approach and Thought Process](#approach-and-thought-process)
- [Step-by-Step Integration Guide](#step-by-Step-integration-guide)


## Requirements
***PHP 7.3 or higher**
***Laravel 8 or higher***
***Stripe account***

## Installation

Follow these steps to get your development environment up and running.

### 1. Clone the Repository

Clone the project repository to your local machine:

```bash
git clone https://github.com/Farragg/Stripe_Payment_Integration.git
cd Stripe_Payment_Integration
```
### 2. Install Dependencies

Install all the required dependencies using Composer and NPM:

```bash
composer install
npm install
```

### 3. Set Up Environment Variables

Create a .env file by copying the example file:

```bash
cp .env.example .env
```

### 4.Generate the Application Key

Generate the Laravel application key:

```bash
php artisan key:generate
```

### 5. Run Migrations

Migrate the database to set up the required tables:
```bash
php artisan migrate
```
### 6. Run the Application

Start the Laravel development server:
```bash
php artisan serve
```
application should now be running at http://localhost:8000.

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).


## Approach and Thought Process

The goal is to integrate Stripe with the Laravel application to handle secure payments. The approach is broken down into several key steps:

#### 1. Stripe SDK Installation: Install the Stripe PHP SDK to interact with Stripe’s API for payments and other related functionalities. This allows us to securely create and process payments without storing sensitive card details directly on our server.

#### 2. Frontend Form: Using Stripe Elements, a secure and customizable UI component, i collect card details and pass the data directly to Stripe. This keeps the sensitive card information out of our server, complying with PCI-DSS requirements.

#### 3. Backend Controller Logic: The controller will handle the payment request, create a PaymentIntent, and return the client secret to the frontend. The frontend will then use the client secret to confirm the payment. The backend will also handle webhook events to update the order status based on the payment outcome.

#### 4. Repository Design Pattern: The Repository pattern was implemented to decouple the application’s data access layer from the business logic. By using repositories, we can easily manage data access logic and maintain a cleaner architecture, making future updates easier and more manageable.

#### 5. SOLID Principles: The project was developed in alignment with SOLID principles.

#### 6. Security Considerations: Secure payment methods are implemented, such as using HTTPS for communication and verifying webhook signatures to ensure that Stripe is sending the event.


## Step-by-Step Integration Guide

### 1. Install Stripe PHP SDK
First, you need to install the Stripe PHP SDK. Run the following command:
```bash
php artisan migrate
```

### 2. Create a Stripe Account

Once you're logged in, navigate to Developers > API Keys. You'll need your Publishable Key and Secret Key for integration.

### 3. Add Stripe Keys to .env
Add the following Stripe keys to your .env file. Replace your_stripe_publishable_key and your_stripe_secret_key with the actual keys from your Stripe account:

```bash
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_signing_secret
```

### 4. Set Up Payment Form in Blade View

Create a Blade view where the user can enter their payment information. This form uses Stripe Elements to securely collect card details.

```bash
<form id="payment-form" action="{{ route('payment.process') }}" method="POST">
    @csrf
    <label for="amount">Amount (USD):</label>
    <input type="number" id="amount" name="amount" required>

    <div id="card-element">
        <!-- A Stripe Element will be inserted here. -->
    </div>

    <button type="submit" id="submit">Pay</button>
</form>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe("{{ env('STRIPE_KEY') }}");
    var elements = stripe.elements();
    var card = elements.create("card");
    card.mount("#card-element");

    var form = document.getElementById("payment-form");
    form.addEventListener("submit", async function (event) {
        event.preventDefault();
        
        const { token, error } = await stripe.createToken(card);

        if (error) {
            console.error(error.message);
        } else {
            const formData = new FormData(form);
            formData.append("payment_method_id", token.id);

            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
            });

            const result = await response.json();

            if (result.error) {
                alert(result.error);
            } else {
                alert("Payment successful!");
            }
        }
    });
</script>

```

### 5.Create PaymentController
In your PaymentController, handle the payment submission:

```bash
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Create a PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100,  // Amount in cents
            'currency' => 'usd',
            'payment_method' => $request->payment_method_id,
            'confirm' => true,
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret
        ]);
    }
}
```

### 6. Set Up Stripe Webhooks
Set up a webhook to handle events such as payment success or failure. In the Stripe Dashboard, navigate to Developers > Webhooks.

- ***Add your webhook URL***: https://yourdomain.com/stripe/webhook
- ***Choose events like payment_intent.succeeded, payment_intent.payment_failed***

### 7. Create WebhookController
Create a ***WebhookController*** to handle Stripe events:

```bash
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        
        try {
            $event = Webhook::constructEvent($payload, $sig_header, env('STRIPE_WEBHOOK_SECRET'));
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                // Update order status in the database
                $paymentIntent = $event->data->object;
                // Your logic for successful payment
                break;
            case 'payment_intent.payment_failed':
                // Handle failed payment
                break;
        }

        return response('Webhook received');
    }
}

```

### 8. Add Routes
In your web.php, add the route for the webhook:

```bash
Route::post('/stripe/webhook', [WebhookController::class, 'handle']);
```

### 9. Update Order Status
Inside your webhook handler (e.g., payment_intent.succeeded), update the order status in the database based on the event.

```bash
$order = Order::find($paymentIntent->metadata->order_id);
$order->status = 'paid';
$order->save();
```

### 10. Test Payments and Webhooks
***Test payments using the Stripe Test Cards.***
***Use Stripe's Webhook testing tools to simulate different events.***
