@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="bi bi-info-circle"></i> Chi tiết đơn hàng #{{ $order->id }}</h3>
            <div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('admin.orders.print-invoice', $order) }}" 
                   class="btn btn-primary" 
                   target="_blank">
                    <i class="bi bi-printer"></i> In hóa đơn
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Thông tin khách hàng</h5>
                    <p><strong>Tên:</strong> {{ $order->buyer_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Thông tin đơn hàng</h5>
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
                            @default
                                {{ $order->payment_method }}
                        @endswitch
                    </p>
                    <form action="{{ route('admin.orders.update-status', $order) }}" 
                          method="POST" 
                          class="d-flex align-items-center">
                        @csrf
                        @method('PATCH')
                        <label class="me-2"><strong>Trạng thái:</strong></label>
                        <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                Chờ xử lý
                            </option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                Đang xử lý
                            </option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                Đang giao
                            </option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                Đã giao
                            </option>
                        </select>
                    </form>
                </div>
            </div>

            <h5>Chi tiết sản phẩm</h5>
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
                                        <!-- @if($detail->product->image)
                                            <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                 alt="{{ $detail->product->name }}"
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 class="me-2">
                                        @endif -->
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
        </div>
    </div>
</div>
@endsection