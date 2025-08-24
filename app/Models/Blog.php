<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'content',
        'meta_title',
        'meta_description',
        'user_id',
        'status',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
