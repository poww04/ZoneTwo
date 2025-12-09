@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-4">Your Cart</h1>

    @if(!$cart || $cart->items->count() === 0)
        <p class="text-gray-600">Your cart is empty.</p>
    @else
        <table class="w-full bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-3">Product</th>
                    <th class="p-3">Qty</th>
                    <th class="p-3">Price</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart->items as $item)
                    <tr class="border-b">
                        <td class="p-3 flex items-center space-x-3">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                    alt="{{ $item->product->name }}" 
                                    class="w-16 h-16 object-cover rounded-lg">
                            @endif
                            <span>{{ $item->product->name }}</span>
                        </td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">₱{{ number_format($item->price, 2) }}</td>
                        <td class="p-3">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                        <td class="p-3 flex space-x-2">
                            <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Remove</button>
                            </form>

                            <form method="GET" action="{{ route('checkout.index') }}">
                                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Checkout</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right mt-4">
            <p class="text-xl font-bold">
                Total: ₱{{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 2) }}
            </p>
        </div>
    @endif
</div>
@endsection
