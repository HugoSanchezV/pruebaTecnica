<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'stock', 'store_id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function cartItems() {
        $this->hasMany(CartItem::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    } 
}
