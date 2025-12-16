@extends('layouts.app')

@section('content')
@include('partials.topbar', ['pageTitle' => 'Cart'])

<div class="min-h-screen bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-black mb-2">Your Cart</h1>
            <p class="text-black">Review your items before checkout</p>
        </div>

        @if(!$cart || $cart->items->count() === 0)
            {{-- Empty Cart --}}
            <div class="bg-white border-2 border-black rounded-lg p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-black mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-black mb-3">Your cart is empty</h2>
                <p class="text-black mb-6">Start adding products to your cart!</p>
                <a href="{{ route('dashboard') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 px-8 rounded-lg transition border-2 border-black">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="space-y-4 mb-8">
                @foreach($cart->items as $item)
                    <div class="bg-white border-2 border-black rounded-lg p-6 transition-all">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            {{-- Product Image --}}
                            <div class="w-full md:w-32 flex-shrink-0">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                        alt="{{ $item->product->name }}" 
                                        class="w-full h-40 object-cover rounded-lg border-2 border-black"
                                        style="aspect-ratio: 3/4;">
                                @else
                                    <div class="w-full h-40 bg-black rounded-lg border-2 border-black flex items-center justify-center">
                                        <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-grow w-full md:w-auto">
                                <h3 class="text-xl font-bold text-black mb-2">{{ $item->product->name }}</h3>
                                <p class="text-sm text-black mb-4">{{ $item->product->description ? Str::limit($item->product->description, 100) : 'No description available.' }}</p>
                                
                                <div class="flex flex-wrap items-center gap-4 mb-4">
                                    @if($item->productSize)
                                        <div>
                                            <span class="text-sm text-black font-medium">Size: </span>
                                            <span class="text-lg font-bold text-black">{{ $item->productSize->size }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-sm text-black font-medium">Quantity: </span>
                                        <span class="text-lg font-bold text-black">{{ $item->quantity }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-black font-medium">Unit Price: </span>
                                        <span class="text-lg font-bold text-black">₱{{ number_format($item->price, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-4 w-full md:w-auto">
                                <div class="text-right">
                                    <p class="text-sm text-black font-medium mb-1">Item Total</p>
                                    <p class="text-2xl font-bold text-black">₱{{ number_format($item->quantity * $item->price, 2) }}</p>
                                </div>
                                
                                @php
                                    // Check if item is out of stock
                                    $isOutOfStock = false;
                                    if ($item->productSize) {
                                        // If item has a size, check size stock
                                        $isOutOfStock = $item->productSize->stock <= 0;
                                    } else {
                                        // If no size, check product stock
                                        $isOutOfStock = $item->product->stock <= 0;
                                    }
                                @endphp
                                
                                <div class="flex gap-3">
                                    <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-white border-2 border-black hover:bg-black hover:text-white text-black font-semibold py-2 px-4 rounded-lg transition">
                                            Remove
                                        </button>
                                    </form>
                                    @if($isOutOfStock)
                                        <div class="bg-red-100 border-2 border-red-500 text-red-700 font-semibold py-2 px-4 rounded-lg text-center">
                                            Not Available (out of stocks!)
                                        </div>
                                    @else
                                        <a href="{{ route('checkout.index', ['item_id' => $item->id]) }}" class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-2 px-4 rounded-lg transition border-2 border-black inline-block">
                                            Checkout
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white border-2 border-black rounded-lg p-6">
                <h2 class="text-2xl font-bold text-black mb-6">Order Summary</h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-black font-medium">Subtotal ({{ $cart->items->count() }} {{ Str::plural('item', $cart->items->count()) }})</span>
                        <span class="text-lg font-bold text-black">₱{{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                    </div>
                    <div class="border-t-2 border-black pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-black">Total</span>
                            <span class="text-3xl font-bold text-black">₱{{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                        </div>
                    </div>
                </div>

                @php
                    // Check if any item in cart is out of stock
                    $hasOutOfStockItems = false;
                    foreach($cart->items as $cartItem) {
                        if ($cartItem->productSize) {
                            if ($cartItem->productSize->stock <= 0) {
                                $hasOutOfStockItems = true;
                                break;
                            }
                        } else {
                            if ($cartItem->product->stock <= 0) {
                                $hasOutOfStockItems = true;
                                break;
                            }
                        }
                    }
                @endphp
                
                <div class="flex gap-4">
                    <a href="{{ route('dashboard') }}" class="flex-1 bg-white border-2 border-black hover:bg-black hover:text-white text-black font-semibold py-3 px-6 rounded-lg transition text-center">
                        Continue Shopping
                    </a>
                    @if($hasOutOfStockItems)
                        <div class="flex-1 bg-red-100 border-2 border-red-500 text-red-700 font-semibold py-3 px-6 rounded-lg text-center">
                            Not Available (out of stocks!)
                        </div>
                    @else
                        <form method="GET" action="{{ route('checkout.index') }}" class="flex-1">
                            <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 px-6 rounded-lg transition border-2 border-black">
                                Proceed to Checkout
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
