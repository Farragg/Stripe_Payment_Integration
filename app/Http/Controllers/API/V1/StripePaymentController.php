<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StripePayment\PaymentRequest;
use App\Interfaces\StripePayment\PaymentRepositoryInterface;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    protected $payment;
    public function __construct(PaymentRepositoryInterface $payment)
    {
        $this->payment = $payment;
    }
    public function showForm()
    {
        return $this->payment->showForm();
    }

    public function payment(PaymentRequest $request)
    {
        return $this->payment->payment($request);
    }
}
