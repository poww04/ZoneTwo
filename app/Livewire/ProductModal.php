<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class ProductModal extends Component
{
    public $productId;
    public $categoryId;
    public $quantity = 1;
    public $selectedSizeId = null;
    public $product;
    public $showModal = false;

    public function mount($productId = null, $categoryId = null)
    {
        $this->categoryId = $categoryId;
        
        // Get productId from request if not provided
        if (!$productId) {
            $productId = request('product_id');
        }
        
        $this->productId = $productId;
        
        if ($this->productId) {
            $this->product = Product::with('sizes')->find($this->productId);
            if ($this->product) {
                $requestQuantity = request('quantity', 1);
                $this->quantity = max(1, min((int)$requestQuantity, $this->product->stock));
                // Set default size if product has sizes
                if ($this->product->sizes->count() > 0) {
                    $this->selectedSizeId = $this->product->sizes->first()->id;
                }
                $this->showModal = true;
            }
        }
    }

    public function updatedProductId($value)
    {
        if ($value) {
            $this->product = Product::with('sizes')->find($value);
            if ($this->product) {
                $this->quantity = 1;
                // Set default size if product has sizes
                if ($this->product->sizes->count() > 0) {
                    $this->selectedSizeId = $this->product->sizes->first()->id;
                } else {
                    $this->selectedSizeId = null;
                }
                $this->showModal = true;
            }
        } else {
            $this->showModal = false;
        }
    }

    public function updatedSelectedSizeId()
    {
        // Reset quantity when size changes
        $this->quantity = 1;
        $this->updateQuantity();
    }

    public function increment()
    {
        $maxStock = $this->getMaxStock();
        if ($this->product && $this->quantity < $maxStock) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updateQuantity()
    {
        if ($this->product) {
            $maxStock = $this->getMaxStock();
            $this->quantity = max(1, min($this->quantity, $maxStock));
        }
    }

    public function getMaxStock()
    {
        if (!$this->product) {
            return 0;
        }

        // If size is selected, return stock for that size
        if ($this->selectedSizeId) {
            $size = $this->product->sizes->firstWhere('id', $this->selectedSizeId);
            return $size ? $size->stock : 0;
        }

        // Otherwise return total product stock
        return $this->product->stock;
    }

    public function close()
    {
        $this->showModal = false;
        $this->productId = null;
        $this->product = null;
        
        // Redirect to dashboard without product_id
        return $this->redirect(route('dashboard', ['category_id' => $this->categoryId]), navigate: true);
    }

    public function addToCart()
    {
        if (!$this->product) {
            return;
        }

        // Validate size selection if product has sizes
        if ($this->product->sizes->count() > 0 && !$this->selectedSizeId) {
            session()->flash('error', 'Please select a size.');
            return;
        }

        // Check stock availability
        $maxStock = $this->getMaxStock();
        if ($this->quantity > $maxStock) {
            session()->flash('error', 'Insufficient stock available.');
            return;
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Find existing cart item with same product and size
        $item = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $this->product->id)
                        ->where('product_size_id', $this->selectedSizeId)
                        ->first();

        if ($item) {
            $newQuantity = $item->quantity + $this->quantity;
            if ($newQuantity > $maxStock) {
                session()->flash('error', 'Cannot add more items. Stock limit reached.');
                return;
            }
            $item->quantity = $newQuantity;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $this->product->id,
                'product_size_id' => $this->selectedSizeId,
                'quantity' => $this->quantity,
                'price' => $this->product->price
            ]);
        }

        $this->showModal = false;
        $this->productId = null;
        $this->product = null;
        $this->selectedSizeId = null;
        
        session()->flash('success', 'Product added to cart!');
        return $this->redirect(route('dashboard', ['category_id' => $this->categoryId]), navigate: true);
    }

    public function getTotalPriceProperty()
    {
        if ($this->product) {
            return $this->product->price * $this->quantity;
        }
        return 0;
    }

    public function render()
    {
        return view('livewire.product-modal');
    }
}

