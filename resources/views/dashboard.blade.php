@extends('layouts.app')

@section('content')
@php $selectedCategoryId = request('category_id'); @endphp

{{-- Topbar --}}
<nav class="bg-white border-b border-black sticky top-0 z-50 shadow-sm">
    <div class="w-full px-6 lg:px-12">
        <div class="flex items-center justify-between h-14">
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

            {{-- Right: Icons (Logout, Cart) --}}
            <div class="flex items-center space-x-0 flex-shrink-0">
                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="inline flex items-center">
                    @csrf
                    <button type="submit" class="text-black hover:text-yellow-500 transition p-2" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>

                {{-- Vertical Separator --}}
                <div class="h-6 w-px bg-black mx-1"></div>

                {{-- Shopping Cart Icon --}}
                <a href="{{ route('cart.index') }}" class="relative text-black hover:text-yellow-500 transition p-2 flex items-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @php
                        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->first()?->items()->count() ?? 0;
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute top-1 right-1 bg-yellow-500 text-black text-xs rounded-full w-4 h-4 flex items-center justify-center font-medium">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        {{-- Mobile Categories Menu --}}
        <div class="md:hidden border-t border-black py-2">
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
                <div class="bg-white rounded-lg shadow-md p-6 border-2 border-black">
                    <p class="text-black text-center">Select a category from the navigation above to view products.</p>
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
