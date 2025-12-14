@extends('layouts.app')

@section('content')
@php $selectedCategoryId = request('category_id'); @endphp

{{-- Topbar --}}
<nav class="bg-white border-b border-black sticky top-0 z-50 shadow-sm">
    <div class="w-full px-6 lg:px-12">
        <div class="flex items-center justify-between h-20">
            {{-- Left: ZoneTwo Logo --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" class="text-3xl font-aesthetic text-black hover:text-yellow-500 transition">
                    ZoneTwo
                </a>
            </div>

            {{-- Center: Categories Navigation --}}
            <div class="hidden md:flex items-center space-x-8 flex-1 justify-center">
                @if(\App\Models\Category::count() > 0)
                    @foreach(\App\Models\Category::all() as $category)
                        <a href="{{ route('dashboard', ['category_id' => $category->id]) }}" 
                           class="text-base font-normal text-black hover:text-yellow-500 {{ $selectedCategoryId == $category->id ? 'border-b-2 border-yellow-500 pb-1' : '' }} transition whitespace-nowrap">
                            {{ $category->name }}
                        </a>
                    @endforeach
                @else
                    <span class="text-base text-black font-normal">No categories available</span>
                @endif
            </div>

            {{-- Right: Icons (Orders, Cart, Logout) --}}
            <div class="flex items-center space-x-0 flex-shrink-0">
                {{-- Orders Icon --}}
                <a href="{{ route('orders.index') }}" class="relative text-black hover:text-yellow-500 transition p-3 flex items-center" title="My Orders">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    @php
                        $baseQuery = \App\Models\Order::where('user_id', Auth::id());
                        $pendingCount = (clone $baseQuery)->where('status', 'pending')->count();
                        $confirmCount = (clone $baseQuery)->where('status', 'confirm')->count();
                        $onDeliverCount = (clone $baseQuery)->where('status', 'on deliver')->count();
                        
                        $viewedStatusCounts = session('viewed_order_status_counts', []);
                        $currentCompleteCount = (clone $baseQuery)->where('status', 'complete')->count();
                        $viewedCompleteCount = $viewedStatusCounts['complete'] ?? 0;
                        $newCompleteCount = max(0, $currentCompleteCount - $viewedCompleteCount);
                        
                        $currentCancelledCount = (clone $baseQuery)->where('status', 'cancelled')->count();
                        $viewedCancelledCount = $viewedStatusCounts['cancelled'] ?? 0;
                        $newCancelledCount = max(0, $currentCancelledCount - $viewedCancelledCount);
                        
                        $totalOrderCount = $pendingCount + $confirmCount + $onDeliverCount + $newCompleteCount + $newCancelledCount;
                    @endphp
                    @if($totalOrderCount > 0)
                        <span class="absolute top-2 right-2 bg-yellow-500 text-black text-xs rounded-full min-w-5 h-5 px-1.5 flex items-center justify-center font-medium">{{ $totalOrderCount }}</span>
                    @endif
                </a>

                {{-- Vertical Separator --}}
                <div class="h-8 w-px bg-black mx-2"></div>

                {{-- Shopping Cart Icon --}}
                <a href="{{ route('cart.index') }}" class="relative text-black hover:text-yellow-500 transition p-3 flex items-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @php
                        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->first()?->items()->count() ?? 0;
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute top-2 right-2 bg-yellow-500 text-black text-xs rounded-full w-5 h-5 flex items-center justify-center font-medium">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- Vertical Separator --}}
                <div class="h-8 w-px bg-black mx-2"></div>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="inline flex items-center">
                    @csrf
                    <button type="submit" class="text-black hover:text-yellow-500 transition p-3" title="Logout">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Mobile Categories Menu --}}
        <div class="md:hidden border-t border-black py-3">
            <div class="flex flex-wrap gap-2">
                @if(\App\Models\Category::count() > 0)
                    @foreach(\App\Models\Category::all() as $category)
                        <a href="{{ route('dashboard', ['category_id' => $category->id]) }}" 
                           class="text-xs px-2.5 py-1 rounded-full {{ $selectedCategoryId == $category->id ? 'bg-yellow-500 text-black' : 'bg-white border border-black text-black hover:bg-yellow-500' }} transition">
                            {{ $category->name }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</nav>

<div class="min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Success message --}}
        @if(session('success'))
            <div class="mb-6 bg-yellow-500 text-black px-6 py-4 rounded-lg shadow-md border-2 border-black">
                {{ session('success') }}
            </div>
        @endif

        {{-- Product Search / Listing via Livewire --}}
        <div id="product-search">
            @if($selectedCategoryId)
                @livewire('product-search', ['categoryId' => $selectedCategoryId])
            @else
                {{-- Welcome Section with Images --}}
                <div class="space-y-8">
                    <div class="text-center mb-8">
                        <h2 class="text-4xl font-bold text-black mb-2">Welcome to ZoneTwo!</h2>
                        <p class="text-lg text-black">Discover amazing products curated just for you</p>
                    </div>

                    {{-- Welcome Image 1 --}}
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="w-full md:w-1/2">
                            <img src="{{ asset('images/2b1s.jpg') }}" 
                                 alt="Fashion Collection" 
                                 class="w-full">
                        </div>
                        <div class="w-full md:w-1/2">
                            <h3 class="text-2xl font-bold text-black mb-3">Trending Fashion</h3>
                            <p class="text-black text-lg leading-relaxed">
                                Welcome, {{ Auth::user()->name }}! Explore our latest collection of trendy fashion pieces. 
                                From stylish shirts to comfortable pants, we have everything you need to express your unique style. 
                                Browse through our categories to discover your next favorite outfit.
                            </p>
                        </div>
                    </div>

                    {{-- Welcome Image 2 --}}
                    <div class="flex flex-col md:flex-row-reverse items-center gap-6">
                        <div class="w-full md:w-1/2">
                            <img src="{{ asset('images/z3.jpg') }}" 
                                 alt="Quality Products" 
                                 class="w-full">
                        </div>
                        <div class="w-full md:w-1/2">
                            <h3 class="text-2xl font-bold text-black mb-3">Premium Quality</h3>
                            <p class="text-black text-lg leading-relaxed">
                                We're thrilled to have you here! At ZoneTwo, quality is our top priority. 
                                Every product in our store is carefully selected to ensure you get the best value for your money. 
                                Experience comfort, style, and durability in every purchase.
                            </p>
                        </div>
                    </div>

                    {{-- Welcome Image 3 --}}
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="w-full md:w-1/2">
                            <img src="{{ asset('images/z2.jpg') }}" 
                                 alt="Shopping Experience" 
                                 class="w-full">
                        </div>
                        <div class="w-full md:w-1/2">
                            <h3 class="text-2xl font-bold text-black mb-3">Start Shopping</h3>
                            <p class="text-black text-lg leading-relaxed">
                                Ready to find your perfect match? Click on any category above to start browsing our amazing collection. 
                                Whether you're looking for casual wear, formal attire, or accessories, we've got you covered. 
                                Happy shopping!
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Product Modal --}}
@php
    $selectedProductId = request('product_id');
@endphp
@livewire('product-modal', ['productId' => $selectedProductId, 'categoryId' => $selectedCategoryId])
@endsection
