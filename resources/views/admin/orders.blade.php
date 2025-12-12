@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-4">Pending Orders</h1>

    @if($orders->count() === 0)
        <p>No pending orders.</p>
    @else
        <table class="w-full bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3">Order ID</th>
                    <th class="p-3">User</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Items</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr class="border-b">
                        <td class="p-3">{{ $order->id }}</td>
                        <td class="p-3">{{ $order->user->name }}</td>
                        <td class="p-3">₱{{ number_format($order->total_amount,2) }}</td>
                        <td class="p-3">
                            <ul>
                                @foreach($order->items as $item)
                                    <li>
                                        {{ $item->product->name }}
                                        @if($item->productSize)
                                            (Size: {{ $item->productSize->size }})
                                        @endif
                                        x {{ $item->quantity }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="p-3">
                            <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Approve</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
