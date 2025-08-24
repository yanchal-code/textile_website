<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $fillable = [
        'product_id',
        'username',
        'email',
        'rating',
        'status',
        'title',
        'comment'
    ];

    use HasFactory;
    const UPDATED_AT = null;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
