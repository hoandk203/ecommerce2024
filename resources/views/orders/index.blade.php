<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@extends('layouts.app')

<style>
    td{
        align-content: center;
    }
</style>

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-cart3"></i> Quản lý đơn hàng</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <div>{{ $order->buyer_name }}</div>
                                    <small class="text-muted">{{ $order->phone }}</small>
                                </td>
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
                                        @default
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="btn btn-sm btn-info">
                                            Xem
                                        </a>
                                        <a href="{{ route('admin.orders.print-invoice', $order) }}" 
                                           class="btn btn-sm btn-secondary"
                                           target="_blank">
                                            In hóa đơn
                                        </a>
                                        <form action="{{ route('admin.orders.destroy', $order) }}" 
                                              method="POST" 
                                              class="d-inline-block m-0"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm rounded-0 rounded-end-1 btn-danger">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection