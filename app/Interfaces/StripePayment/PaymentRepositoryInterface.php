<?php

namespace App\Interfaces\StripePayment;

interface PaymentRepositoryInterface
{
    public function showForm();

    public function payment($request);
}
