@extends('layouts.app')

@section('content')
@include('partials.topbar', ['pageTitle' => 'My Orders'])

<div class="min-h-screen bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-black mb-2">My Orders</h1>
            <p class="text-black mb-4">View your order history and track your purchases</p>
            
            {{-- Filter Tabs --}}
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="{{ route('orders.index', ['status' => 'all']) }}" 
                   class="px-4 py-2 rounded-lg border-2 border-black font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'all' ? 'bg-yellow-500 text-black' : 'bg-white text-black hover:bg-yellow-50' }}">
                    All
                </a>
                <a href="{{ route('orders.index', ['status' => 'pending']) }}" 
                   class="relative px-4 py-2 rounded-lg border-2 border-black font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'pending' ? 'bg-yellow-500 text-black' : 'bg-white text-black hover:bg-yellow-50' }}">
                    Pending
                    @if(($statusCounts['pending'] ?? 0) > 0)
                        <span class="absolute -top-2 -right-2 bg-yellow-500 text-black text-xs rounded-full min-w-5 h-5 px-1.5 flex items-center justify-center font-medium border-2 border-black">
                            {{ $statusCounts['pending'] }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" 
                   class="relative px-4 py-2 rounded-lg border-2 border-black font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'cancelled' ? 'bg-yellow-500 text-black' : 'bg-white text-black hover:bg-yellow-50' }}">
                    Cancelled
                    @php
                        $currentCancelledCount = $statusCounts['cancelled'] ?? 0;
                        $viewedCancelledCount = $viewedStatusCounts['cancelled'] ?? 0;
                        $newCancelledCount = $currentCancelledCount - $viewedCancelledCount;
                        $showCancelledBadge = $newCancelledCount > 0;
                    @endphp
                    @if($showCancelledBadge)
                        <span class="absolute -top-2 -right-2 bg-yellow-500 text-black text-xs rounded-full min-w-5 h-5 px-1.5 flex items-center justify-center font-medium border-2 border-black">
                            {{ $newCancelledCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('orders.index', ['status' => 'declined']) }}" 
                   class="relative px-4 py-2 rounded-lg border-2 border-black font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'declined' ? 'bg-yellow-500 text-black' : 'bg-white text-black hover:bg-yellow-50' }}">
                    Declined
                    @php
                        $currentDeclinedCount = $statusCounts['declined'] ?? 0;
                        $viewedDeclinedCount = $viewedStatusCounts['declined'] ?? 0;
                        $newDeclinedCount = $currentDeclinedCount - $viewedDeclinedCount;
                        $showDeclinedBadge = $newDeclinedCount > 0;
                    @endphp
                    @if($showDeclinedBadge)
                        <span class="absolute -top-2 -right-2 bg-yellow-500 text-black text-xs rounded-full min-w-5 h-5 px-1.5 flex items-center justify-center font-medium border-2 border-black">
                            {{ $newDeclinedCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('orders.index', ['status' => 'confirm']) }}" 
                   class="relative px-4 py-2 rounded-lg border-2 border-black font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'confirm' ? 'bg-yellow-500 text-black' : 'bg-white text-black hover:bg-yellow-50' }}">
                    Confirm
                    @if(($statusCounts['confirm'] ?? 0) > 0)
                        <span class="absolute -top-2 -right-2 bg-yellow-500 text-black text-xs rounded-full min-w-5 h-5 px-1.5 flex items-center justify-center font-medium border-2 border-black">
                            {{ $statusCounts['confirm'] }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('orders.index', ['status' => 'on deliver']) }}" 
                   class="relative px-4 py-2 rounded-lg border-2 border-black font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'on deliver' ? 'bg-yellow-500 text-black' : 'bg-white text-black hover:bg-yellow-50' }}">
                    On Deliver
                    @if(($statusCounts['on deliver'] ?? 0) > 0)
                        <span class="absolute -top-2 -right-2 bg-yellow-500 text-black text-xs rounded-full min-w-5 h-5 px-1.5 flex items-center justify-center font-medium border-2 border-black">
                            {{ $statusCounts['on deliver'] }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('orders.index', ['status' => 'complete']) }}" 
                   class="relative px-4 py-2 rounded-lg border-2 border-black font-semibold text-sm transition
                   {{ ($selectedStatus ?? 'all') === 'complete' ? 'bg-yellow-500 text-black' : 'bg-white text-black hover:bg-yellow-50' }}">
                    Complete
                    @php
                        $currentCompleteCount = $statusCounts['complete'] ?? 0;
                        $viewedCompleteCount = $viewedStatusCounts['complete'] ?? 0;
                        $newCompleteCount = $currentCompleteCount - $viewedCompleteCount;
                        $showCompleteBadge = $newCompleteCount > 0;
                    @endphp
                    @if($showCompleteBadge)
                        <span class="absolute -top-2 -right-2 bg-yellow-500 text-black text-xs rounded-full min-w-5 h-5 px-1.5 flex items-center justify-center font-medium border-2 border-black">
                            {{ $newCompleteCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        @if($orders->count() === 0)
            {{-- Empty Orders --}}
            <div class="bg-white border-2 border-black rounded-lg p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-black mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-black mb-3">
                    @if(isset($selectedStatus) && $selectedStatus !== 'all')
                        No {{ ucfirst($selectedStatus) }} orders
                    @else
                        No orders yet
                    @endif
                </h2>
                <p class="text-black mb-6">
                    @if(isset($selectedStatus) && $selectedStatus !== 'all')
                        You don't have any orders with this status.
                    @else
                        You haven't placed any orders. Start shopping to see your orders here!
                    @endif
                </p>
                @if(!isset($selectedStatus) || $selectedStatus === 'all')
                    <a href="{{ route('dashboard') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 px-8 rounded-lg transition border-2 border-black">
                        Start Shopping
                    </a>
                @endif
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white border-2 border-black rounded-lg p-6">
                        {{-- Order Header --}}
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b-2 border-black">
                            <div>
                                <h3 class="text-xl font-bold text-black mb-1">Order #{{ $order->id }}</h3>
                                <p class="text-sm text-black">Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="mt-4 md:mt-0 text-right">
                                <span class="inline-block px-4 py-2 rounded-lg border-2 border-black font-semibold text-sm bg-white text-black">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="text-2xl font-bold text-black mt-2">₱{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="mb-4">
                            <p class="text-sm text-black font-medium mb-1">Payment Method:</p>
                            <p class="text-base font-bold text-black">
                                {{ $order->payment_method === 'cod' ? 'Cash on Delivery (COD)' : 'GCash' }}
                            </p>
                        </div>

                        {{-- Order Items --}}
                        <div class="mb-4">
                            <h4 class="text-lg font-bold text-black mb-3">Order Items</h4>
                            <div class="space-y-3">
                                @foreach($order->items as $item)
                                    <div class="flex flex-col md:flex-row items-center gap-4 p-3 border border-black rounded-lg">
                                        {{-- Product Image --}}
                                        <div class="w-full md:w-24 flex-shrink-0">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                    alt="{{ $item->product->name }}" 
                                                    class="w-full h-32 object-cover rounded-lg"
                                                    style="aspect-ratio: 3/4;">
                                            @else
                                                <div class="w-full h-32 bg-black rounded-lg flex items-center justify-center"
                                                    style="aspect-ratio: 3/4;">
                                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Product Info --}}
                                        <div class="flex-grow w-full md:w-auto text-center md:text-left">
                                            <h5 class="text-lg font-bold text-black mb-1">{{ $item->product->name }}</h5>
                                            <div class="flex flex-wrap justify-center md:justify-start items-center gap-4">
                                                <div>
                                                    <span class="text-sm text-black font-medium">Quantity: </span>
                                                    <span class="text-base font-bold text-black">{{ $item->quantity }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-sm text-black font-medium">Unit Price: </span>
                                                    <span class="text-base font-bold text-black">₱{{ number_format($item->price, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Item Total --}}
                                        <div class="text-center md:text-right w-full md:w-auto">
                                            <p class="text-sm text-black font-medium mb-1">Item Total</p>
                                            <p class="text-xl font-bold text-black">₱{{ number_format($item->quantity * $item->price, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Payment Screenshot (if GCash) --}}
                        @if($order->payment_method === 'gcash' && $order->payment_screenshot)
                            <div class="mt-4 pt-4 border-t-2 border-black">
                                <p class="text-sm text-black font-medium mb-2">Payment Screenshot:</p>
                                <button data-image-url="{{ asset('storage/' . $order->payment_screenshot) }}" 
                                        onclick="openImageModal(this.getAttribute('data-image-url'))" 
                                        class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-2 px-4 rounded-lg transition border-2 border-black">
                                    View Payment Image
                                </button>
                            </div>
                        @endif

                        {{-- Cancel Button (only for pending orders) --}}
                        @if($order->status === 'pending')
                            <div class="mt-6 pt-4 border-t-2 border-black">
                                <form method="POST" action="{{ route('orders.cancel', $order->id) }}" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition border-2 border-black">
                                        Cancel Order
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white border-b-2 border-black px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-black">Payment Screenshot</h3>
            <button onclick="closeImageModal()" class="text-black hover:text-yellow-500 text-2xl font-bold">
                &times;
            </button>
        </div>
        <div class="p-6">
            <img id="modalImage" src="" alt="Payment Screenshot" class="w-full h-auto rounded-lg border-2 border-black">
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection

