@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
            <h2 class="mt-3">Cảm ơn bạn đã đặt hàng!</h2>
            <p class="lead">Đơn hàng #{{ $order->id }} của bạn đã được xác nhận.</p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4><i class="bi bi-info-circle"></i> Chi tiết đơn hàng</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Thông tin người nhận</h5>
                    <p><strong>Họ tên:</strong> {{ $order->buyer_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Thông tin đơn hàng</h5>
                    <p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Phương thức thanh toán:</strong> 
                        @switch($order->payment_method)
                            @case('cod')
                                Thanh toán khi nhận hàng (COD)
                                @break
                            @case('bank_transfer')
                                Chuyển khoản ngân hàng
                                @break
                            @case('momo')
                                Ví MoMo
                                @break
                        @endswitch
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderDetails as $detail)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($detail->product->image)
                                            <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                 alt="{{ $detail->product->name }}"
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 class="me-2">
                                        @endif
                                        {{ $detail->product->name }}
                                    </div>
                                </td>
                                <td>${{ number_format($detail->price, 2) }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>${{ number_format($detail->price * $detail->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                            <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                        </tr>
                        @if($order->discount > 0)
                            <tr>
                                <td colspan="3" class="text-end text-success">
                                    <strong>Giảm giá (11%):</strong>
                                </td>
                                <td class="text-success">
                                    <strong>-${{ number_format($order->discount, 2) }}</strong>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end"><strong>Thành tiền:</strong></td>
                            <td><strong>${{ number_format($order->final_price, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('welcome') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-cart"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
