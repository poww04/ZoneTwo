<div>
    <div class="mb-6">
        <div class="relative">
            <input type="text" 
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search products by name or description..."
                   class="w-full border-2 border-black rounded-lg py-3 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
            <svg class="absolute left-3 top-3.5 h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            @if($search)
                <button wire:click="$set('search', '')" 
                        class="absolute right-3 top-3 text-black hover:text-yellow-500 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>
        @if($search)
            <p class="text-sm text-black mt-2">
                {{ $products->count() }} {{ Str::plural('product', $products->count()) }} found
            </p>
        @endif
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
                <a href="{{ route('dashboard', ['category_id' => $categoryId, 'product_id' => $product->id]) }}"
                   class="bg-white hover:bg-yellow-50 transition-all cursor-pointer block flex flex-col h-full">
                    @if($product->image)
                        <div class="mb-4 w-full overflow-hidden" style="aspect-ratio: 3/4;">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover object-center">
                        </div>
                    @else
                        <div class="mb-4 bg-black w-full flex items-center justify-center overflow-hidden" style="aspect-ratio: 3/4;">
                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif

                    <div class="flex-grow px-2">
                        <h3 class="font-semibold text-black text-lg mb-2 line-clamp-2">{{ $product->name }}</h3>
                        
                        @if($product->description)
                            <p class="text-sm text-black mb-3 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                        @endif
                    </div>

                    <div class="flex justify-between items-baseline mt-auto pt-3 px-2">
                        <p class="text-lg font-bold text-black">₱{{ number_format($product->price, 2) }}</p>
                        <p class="text-xs text-black font-medium {{ $product->stock > 0 ? '' : 'text-red-600' }}">
                            {{ $product->stock > 0 ? "Stock: {$product->stock}" : 'Out of Stock' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-black mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
            <p class="text-black text-lg font-medium">
                @if($search)
                    No products found matching "{{ $search }}"
                @else
                    No products available in this category.
                @endif
            </p>
            @if($search)
                <button wire:click="$set('search', '')" 
                        class="mt-4 text-yellow-500 hover:text-yellow-600 underline">
                    Clear search
                </button>
            @endif
        </div>
    @endif
</div>
