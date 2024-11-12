<?php

namespace App\Repository\StripePayment;

use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\PaymentMethod;
use Stripe\Customer;
use App\Models\Payment;


class PaymentRepository implements \App\Interfaces\StripePayment\PaymentRepositoryInterface
{

    public function showForm()
    {
        return view('payment.create');
    }

    public function payment($request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {

            // Create a PaymentMethod using the provided token (card details)
            $paymentMethod = PaymentMethod::retrieve($request->payment_method_id);

            // You can create a customer if you need to store payment methods
            $customer = Customer::create([
                'payment_method' => $paymentMethod->id,
                'email' => $request->email,
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethod->id,
                ],
            ]);

            // Now create the charge (payment)
            $charge = Charge::create([
                'amount' => $request->amount * 100, // amount in cents
                'currency' => 'usd',
                'customer' => $customer->id,
                'payment_method' => $paymentMethod->id,
                'confirm' => true,
            ]);

            if ($charge->status == 'succeeded') {
                // Store the payment details in the database
                Payment::create([
                    'stripe_charge_id' => $charge->id,
                    'amount' => $charge->amount / 100, // convert amount back to dollars
                    'payment_method_id' => $paymentMethod->id,
                    'status' => $charge->status,
                    'email' => $request->email,
                ]);

                // Return a success message
                return redirect()->route('payment.success')->with('success', 'Payment Successful!');
            } else {
                return response()->json(['message' => 'Payment failed']);
            }
        }catch (\Exception $e){
            // Return a failure
            return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 500);
        }

    }
}
