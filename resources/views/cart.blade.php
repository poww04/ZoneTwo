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
                        <td class="p-3">{{ $item->product->name }}</td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">₱{{ number_format($item->price, 2) }}</td>
                        <td class="p-3">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                        <td class="p-3">
                            <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Remove</button>
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
