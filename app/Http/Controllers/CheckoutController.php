<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        return view('checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();

        if(!$cart || $cart->items->count() === 0){
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        DB::transaction(function() use ($cart) {

            $totalAmount = $cart->items->sum(fn($i) => $i->price * $i->quantity);
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            foreach($cart->items as $item){
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            $cart->items()->delete();
            $cart->delete();
        });

        return redirect()->route('dashboard')->with('success', 'Your order has been submitted and is pending admin approval.');
    }
}
