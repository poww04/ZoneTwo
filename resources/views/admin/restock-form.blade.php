@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Restock: {{ $product->name }}</h1>
                <a href="{{ route('admin.products.restock') }}" 
                   class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-32 h-32 object-cover rounded-lg border border-gray-300 mb-4">
                @endif
                <p class="text-gray-600"><strong>Category:</strong> {{ $product->category->name }}</p>
                <p class="text-gray-600"><strong>Price:</strong> ₱{{ number_format($product->price, 2) }}</p>
                <p class="text-gray-600"><strong>Current Total Stock:</strong> {{ $product->stock }}</p>
            </div>

            <form method="POST" action="{{ route('admin.products.restock.update', $product) }}">
                @csrf
                
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Update Stock by Size</h2>
                    
                    @if($product->sizes->count() > 0)
                        <div class="space-y-4">
                            @foreach($product->sizes as $size)
                                <div class="flex items-center gap-4 p-4 border border-gray-300 rounded-lg">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Size: <span class="font-bold text-lg">{{ $size->size }}</span>
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-600">Current Stock:</span>
                                            <span class="text-sm font-semibold text-gray-800">{{ $size->stock }}</span>
                                        </div>
                                    </div>
                                    <div class="w-48">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">New Stock</label>
                                        <input type="hidden" name="sizes[{{ $loop->index }}][id]" value="{{ $size->id }}">
                                        <input type="number" 
                                               name="sizes[{{ $loop->index }}][stock]" 
                                               value="{{ old('sizes.'.$loop->index.'.stock', $size->stock) }}"
                                               min="0"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                               required>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800">This product has no sizes defined. Please edit the product to add sizes first.</p>
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="total-stock" class="block text-sm font-medium text-gray-700 mb-2">New Total Stock</label>
                    <input type="number" 
                           id="total-stock" 
                           value="{{ $product->stock }}"
                           readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                    <p class="text-xs text-gray-500 mt-1">This will be automatically calculated from all sizes</p>
                </div>

                @if($product->sizes->count() > 0)
                    <div class="flex gap-4">
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded-lg transition">
                            Update Stock
                        </button>
                        <a href="{{ route('admin.products.restock') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition">
                            Cancel
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
    function updateTotalStock() {
        const stockInputs = document.querySelectorAll('input[name*="[stock]"]:not([readonly])');
        let total = 0;
        
        stockInputs.forEach(input => {
            const value = parseInt(input.value) || 0;
            total += value;
        });
        
        document.getElementById('total-stock').value = total;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const stockInputs = document.querySelectorAll('input[name*="[stock]"]:not([readonly])');
        stockInputs.forEach(input => {
            input.addEventListener('input', updateTotalStock);
        });
    });
</script>
@endsection

