@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Giỏ hàng của bạn</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if($cartItems->count() > 0)
        <form action="{{ route('cart.updateAll') }}" method="POST">
            @csrf
            @method('PATCH')
            <table class="table">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Danh mục</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php    $totalCart = 0; @endphp
                    @foreach($cartItems as $item)
                                @php
                                    $totalItem = $item->quantity * $item->product->price;
                                    $totalCart += $totalItem;
                                @endphp
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>
                                        <input type="number" name="quantities[{{ $item->product_id }}]" value="{{ $item->quantity }}"
                                            min="1" style="width: 60px;">
                                    </td>
                                    <td>${{ number_format($item->product->price) }}</td>
                                    <td>{{ $item->product->category->name }}</td>
                                    <td>${{ number_format($totalItem) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-item"
                                            data-product-id="{{ $item->product_id }}">Xoá</button>
                                    </td>
                                </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Tổng tiền giỏ hàng:</strong></td>
                        <td><strong>${{ number_format($totalCart) }}</strong></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
            <button type="submit" class="btn btn-primary">Cập nhật giỏ hàng</button>
            <a href="{{ route('welcome') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
        </form>

        <form id="remove-form" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const removeButtons = document.querySelectorAll('.remove-item');
                const removeForm = document.getElementById('remove-form');

                removeButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const productId = this.getAttribute('data-product-id');
                        removeForm.action = "{{ route('cart.remove', '') }}/" + productId;
                        removeForm.submit();
                    });
                });
            });
        </script>
    @else
        <p>Giỏ hàng của bạn trống.</p>
    @endif
</div>
@endsection