<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_id',
        'gateway',
        'phonepe_transaction_id',
        'gateway_email',
        'gateway_payer_id',
        'amount',
        'currency',
        'status',
    ];
}
