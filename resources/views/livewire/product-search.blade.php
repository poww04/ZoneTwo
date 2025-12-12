<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <input type="text" wire:model="search"
           placeholder="Search products..."
           class="w-full border border-gray-300 rounded-lg py-2 px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">

    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($products as $product)
                <a href="{{ route('dashboard', ['category_id' => $categoryId, 'product_id' => $product->id]) }}"
                   class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer block">
                    <p class="font-medium text-gray-800 text-lg mb-2">{{ $product->name }}</p>

                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-40 object-cover rounded-lg">
                        </div>
                    @endif

                    <p class="text-lg font-semibold text-blue-600 mb-1">₱{{ number_format($product->price, 2) }}</p>
                    <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-gray-600 text-center">No products found.</p>
    @endif
</div>
