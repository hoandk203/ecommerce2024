@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-cart3"></i> Giỏ hàng</h3>
        </div>
        <div class="card-body">
            @if($cartItems->count() > 0)
                <form action="{{ route('cart.updateAll') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <!-- @if($item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="{{ $item->product->name }}"
                                                                style="width: 50px; height: 50px; object-fit: cover;" class="me-2">
                                                        @endif -->
                                                {{ $item->product->name }}
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->product->price, 2) }}</td>
                                        <td style="width: 150px;">
                                            <input type="number" name="quantities[{{ $item->product->id }}]"
                                                value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}"
                                                class="form-control">
                                        </td>
                                        <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item->product->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td colspan="2"><strong>${{ number_format($totalCart, 2) }}</strong></td>
                                </tr>
                                @if($totalCart >= 200)
                                    <tr class="table-success">
                                        <td colspan="3" class="text-end">
                                            <strong>Giảm giá (11%):</strong>
                                        </td>
                                        <td colspan="2">
                                            <strong>-${{ number_format($totalCart * 0.11, 2) }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">
                                            <strong>Thành tiền:</strong>
                                        </td>
                                        <td colspan="2">
                                            <strong>${{ number_format($totalCart * 0.89, 2) }}</strong>
                                        </td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('welcome') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Tiếp tục mua hàng
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-arrow-clockwise"></i> Cập nhật giỏ hàng
                            </button>
                            <a href="{{ route('cart.checkout') }}" class="btn btn-success">
                                <i class="bi bi-credit-card"></i> Thanh toán
                            </a>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-cart-x display-1"></i>
                    <p class="lead mt-3">Giỏ hàng của bạn đang trống</p>
                    <a href="{{ route('welcome') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Tiếp tục mua hàng
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection