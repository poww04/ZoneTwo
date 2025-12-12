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
            <form method="POST" action="{{ route('checkout.process') }}" enctype="multipart/form-data" id="checkout-form">
                @csrf
                @if(request('item_id'))
                    <input type="hidden" name="item_ids[]" value="{{ request('item_id') }}">
                @endif
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Left Side: Order Details and Items --}}
                    <div class="lg:col-span-2 space-y-6">
                    {{-- Order Details --}}
                    <div class="bg-white border-2 border-black rounded-lg p-4 hover:bg-yellow-50 transition-all">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                            <div>
                                <p class="text-xs text-black font-medium mb-1">Name</p>
                                <p class="text-base font-bold text-black">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-black font-medium mb-1">Email</p>
                                <p class="text-base font-bold text-black">{{ Auth::user()->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-black font-medium mb-1">Phone Number</p>
                                <p class="text-base font-bold text-black">{{ Auth::user()->phone_number ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-black font-medium mb-1">Address</p>
                                <p class="text-base font-bold text-black">{{ Auth::user()->address ?? 'Not provided' }}</p>
                            </div>
                        </div>

                        {{-- Payment Method Selection --}}
                        <div class="border-t-2 border-black pt-4">
                            <h3 class="text-base font-bold text-black mb-3">Payment Method</h3>
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border-2 border-black rounded-lg cursor-pointer hover:bg-yellow-50 transition">
                                    <input type="radio" name="payment_method" value="cod" checked class="mr-3 w-4 h-4 text-yellow-500 focus:ring-yellow-500 border-black" onchange="togglePaymentMethod()">
                                    <div>
                                        <span class="text-base font-semibold text-black">Cash on Delivery (COD)</span>
                                        <p class="text-xs text-black">Pay when you receive your order</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border-2 border-black rounded-lg cursor-pointer hover:bg-yellow-50 transition">
                                    <input type="radio" name="payment_method" value="gcash" class="mr-3 w-4 h-4 text-yellow-500 focus:ring-yellow-500 border-black" onchange="togglePaymentMethod()">
                                    <div>
                                        <span class="text-base font-semibold text-black">GCash</span>
                                        <p class="text-xs text-black">Pay via GCash QR code</p>
                                    </div>
                                </label>
                            </div>

                            {{-- GCash QR Code and Upload Section --}}
                            <div id="gcash-section" class="mt-4 hidden">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Left: QR Code --}}
                                    <div class="border-2 border-black rounded-lg p-4">
                                        <h3 class="text-lg font-bold text-black mb-4">Scan QR Code to Pay</h3>
                                        <div class="flex justify-center mb-4">
                                            @if(file_exists(public_path('images/gcash.png')))
                                                <img src="{{ asset('images/gcash.png') }}" alt="GCash QR Code" class="w-48 h-48 border-2 border-black rounded-lg object-contain">
                                            @else
                                                <div class="w-48 h-48 bg-gray-100 border-2 border-black rounded-lg flex items-center justify-center">
                                                    <p class="text-black text-center px-4 text-xs">Please add GCash QR code image at<br><code class="text-xs">public/images/gcash.png</code></p>
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-sm text-black text-center">Scan the QR code using your GCash app to complete payment</p>
                                    </div>

                                    {{-- Right: Upload Payment Screenshot --}}
                                    <div class="border-2 border-black rounded-lg p-4">
                                        <h3 class="text-lg font-bold text-black mb-4">Upload Payment Screenshot</h3>
                                        <div>
                                            <label for="payment_screenshot" class="block text-sm font-medium text-black mb-2">
                                                Payment Screenshot <span class="text-red-600">*</span>
                                            </label>
                                            <input type="file" 
                                                   id="payment_screenshot" 
                                                   name="payment_screenshot" 
                                                   accept="image/*" 
                                                   class="w-full px-4 py-2 border-2 border-black rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-black"
                                                   onchange="checkScreenshot(this)">
                                            <p class="text-xs text-black mt-2">Please upload a screenshot of your GCash payment confirmation</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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

                    {{-- Right Side: Order Summary --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white border-2 border-black rounded-lg p-6 sticky top-24 hover:bg-yellow-50 transition-all">
                            <h2 class="text-2xl font-bold text-black mb-6">Order Summary</h2>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-black font-medium">Items ({{ $cart->items->count() }} {{ Str::plural('item', $cart->items->count()) }})</span>
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

                            <button type="submit" id="confirm-btn" class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-4 px-6 rounded-lg transition border-2 border-black text-lg">
                                Confirm Purchase
                            </button>

                            <a href="{{ route('cart.index') }}" class="block mt-4 w-full bg-white border-2 border-black hover:bg-black hover:text-white text-black font-semibold py-3 px-6 rounded-lg transition text-center">
                                Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
    function togglePaymentMethod() {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const gcashSection = document.getElementById('gcash-section');
        const confirmBtn = document.getElementById('confirm-btn');
        const screenshotInput = document.getElementById('payment_screenshot');
        
        if (paymentMethod === 'gcash') {
            gcashSection.classList.remove('hidden');
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            gcashSection.classList.add('hidden');
            confirmBtn.disabled = false;
            confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            // Clear screenshot when switching to COD
            if (screenshotInput) {
                screenshotInput.value = '';
            }
        }
    }
    
    function checkScreenshot(input) {
        const confirmBtn = document.getElementById('confirm-btn');
        
        if (input.files && input.files[0]) {
            // Enable confirm button if screenshot is uploaded
            confirmBtn.disabled = false;
            confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            // Disable confirm button if no file
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Handle form submission
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const screenshotInput = document.getElementById('payment_screenshot');
        
        if (paymentMethod === 'gcash' && (!screenshotInput || !screenshotInput.files || screenshotInput.files.length === 0)) {
            e.preventDefault();
            alert('Please upload a payment screenshot for GCash payment.');
            return false;
        }
    });
</script>
@endsection
