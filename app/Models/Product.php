<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [

        'name',
        'slug',
        'd_type',
        'category_id',
        'sub_category_id',
        'leaf_category_id',
        'brand_id',
        'design_number',
        'sku',
        'color',
        'size',
        'shipping',
        'shippingAddons',
        'h_time',
        'd_time',
        's_services',
        'price',
        'compare_price',
        'quantity',
        'short_description',
        'description',
        'has_variations',
        'is_featured',
        'status',
        'specs',
        'is_featured',

        'meta_title',
        'meta_description',
        'meta_keywords',
        'alt_image_text'
    ];


    protected $casts = ['specs' => 'array'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            // Delete variations
            $product->variations()->delete();

            // Delete images from storage
            foreach ($product->images as $image) {
                if (File::exists($image->image)) {
                    File::delete($image->image);
                }
                $image->delete(); // Delete record from DB
            }
        });
    }



    /**
     * Relationship: A product can have many variations.
     */

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Relationship: A product can have many images.
     */

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function videos()
    {
        return $this->hasMany(ProductVideo::class);
    }
    /**
     * Get the default image for the product.
     */
    public function defaultImage()
    {
        return $this->hasOne(ProductImage::class)
            ->whereNull('variation_id')
            ->where('is_default', 1);
    }


    public function defaultImage2()
    {
        return ProductImage::where('product_id', $this->id);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relationship with SubCategory
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    // Relationship with LeafCategory
    public function leafCategory()
    {
        return $this->belongsTo(LeafCategory::class, 'leaf_category_id');
    }

    // Relationship with Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }


    // Relationship with Ratings
    public function product_ratings()
    {
        return $this->hasMany(ProductRating::class)->where('status', 1);
    }


    public function getPriceAttribute()
    {

        $price = $this->attributes['price'];

        if (Auth::check() && Auth::user()->region === 'India') {

            $settings = Setting::latest()->first();

            $price = $price * $settings->conversion_rate_usd_to_inr;
        }

        return $price;
    }

    public function getComparePriceAttribute()
    {

        $comparePrice = $this->attributes['compare_price'];

        if (Auth::check() && Auth::user()->region === 'India') {
            $settings = Setting::latest()->first();

            $comparePrice = $comparePrice * $settings->conversion_rate_usd_to_inr;
        }

        return $comparePrice;
    }
}
