@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-black mb-2">Checkout</h1>
            <p class="text-black">Review your order and confirm your purchase</p>
        </div>

        @if(!$cart || $cart->items->count() === 0)
            {{-- Empty Cart --}}
            <div class="bg-white border-2 border-black rounded-lg p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-black mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-black mb-3">Your cart is empty</h2>
                <p class="text-black mb-6">Add items to your cart before checkout.</p>
                <a href="{{ route('cart.index') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 px-8 rounded-lg transition border-2 border-black">
                    View Cart
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Order Items --}}
                <div class="lg:col-span-2 space-y-6">
                    <h2 class="text-2xl font-bold text-black mb-6">Order Items</h2>
                    @foreach($cart->items as $item)
                        <div class="bg-white border-2 border-black rounded-lg p-6 hover:bg-yellow-50 transition-all">
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                {{-- Product Image --}}
                                <div class="w-full md:w-32 flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                            alt="{{ $item->product->name }}" 
                                            class="w-full h-40 object-cover rounded-lg"
                                            style="aspect-ratio: 3/4;">
                                    @else
                                        <div class="w-full h-40 bg-black rounded-lg flex items-center justify-center"
                                            style="aspect-ratio: 3/4;">
                                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-grow w-full md:w-auto text-center md:text-left">
                                    <h3 class="text-xl font-bold text-black mb-2">{{ $item->product->name }}</h3>
                                    <div class="flex flex-wrap justify-center md:justify-start items-center gap-4">
                                        <div>
                                            <span class="text-sm text-black font-medium">Quantity: </span>
                                            <span class="text-lg font-bold text-black">{{ $item->quantity }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm text-black font-medium">Unit Price: </span>
                                            <span class="text-lg font-bold text-yellow-500">₱{{ number_format($item->price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Item Total --}}
                                <div class="text-center md:text-right w-full md:w-auto">
                                    <p class="text-sm text-black font-medium mb-1">Item Total</p>
                                    <p class="text-2xl font-bold text-yellow-500">₱{{ number_format($item->quantity * $item->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white border-2 border-black rounded-lg p-6 sticky top-24 hover:bg-yellow-50 transition-all">
                        <h2 class="text-2xl font-bold text-black mb-6">Order Summary</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-black font-medium">Items ({{ $cart->items->count() }})</span>
                                <span class="text-lg font-bold text-black">₱{{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-black font-medium">Shipping</span>
                                <span class="text-lg font-bold text-black">Free</span>
                            </div>
                            <div class="border-t-2 border-black pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-black">Total</span>
                                    <span class="text-3xl font-bold text-yellow-500">₱{{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('checkout.process') }}">
                            @csrf
                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-4 px-6 rounded-lg transition border-2 border-black text-lg">
                                Confirm Purchase
                            </button>
                        </form>

                        <a href="{{ route('cart.index') }}" class="block mt-4 text-center text-black hover:text-yellow-500 font-medium transition">
                            ← Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
