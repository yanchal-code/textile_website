<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color',
        'size',
        'sku',
        'price',
        'c_price',
        'quantity',
        'status'
    ];


    /**
     * Relationship: A variation belongs to a product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: A variation can have many images.
     */

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variation_id');
    }

    public function videos()
    {
        return $this->hasMany(ProductVideo::class ,'variation_id');
    }

    public function defaultImage()
    {
        return $this->hasOne(ProductImage::class, 'variation_id')->where('is_default', 1);
    }
}
