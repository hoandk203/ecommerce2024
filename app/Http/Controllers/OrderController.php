<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use PDF;
use Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if (Auth::user()->role === 'admin') {
            return view('orders.show', compact('order'));
        }

        // Nếu là user thường, kiểm tra xem đơn hàng có phải của họ không
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('my-orders')
                ->with('error', 'Bạn không có quyền xem đơn hàng này');
        }

        return view('orders.show-my-order', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
    }

    public function printInvoice(Order $order)
    {
        $order->load('orderDetails.product', 'user');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orders.invoice', compact('order'));
        return $pdf->download(filename: 'invoice-' . $order->id . '.pdf');
    }

    public function destroy(Order $order)
    {
        // Xóa các chi tiết đơn hàng trước
        $order->orderDetails()->delete();

        // Sau đó xóa đơn hàng
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa thành công.');
    }

    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.my-orders', compact('orders'));
    }

}
