@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-box-seam"></i> Đơn hàng của tôi</h3>
        </div>
        <div class="card-body">
            @if($orders->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                    <p class="mt-3">Bạn chưa có đơn hàng nào</p>
                    <a href="{{ route('welcome') }}" class="btn btn-primary">
                        <i class="bi bi-cart"></i> Mua sắm ngay
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>${{ number_format($order->final_price, 2) }}</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info">Đang xử lý</span>
                                                @break
                                            @case('shipped')
                                                <span class="badge bg-primary">Đang giao</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge bg-success">Đã giao</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('my-orders.show', $order) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection