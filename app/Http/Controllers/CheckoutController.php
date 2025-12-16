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
    public function index(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product', 'items.productSize')->first();
        
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }
        
        $items = $cart->items;
        
        if ($request->has('item_id')) {
            $itemId = (int) $request->input('item_id');
            
            $selectedItem = $items->firstWhere('id', $itemId);
            
            if (!$selectedItem) {
                return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
            }
            
            $items = collect([$selectedItem]);
            
            $cart->setRelation('items', $items);
        }
        
        return view('checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,gcash',
            'payment_screenshot' => 'required_if:payment_method,gcash|image|mimes:jpeg,png,jpg|max:2048',
            'item_ids' => 'nullable|array',
            'item_ids.*' => 'exists:cart_items,id',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('items.product', 'items.productSize')->first();

        if(!$cart || $cart->items->count() === 0){
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        $itemsToCheckout = $cart->items;
        if ($request->has('item_ids') && !empty($request->item_ids)) {
            $itemsToCheckout = $cart->items->whereIn('id', $request->item_ids);
            if ($itemsToCheckout->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Selected items not found.');
            }
        }

        DB::transaction(function() use ($cart, $request, $itemsToCheckout) {
            $totalAmount = $itemsToCheckout->sum(fn($i) => $i->price * $i->quantity);
            
            $paymentScreenshot = null;
            if ($request->payment_method === 'gcash' && $request->hasFile('payment_screenshot')) {
                $paymentScreenshot = $request->file('payment_screenshot')->store('payment-screenshots', 'public');
            }
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_screenshot' => $paymentScreenshot,
            ]);

            foreach($itemsToCheckout as $item){
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'product_size_id' => $item->product_size_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            $itemIds = $itemsToCheckout->pluck('id')->toArray();
            $cart->items()->whereIn('id', $itemIds)->delete();
            
            if ($cart->items()->count() === 0) {
                $cart->delete();
            }
        });

        return redirect()->route('dashboard')->with('success', 'Your order has been submitted and is pending admin approval.');
    }
}
