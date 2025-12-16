@extends('layouts.app')

@section('content')
@php $selectedCategoryId = request('category_id'); @endphp

@include('partials.topbar', ['showCategories' => true, 'selectedCategoryId' => $selectedCategoryId])

<div class="min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div id="product-search">
            @if($selectedCategoryId)
                @livewire('product-search', ['categoryId' => $selectedCategoryId])
            @else
                <div class="space-y-8">
                    <div class="text-center mb-8">
                        <h2 class="text-4xl font-bold text-black mb-2">Welcome to ZoneTwo!</h2>
                        <p class="text-lg text-black">Discover amazing products curated just for you</p>
                    </div>

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

@php
    $selectedProductId = request('product_id');
@endphp
@livewire('product-modal', ['productId' => $selectedProductId, 'categoryId' => $selectedCategoryId])
@endsection
