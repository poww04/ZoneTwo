<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    {{-- Search Bar --}}
    <div class="mb-6">
        <div class="relative">
            <input type="text" 
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search products by name or description..."
                   class="w-full border-2 border-gray-300 rounded-lg py-3 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            @if($search)
                <button wire:click="$set('search', '')" 
                        class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>
        @if($search)
            <p class="text-sm text-gray-500 mt-2">
                {{ $products->count() }} {{ Str::plural('product', $products->count()) }} found
            </p>
        @endif
    </div>

    {{-- Products Grid --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($products as $product)
                <a href="{{ route('dashboard', ['category_id' => $categoryId, 'product_id' => $product->id]) }}"
                   class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 hover:shadow-md transition-all cursor-pointer block">
                    @if($product->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-40 object-cover rounded-lg">
                        </div>
                    @else
                        <div class="mb-3 bg-gray-200 rounded-lg h-40 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif

                    <h3 class="font-semibold text-gray-800 text-lg mb-2 line-clamp-2">{{ $product->name }}</h3>
                    
                    @if($product->description)
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                    @endif

                    <div class="flex justify-between items-center">
                        <p class="text-lg font-bold text-blue-600">₱{{ number_format($product->price, 2) }}</p>
                        <p class="text-xs text-gray-500 {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stock > 0 ? "Stock: {$product->stock}" : 'Out of Stock' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-gray-600 text-lg font-medium">
                @if($search)
                    No products found matching "{{ $search }}"
                @else
                    No products available in this category.
                @endif
            </p>
            @if($search)
                <button wire:click="$set('search', '')" 
                        class="mt-4 text-blue-600 hover:text-blue-800 underline">
                    Clear search
                </button>
            @endif
        </div>
    @endif
</div>
