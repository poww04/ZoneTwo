@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-4">Checkout</h1>

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
                </tr>
            </thead>
            <tbody>
                @foreach($cart->items as $item)
                    <tr class="border-b">
                        <td class="p-3">{{ $item->product->name }}</td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">₱{{ number_format($item->price, 2) }}</td>
                        <td class="p-3">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right mt-4 mb-4">
            <p class="text-xl font-bold">
                Total: ₱{{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity), 2) }}
            </p>
        </div>

        <form method="POST" action="{{ route('checkout.process') }}">
            @csrf
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                Confirm Purchase
            </button>
        </form>
    @endif

</div>
@endsection
