@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Manage Orders</h1>
        <a href="{{ route('admin.dashboard') }}" 
           class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->count() === 0)
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">No orders found.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-3 text-left">Order ID</th>
                            <th class="p-3 text-left">User</th>
                            <th class="p-3 text-left">Total</th>
                            <th class="p-3 text-left">Items</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Created At</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">#{{ $order->id }}</td>
                                <td class="p-3">{{ $order->user->name }}</td>
                                <td class="p-3">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="p-3">
                                    <ul class="list-disc list-inside text-sm">
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
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'confirm') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'on deliver') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'complete') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm text-gray-600">
                                    {{ $order->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="p-3">
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" 
                                                onchange="this.form.submit()" 
                                                class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirm" {{ $order->status === 'confirm' ? 'selected' : '' }}>Confirm</option>
                                            <option value="on deliver" {{ $order->status === 'on deliver' ? 'selected' : '' }}>On Deliver</option>
                                            <option value="complete" {{ $order->status === 'complete' ? 'selected' : '' }}>Complete</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

