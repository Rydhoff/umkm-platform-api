<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['buyer_id', 'seller_id', 'store_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
