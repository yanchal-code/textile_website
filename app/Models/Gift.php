<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'gift_card_value',
        'coupon_code',
        'buy_one_get_one',
    ];
}
