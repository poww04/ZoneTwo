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
            $this->product = Product::find($this->productId);
            if ($this->product) {
                $requestQuantity = request('quantity', 1);
                $this->quantity = max(1, min((int)$requestQuantity, $this->product->stock));
                $this->showModal = true;
            }
        }
    }

    public function updatedProductId($value)
    {
        if ($value) {
            $this->product = Product::find($value);
            if ($this->product) {
                $this->quantity = min(1, $this->product->stock);
                $this->showModal = true;
            }
        } else {
            $this->showModal = false;
        }
    }

    public function increment()
    {
        if ($this->product && $this->quantity < $this->product->stock) {
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
            $this->quantity = max(1, min($this->quantity, $this->product->stock));
        }
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

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $item = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $this->product->id)
                        ->first();

        if ($item) {
            $item->quantity += $this->quantity;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $this->product->id,
                'quantity' => $this->quantity,
                'price' => $this->product->price
            ]);
        }

        $this->showModal = false;
        $this->productId = null;
        $this->product = null;
        
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

