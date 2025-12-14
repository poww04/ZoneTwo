<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
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
            'declined' => (clone $baseQuery)->where('status', 'declined')->count(),
            'confirm' => (clone $baseQuery)->where('status', 'confirm')->count(),
            'on deliver' => (clone $baseQuery)->where('status', 'on deliver')->count(),
            'complete' => (clone $baseQuery)->where('status', 'complete')->count(),
        ];

        // Mark "complete", "cancelled", or "declined" as viewed when user opens those filters
        // Store the count at the time of viewing in database
        if ($selectedStatus === 'complete') {
            User::where('id', Auth::id())->update(['viewed_complete_count' => $statusCounts['complete']]);
        } elseif ($selectedStatus === 'cancelled') {
            User::where('id', Auth::id())->update(['viewed_cancelled_count' => $statusCounts['cancelled']]);
        } elseif ($selectedStatus === 'declined') {
            User::where('id', Auth::id())->update(['viewed_declined_count' => $statusCounts['declined']]);
        }
        
        // Get user for later use
        $user = Auth::user();

        // Build query for orders list
        $query = Order::where('user_id', Auth::id())
            ->with('items.product', 'items.productSize')
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        // Get viewed status counts from database
        $viewedStatusCounts = [
            'complete' => $user->viewed_complete_count ?? 0,
            'cancelled' => $user->viewed_cancelled_count ?? 0,
            'declined' => $user->viewed_declined_count ?? 0,
        ];

        return view('orders', compact('orders', 'selectedStatus', 'statusCounts', 'viewedStatusCounts'));
    }

    public function cancel(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Unauthorized action.');
        }

        // Only allow cancellation of pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Only pending orders can be cancelled.');
        }

        // Update order status to cancelled
        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('orders.index', ['status' => 'pending'])->with('success', 'Order cancelled successfully.');
    }
}
