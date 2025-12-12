@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Add New Product</h1>
                <a href="{{ route('admin.dashboard') }}" 
                   class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select id="category_id" 
                            name="category_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter product name"
                           required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" 
                            name="description" 
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter product description"
                            required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <input type="number" 
                           id="price" 
                           name="price" 
                           value="{{ old('price') }}"
                           step="0.01"
                           min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="0.00"
                           required>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">Product Sizes & Stock</label>
                        <button type="button" 
                                onclick="addSizeField()" 
                                class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                            + Add Size
                        </button>
                    </div>
                    <div id="sizes-container" class="space-y-2">
                        <!-- Size fields will be added here dynamically -->
                    </div>
                    <div class="mt-2">
                        <label for="total-stock" class="block text-sm font-medium text-gray-700 mb-2">Total Stock</label>
                        <input type="number" 
                               id="total-stock" 
                               name="stock" 
                               value="0"
                               min="0"
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100"
                               placeholder="0">
                        <p class="text-xs text-gray-500 mt-1">This is automatically calculated from all sizes</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                    <input type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="flex gap-4">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Create Product
                    </button>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let sizeIndex = 0;

    function addSizeField(size = '', stock = 0) {
        const container = document.getElementById('sizes-container');
        const sizeField = document.createElement('div');
        sizeField.className = 'flex gap-2 items-end';
        sizeField.id = `size-field-${sizeIndex}`;
        
        sizeField.innerHTML = `
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                <input type="text" 
                       name="sizes[${sizeIndex}][size]" 
                       value="${size}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., S, M, L, XL"
                       required>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                <input type="number" 
                       name="sizes[${sizeIndex}][stock]" 
                       value="${stock}"
                       min="0"
                       oninput="updateTotalStock()"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="0"
                       required>
            </div>
            <button type="button" 
                    onclick="removeSizeField(${sizeIndex})" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                Remove
            </button>
        `;
        
        container.appendChild(sizeField);
        sizeIndex++;
        updateTotalStock();
    }

    function removeSizeField(index) {
        const field = document.getElementById(`size-field-${index}`);
        if (field) {
            field.remove();
            updateTotalStock();
        }
    }

    function updateTotalStock() {
        const stockInputs = document.querySelectorAll('input[name*="[stock]"]');
        let total = 0;
        
        stockInputs.forEach(input => {
            const value = parseInt(input.value) || 0;
            total += value;
        });
        
        document.getElementById('total-stock').value = total;
    }

    // Add one size field by default when page loads
    document.addEventListener('DOMContentLoaded', function() {
        addSizeField();
    });
</script>
@endsection

