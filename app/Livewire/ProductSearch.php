<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductSearch extends Component
{
    public $search = '';
    public $categoryId;

    public function mount($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function updatingSearch()
    {
        // This method is called when the search property is being updated
        // Useful for resetting pagination or other side effects
    }

    public function render()
    {
        $products = Product::where('category_id', $this->categoryId)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return view('livewire.product-search', [
            'products' => $products,
        ]);
    }
}

