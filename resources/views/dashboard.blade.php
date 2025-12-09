@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-6 bg-green-500 text-white px-6 py-4 rounded-lg shadow-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Welcome to ZoneTwo!</h1>
                    <p class="text-gray-600 mt-2">Welcome back, {{ Auth::user()->name }}!</p>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('cart.index') }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Cart
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->first()?->items()->count() ?? 0;
                            @endphp
                            @if($cartCount > 0)
                                ({{ $cartCount }})
                            @endif
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                </div>
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
            $selectedProductId = request('product_id');
            $quantity = max(1, min((int)request('quantity', 1), 999));
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
                                <a href="{{ route('dashboard', ['category_id' => $selectedCategoryId, 'product_id' => $product->id]) }}" 
                                   class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer block">
                                    <p class="font-medium text-gray-800 text-lg mb-2">{{ $product->name }}</p>
                                    @if($product->description)
                                        <p class="text-sm text-gray-600 mb-2">{{ $product->description }}</p>
                                    @endif
                                    <p class="text-lg font-semibold text-blue-600 mb-1">₱{{ number_format($product->price, 2) }}</p>
                                    <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                                </a>
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

@if($selectedProductId && !session('success'))
    @php
        $selectedProduct = \App\Models\Product::find($selectedProductId);
        $maxQuantity = $selectedProduct ? min($quantity, $selectedProduct->stock) : $quantity;
        $totalPrice = $selectedProduct ? $selectedProduct->price * $maxQuantity : 0;
    @endphp
    @if($selectedProduct)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('dashboard', ['category_id' => $selectedCategoryId]) }}" 
                           class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</a>
                    </div>

                    @if($selectedProduct->image)
                        <div class="mb-4">
                            <img src="{{ $selectedProduct->image }}" alt="{{ $selectedProduct->name }}" class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @endif

                    <h2 class="text-3xl font-bold text-gray-800 mb-3">{{ $selectedProduct->name }}</h2>
                    <p class="text-gray-600 mb-4">{{ $selectedProduct->description ?: 'No description available.' }}</p>
                    <p class="text-xl font-semibold text-blue-600 mb-6">₱{{ number_format($selectedProduct->price, 2) }} per unit</p>

                    <div class="mb-6">
                        <label for="productQuantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <div class="flex items-center space-x-4">
                            @if($maxQuantity > 1)
                                <a href="{{ route('dashboard', ['category_id' => $selectedCategoryId, 'product_id' => $selectedProductId, 'quantity' => max(1, $maxQuantity - 1)]) }}" 
                                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition">-</a>
                            @else
                                <span class="bg-gray-100 text-gray-400 font-bold py-2 px-4 rounded-lg cursor-not-allowed">-</span>
                            @endif
                            
                            <form method="GET" action="{{ route('dashboard') }}" class="inline-flex items-center">
                                <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
                                <input type="hidden" name="product_id" value="{{ $selectedProductId }}">
                                <input type="number" 
                                       name="quantity"
                                       value="{{ $maxQuantity }}" 
                                       min="1" 
                                       max="{{ $selectedProduct->stock }}"
                                       class="w-20 text-center border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold py-2 px-3 rounded-lg transition">
                                    Update
                                </button>
                            </form>
                            
                            @if($maxQuantity < $selectedProduct->stock)
                                <a href="{{ route('dashboard', ['category_id' => $selectedCategoryId, 'product_id' => $selectedProductId, 'quantity' => min($selectedProduct->stock, $maxQuantity + 1)]) }}" 
                                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition">+</a>
                            @else
                                <span class="bg-gray-100 text-gray-400 font-bold py-2 px-4 rounded-lg cursor-not-allowed">+</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Available stock: {{ $selectedProduct->stock }}</p>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-800">Total Price:</span>
                            <span class="text-2xl font-bold text-blue-600">₱{{ number_format($totalPrice, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('dashboard', ['category_id' => $selectedCategoryId]) }}" 
                           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition text-center">
                            Close
                        </a>
                        <form method="POST" action="{{ route('cart.add') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $selectedProductId }}">
                            <input type="hidden" name="quantity" value="{{ $maxQuantity }}">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                                Add to Cart
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endif
@endsection
