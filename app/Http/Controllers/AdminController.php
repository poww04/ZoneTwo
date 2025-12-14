<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\ProductSize;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'sizes')->latest()->get();
        return view('admin.dashboard', compact('products'));
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
            'sizes' => 'required|array|min:1',
            'sizes.*.size' => 'required|string|max:255',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public'); 
        } else {
            $imagePath = null;
        }

        // Calculate total stock from sizes
        $totalStock = 0;
        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeData) {
                $totalStock += (int)($sizeData['stock'] ?? 0);
            }
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $totalStock,
            'image' => $imagePath, 
        ]);

        // Create product sizes
        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeData) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $sizeData['size'],
                    'stock' => (int)($sizeData['stock'] ?? 0),
                ]);
            }
        }

        return redirect()->route('admin.dashboard')->with('success', 'Product created successfully!');
    }

    public function restockProduct()
    {
        $products = Product::with('category', 'sizes')->latest()->get();
        return view('admin.restock-product', compact('products'));
    }

    public function showRestockForm(Product $product)
    {
        $product->load('sizes');
        return view('admin.restock-form', compact('product'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'sizes' => 'required|array|min:1',
            'sizes.*.id' => 'required|exists:product_sizes,id',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        $totalStock = 0;

        // Update each size's stock
        foreach ($request->sizes as $sizeData) {
            $size = ProductSize::find($sizeData['id']);
            if ($size && $size->product_id == $product->id) {
                $size->stock = (int)($sizeData['stock'] ?? 0);
                $size->save();
                $totalStock += $size->stock;
            }
        }

        // Update product total stock
        $product->stock = $totalStock;
        $product->save();

        return redirect()->route('admin.products.restock')->with('success', 'Product restocked successfully!');
    }

    public function orders()
    {
        $orders = Order::with('items.product', 'items.productSize')->where('status', 'pending')->get();
        return view('admin.orders', compact('orders'));
    }

    public function approveOrder(Order $order)
    {
        if($order->status !== 'pending'){
            return redirect()->back()->with('error', 'Order cannot be approved.');
        }

        DB::transaction(function() use ($order) {
            // Load order items with product and productSize relationships
            $order->load('items.product', 'items.productSize');

            foreach($order->items as $item){
                // Refresh product to get latest data
                $product = Product::find($item->product_id);
                
                // If order item has a specific size, deduct from that size's stock
                if($item->product_size_id){
                    // Refresh the ProductSize model to get latest stock
                    $size = ProductSize::find($item->product_size_id);
                    if($size && $size->product_id == $product->id){
                        // Deduct from size stock
                        $size->stock = max(0, $size->stock - $item->quantity);
                        $size->save();
                        
                        // Update product total stock by recalculating from all sizes
                        $product->load('sizes');
                        $totalStock = $product->sizes->sum('stock');
                        $product->stock = $totalStock;
                        $product->save();
                    } else {
                        // If size not found or doesn't match product, fallback to product total stock
                        $product->stock = max(0, $product->stock - $item->quantity);
                        $product->save();
                    }
                } else {
                    // Fallback: if no size specified, deduct from product total stock
                    $product->stock = max(0, $product->stock - $item->quantity);
                    $product->save();
                }
            }

            $order->status = 'confirm';
            $order->save();
        });

        return redirect()->back()->with('success', 'Order approved successfully.');
    }

    public function declineOrder(Order $order)
    {
        if($order->status !== 'pending'){
            return redirect()->back()->with('error', 'Order cannot be declined.');
        }

        // Update order status to declined
        $order->status = 'declined';
        $order->save();

        return redirect()->back()->with('success', 'Order declined successfully.');
    }

    public function manageOrders(Request $request)
    {
        $selectedStatus = $request->get('status', 'all');
        
        $query = Order::with('user', 'items.product', 'items.productSize')->latest();
        
        // Filter by status if provided
        if ($selectedStatus !== 'all') {
            $query->where('status', $selectedStatus);
        }
        
        $orders = $query->get();
        
        return view('admin.manage-orders', compact('orders', 'selectedStatus'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,cancelled,declined,confirm,on deliver,complete',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
