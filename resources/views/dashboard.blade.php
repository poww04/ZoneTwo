@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Welcome to ZoneTwo!</h1>
                    <p class="text-gray-600 mt-2">Welcome back, {{ Auth::user()->name }}!</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Categories</h2>
            @if(\App\Models\Category::count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @php
                        $selectedCategoryId = request('category_id');
                    @endphp
                    @foreach(\App\Models\Category::all() as $category)
                        <a href="{{ route('dashboard', ['category_id' => $category->id]) }}" 
                           class="border-2 {{ $selectedCategoryId == $category->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition text-center">
                            <h3 class="text-lg font-semibold text-gray-800 hover:text-blue-600">{{ $category->name }}</h3>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">No categories available yet.</p>
            @endif
        </div>

        @php
            $selectedCategoryId = request('category_id');
        @endphp
        @if($selectedCategoryId)
            @php
                $selectedCategory = \App\Models\Category::find($selectedCategoryId);
                $products = \App\Models\Product::where('category_id', $selectedCategoryId)->get();
            @endphp
            @if($selectedCategory)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Products - {{ $selectedCategory->name }}</h2>
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($products as $product)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition">
                                    <p class="font-medium text-gray-800 text-lg mb-2">{{ $product->name }}</p>
                                    @if($product->description)
                                        <p class="text-sm text-gray-600 mb-2">{{ $product->description }}</p>
                                    @endif
                                    <p class="text-lg font-semibold text-blue-600 mb-1">₱{{ number_format($product->price, 2) }}</p>
                                    <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">No products in this category yet.</p>
                    @endif
                </div>
            @endif
        @else
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-600 text-center">Click on a category above to view products.</p>
            </div>
        @endif
    </div>
</div>
@endsection
