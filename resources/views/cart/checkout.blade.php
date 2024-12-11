@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3><i class="bi bi-credit-card"></i> Thông tin thanh toán</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('cart.process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Họ tên người nhận</label>
                            <input type="text" name="buyer_name" class="form-control" required
                                value="{{ Auth::user()->name ?? old('buyer_name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng</label>
                            <textarea name="address" class="form-control" rows="3"
                                required>{{ old('address') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phương thức thanh toán</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Chọn phương thức thanh toán</option>
                                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                                <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                <option value="momo">Ví MoMo</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Đặt hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-cart-check"></i> Đơn hàng của bạn</h3>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                {{ $item->product->name }}
                                <small class="text-muted d-block">{{ $item->quantity }} x
                                    ${{ number_format($item->product->price, 2) }}</small>
                            </div>
                            <div>${{ number_format($item->product->price * $item->quantity, 2) }}</div>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <div>Tổng cộng:</div>
                        <div>${{ number_format($totalPrice, 2) }}</div>
                    </div>

                    @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <div>Giảm giá (11%):</div>
                            <div>-${{ number_format($discount, 2) }}</div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <div><strong>Thành tiền:</strong></div>
                            <div><strong>${{ number_format($finalPrice, 2) }}</strong></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection