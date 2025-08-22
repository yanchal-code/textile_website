<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeafCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'sub_category_id', 'spec_fields', 'v1st', 'v2nd', 'status'];

    protected $casts = ['spec_fields' => 'array'];

    /**
     * Get the sub-category the leaf category belongs to.
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
