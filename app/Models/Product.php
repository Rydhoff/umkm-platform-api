<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'store_id',
        'name',
        'price',
        'stock',
        'description',
        'image',
        'is_available'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : null;
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
