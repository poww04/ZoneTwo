<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show cart page
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        return view('cart', compact('cart')); // cart.blade.php
    }

    // Add item to cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = (int) $request->quantity;

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $item = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($item) {
            $item->quantity += $qty;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $product->price
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    // Remove item from cart
    public function remove(CartItem $item)
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart && $item->cart_id == $cart->id) {
            $item->delete();
        }

        return back()->with('success', 'Item removed from cart.');
    }
}

