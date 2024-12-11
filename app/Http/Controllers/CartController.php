<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Barryvdh\DomPDF\Facade as PDF; // Ensure this line is present
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    const DISCOUNT_THRESHOLD = 200; // Ngưỡng giảm giá
    const DISCOUNT_PERCENTAGE = 11; // Phần trăm giảm giá

    private function calculateDiscount($totalPrice)
    {
        if ($totalPrice >= self::DISCOUNT_THRESHOLD) {
            return $totalPrice * (self::DISCOUNT_PERCENTAGE / 100);
        }
        return 0;
    }

    private function getCartItems()
    {
        if (Auth::check()) {
            // Nếu đã đăng nhập, lấy từ database
            return Cart::where('user_id', Auth::id())->with('product')->get();
        } else {
            // Nếu chưa đăng nhập, lấy từ session
            $cartItems = session()->get('cart', []);
            $items = collect();

            foreach ($cartItems as $productId => $details) {
                $product = Product::find($productId);
                if ($product) {
                    $items->push((object) [
                        'product' => $product,
                        'quantity' => $details['quantity']
                    ]);
                }
            }

            return $items;
        }
    }

    public function index()
    {
        $cartItems = $this->getCartItems();
        $totalCart = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'totalCart'));
    }

    public function thaiviethoan(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if (Auth::check()) {
            // Xử lý cho user đã đăng nhập
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                if ($cartItem->quantity + 1 > $product->quantity) {
                    return redirect()->back()->with('error', 'Số lượng sản phẩm không đủ.');
                }
                $cartItem->increment('quantity');
            } else {
                if ($product->quantity < 1) {
                    return redirect()->back()->with('error', 'Sản phẩm đã hết hàng.');
                }
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => 1
                ]);
            }
        } else {
            // Xử lý cho khách vãng lai
            $cart = session()->get('cart', []);

            if (isset($cart[$productId])) {
                if ($cart[$productId]['quantity'] + 1 > $product->quantity) {
                    return redirect()->back()->with('error', 'Số lượng sản phẩm không đủ.');
                }
                $cart[$productId]['quantity']++;
            } else {
                if ($product->quantity < 1) {
                    return redirect()->back()->with('error', 'Sản phẩm đã hết hàng.');
                }
                $cart[$productId] = [
                    'quantity' => 1
                ];
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function remove($productId)
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }

    public function updateAll(Request $request)
    {
        $quantities = $request->input('quantities', []);

        if (Auth::check()) {
            foreach ($quantities as $productId => $quantity) {
                $cartItem = Cart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->first();

                if ($cartItem) {
                    $product = Product::find($productId);
                    if ($product && $quantity <= $product->quantity) {
                        $cartItem->update(['quantity' => $quantity]);
                    }
                }
            }
        } else {
            $cart = session()->get('cart', []);
            foreach ($quantities as $productId => $quantity) {
                $product = Product::find($productId);
                if ($product && $quantity <= $product->quantity) {
                    if (isset($cart[$productId])) {
                        $cart[$productId]['quantity'] = $quantity;
                    }
                }
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Giỏ hàng đã được cập nhật.');
    }

    public function checkout()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Tính giảm giá
        $discount = 0;
        if ($totalPrice >= self::DISCOUNT_THRESHOLD) {
            $discount = $totalPrice * (self::DISCOUNT_PERCENTAGE / 100);
        }
        $finalPrice = $totalPrice - $discount;

        return view('cart.checkout', compact('cartItems', 'totalPrice', 'discount', 'finalPrice'));
    }

    public function printInvoice(Request $request)
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        $buyerName = $request->input('buyer_name');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $orderTime = now()->setTimezone('Asia/Bangkok');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cart.invoice', compact('cartItems', 'buyerName', 'phone', 'address', 'orderTime'));
        return $pdf->download('invoice.pdf');
    }

    public function process(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'buyer_name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'payment_method' => 'required'
            ]);

            $cartItems = $this->getCartItems();
            $totalPrice = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Tính giảm giá
            $discount = $this->calculateDiscount($totalPrice);
            $finalPrice = $totalPrice - $discount;

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(), // Null nếu không đăng nhập
                'buyer_name' => $validatedData['buyer_name'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'payment_method' => $validatedData['payment_method'],
                'total_price' => $totalPrice,
                'discount' => $discount,
                'final_price' => $finalPrice,
                'status' => 'pending'
            ]);

            // Tạo chi tiết đơn hàng
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                // Giảm số lượng sản phẩm
                $item->product->decrement('quantity', $item->quantity);
            }

            // Xóa giỏ hàng
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                session()->forget('cart');
            }

            DB::commit();

            // Thay vì trả về view checkout, chuyển hướng đến trang cảm ơn
            return redirect()->route('cart.thank-you', ['order' => $order->id])
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage());
        }
    }

    public function thankYou(Order $order)
    {
        return view('cart.thank-you', compact('order'));
    }
}
