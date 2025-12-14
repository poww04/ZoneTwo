<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $selectedStatus = $request->get('status', 'all');

        // Get counts for each status (always calculate fresh)
        $baseQuery = Order::where('user_id', Auth::id());
        $statusCounts = [
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
            'confirm' => (clone $baseQuery)->where('status', 'confirm')->count(),
            'on deliver' => (clone $baseQuery)->where('status', 'on deliver')->count(),
            'complete' => (clone $baseQuery)->where('status', 'complete')->count(),
        ];

        // Mark "complete" or "cancelled" as viewed when user opens those filters
        // Store the count at the time of viewing
        if ($selectedStatus === 'complete' || $selectedStatus === 'cancelled') {
            $viewedStatusCounts = $request->session()->get('viewed_order_status_counts', []);
            $viewedStatusCounts[$selectedStatus] = $statusCounts[$selectedStatus];
            $request->session()->put('viewed_order_status_counts', $viewedStatusCounts);
        }

        // Build query for orders list
        $query = Order::where('user_id', Auth::id())
            ->with('items.product', 'items.productSize')
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        // Get viewed status counts from session
        $viewedStatusCounts = $request->session()->get('viewed_order_status_counts', []);

        return view('orders', compact('orders', 'selectedStatus', 'statusCounts', 'viewedStatusCounts'));
    }
}
