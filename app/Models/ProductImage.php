<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_id',
        'image',
        'is_default',
    ];

    /**
     * Relationship: An image belongs to a product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: An image may belong to a specific variation.
     */
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
