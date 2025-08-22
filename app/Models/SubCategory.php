<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'category_id', 'status'];

    /**
     * Get the category the sub-category belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the leaf categories associated with the sub-category.
     */
    public function leafCategories()
    {
        return $this->hasMany(LeafCategory::class);
    }
}
