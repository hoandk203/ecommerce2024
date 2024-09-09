<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        $totalCart = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        return view('cart.index', compact('cartItems', 'totalCart'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cartItem = Cart::where('user_id', auth()->id())
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
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function remove($productId)
    {
        Cart::where('user_id', auth()->id())->where('product_id', $productId)->delete();

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }

    public function updateAll(Request $request)
    {
        $quantities = $request->input('quantities', []);

        foreach ($quantities as $productId => $quantity) {
            $cartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $product = Product::find($productId);
                if ($product && $quantity <= $product->quantity) {
                    $cartItem->update(['quantity' => $quantity]);
                } else {
                    return redirect()->route('cart.index')->with('error', 'Số lượng sản phẩm ' . $product->name . ' không đủ.');
                }
            }
        }

        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật.');
    }
}
