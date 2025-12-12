<div>
    @if($showModal && $product)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click="close">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="p-6">

                    <div class="flex justify-end mb-4">
                        <button wire:click="close" class="text-black hover:text-yellow-500 text-2xl font-bold">&times;</button>
                    </div>

                    @if($product->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @endif

                    <h2 class="text-3xl font-bold text-black mb-3">{{ $product->name }}</h2>
                    <p class="text-black mb-4">{{ $product->description ?: 'No description available.' }}</p>
                    <p class="text-xl font-semibold text-yellow-500 mb-6">₱{{ number_format($product->price, 2) }} per unit</p>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Size Selection --}}
                    @if($product->sizes && $product->sizes->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-black mb-2">Size</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->sizes as $size)
                                    <button type="button"
                                            wire:click="$set('selectedSizeId', {{ $size->id }})"
                                            class="px-4 py-2 rounded-lg border-2 font-semibold transition
                                                @if($selectedSizeId == $size->id)
                                                    bg-yellow-500 border-black text-black
                                                @elseif($size->stock == 0)
                                                    bg-gray-200 border-gray-400 text-gray-400 cursor-not-allowed
                                                @else
                                                    bg-white border-black text-black hover:bg-yellow-100
                                                @endif
                                                {{ $size->stock == 0 ? 'opacity-50' : '' }}">
                                        {{ $size->size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Quantity --}}
                    <div class="mb-6">
                        <label for="productQuantity" class="block text-sm font-medium text-black mb-2">Quantity</label>
                        <div class="flex items-center space-x-4">
                            @if($quantity > 1)
                                <button wire:click="decrement" 
                                        class="bg-white border-2 border-black hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-lg transition">-</button>
                            @else
                                <span class="bg-white border-2 border-gray-400 text-gray-400 font-bold py-2 px-4 rounded-lg cursor-not-allowed">-</span>
                            @endif

                            <input type="number" 
                                   wire:model.live="quantity"
                                   wire:change="updateQuantity"
                                   min="1" 
                                   max="{{ $this->getMaxStock() }}"
                                   class="w-20 text-center border-2 border-black rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">

                            @if($quantity < $this->getMaxStock())
                                <button wire:click="increment" 
                                        class="bg-white border-2 border-black hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-lg transition">+</button>
                            @else
                                <span class="bg-white border-2 border-gray-400 text-gray-400 font-bold py-2 px-4 rounded-lg cursor-not-allowed">+</span>
                            @endif
                        </div>
                        <p class="text-sm text-black mt-2">
                            Available stock: 
                            @if($selectedSizeId)
                                @php
                                    $selectedSize = $product->sizes->firstWhere('id', $selectedSizeId);
                                @endphp
                                {{ $selectedSize ? $selectedSize->stock : 0 }}
                            @else
                                {{ $product->stock }}
                            @endif
                        </p>
                    </div>

                    {{-- Total Price --}}
                    <div class="border-t-2 border-black pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-black">Total Price:</span>
                            <span class="text-2xl font-bold text-yellow-500">₱{{ number_format($this->totalPrice, 2) }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 flex space-x-4">
                        <button wire:click="close" 
                                class="flex-1 bg-white border-2 border-black hover:bg-black hover:text-white text-black font-semibold py-3 px-6 rounded-lg transition text-center">
                            Close
                        </button>
                        <button wire:click="addToCart"
                                class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-3 px-6 rounded-lg transition border-2 border-black">
                            Add to Cart
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>

