<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price'];

    // Cart item belongs to a cart
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Cart item belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

