<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Get the Stripe webhook payload and signature
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            // Verify the webhook signature and parse the event
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid Payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid Signature'], 400);
        }

        // Process the event based on its type
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSuccess($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailure($event->data->object);
                break;
            default:
                Log::info("Unhandled event type: {$event->type}");
        }

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentSuccess($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id;
        $order = Order::find($orderId);

        if ($order) {
            $order->status = 'paid';
            $order->payment_id = $paymentIntent->id;
            $order->save();
            Log::info("Order #{$orderId} payment succeeded.");
        } else {
            Log::warning("Order #{$orderId} not found.");
        }
    }

    protected function handlePaymentFailure($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id;
        $order = Order::find($orderId);

        if ($order) {
            $order->status = 'failed';
            $order->save();
            Log::info("Order #{$orderId} payment failed.");
        } else {
            Log::warning("Order #{$orderId} not found.");
        }
    }

}
