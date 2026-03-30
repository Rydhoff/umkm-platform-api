<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'buyer_id',
        'store_id',
        'product_total',
        'delivery_fee',
        'platform_fee',
        'total_price',
        'order_type',
        'status'
    ];

    // RELATION

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
