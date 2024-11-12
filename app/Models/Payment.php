<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'stripe_charge_id',
        'amount',
        'payment_method_id',
        'status',
        'email',
    ];
}
