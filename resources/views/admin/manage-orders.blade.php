@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
        {{-- Header Card --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Manage Orders</h1>
                    <p class="text-gray-600 mt-2">View and manage all customer orders</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Filter Buttons --}}
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.orders.manage', ['status' => 'all']) }}" 
                   class="px-4 py-2 rounded-lg font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'all' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All
                </a>
                <a href="{{ route('admin.orders.manage', ['status' => 'confirm']) }}" 
                   class="px-4 py-2 rounded-lg font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'confirm' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    Confirm
                </a>
                <a href="{{ route('admin.orders.manage', ['status' => 'cancelled']) }}" 
                   class="px-4 py-2 rounded-lg font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'cancelled' ? 'bg-gray-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                    Cancelled
                </a>
                <a href="{{ route('admin.orders.manage', ['status' => 'declined']) }}" 
                   class="px-4 py-2 rounded-lg font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'declined' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                    Declined
                </a>
                <a href="{{ route('admin.orders.manage', ['status' => 'on deliver']) }}" 
                   class="px-4 py-2 rounded-lg font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'on deliver' ? 'bg-purple-600 text-white' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                    On Deliver
                </a>
                <a href="{{ route('admin.orders.manage', ['status' => 'complete']) }}" 
                   class="px-4 py-2 rounded-lg font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'complete' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                    Complete
                </a>
            </div>
        </div>

        {{-- Error Message --}}
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
                <div class="flex">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Orders Table --}}
        @if($orders->count() === 0)
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No orders found</h3>
                <p class="text-gray-500">There are no orders to display at this time.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Created At</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-900">#{{ $order->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            @foreach($order->items as $item)
                                                <li class="flex items-start">
                                                    <span class="text-gray-400 mr-2">•</span>
                                                    <span>
                                                        <span class="font-medium">{{ $item->product->name }}</span>
                                                        @if($item->productSize)
                                                            <span class="text-gray-500">(Size: {{ $item->productSize->size }})</span>
                                                        @endif
                                                        <span class="text-gray-500">x {{ $item->quantity }}</span>
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'confirm') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'on deliver') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'complete') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-gray-100 text-gray-800
                                            @elseif($order->status === 'declined') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $order->created_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($order->status === 'confirm')
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" 
                                                        onchange="this.form.submit()" 
                                                        class="px-3 py-2 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer hover:bg-blue-100 transition">
                                                    <option value="confirm" selected>Confirm</option>
                                                    <option value="on deliver">On Deliver</option>
                                                </select>
                                            </form>
                                        @elseif($order->status === 'on deliver')
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" 
                                                        onchange="this.form.submit()" 
                                                        class="px-3 py-2 bg-purple-50 border border-purple-200 text-purple-800 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 cursor-pointer hover:bg-purple-100 transition">
                                                    <option value="on deliver" selected>On Deliver</option>
                                                    <option value="complete">Complete</option>
                                                </select>
                                            </form>
                                        @elseif($order->status === 'complete')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm italic">No action available</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

