<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['group', 'key', 'value', 'struture'];

    public function getCurrencyAttribute()
    {
        $currency = $this->attributes['currency'];

        if (Auth::check() && Auth::user()->region === 'India') {

            $currency = 'INR';
        }

        return $currency;
    }

    public function getCurrencySymbolAttribute()
    {
        $currency_symbol = $this->attributes['currency_symbol'];

        if (Auth::check() && Auth::user()->region === 'India') {

            $currency_symbol = '₹';
        }

        return $currency_symbol;
    }
}
