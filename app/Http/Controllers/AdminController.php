<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Category created successfully!');
    }


    public function createCategory()
    {
        return view('admin.create-category');
    }
    public function createProduct()
    {
        $categories = Category::all(); 
        return view('admin.create-product', compact('categories'));
    }


    public function storeProduct(Request $request)
    {

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',  
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public'); 
        } else {
            $imagePath = null;
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath, 
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Product created successfully!');
    }


    public function orders()
    {
        $orders = Order::with('items.product')->where('status', 'pending')->get();
        return view('admin.orders', compact('orders'));
    }

    public function approveOrder(Order $order)
    {
        if($order->status !== 'pending'){
            return redirect()->back()->with('error', 'Order cannot be approved.');
        }

        foreach($order->items as $item){
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
        }

        $order->status = 'completed';
        $order->save();

        return redirect()->back()->with('success', 'Order approved successfully.');
    }
}
