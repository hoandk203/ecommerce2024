<!-- views/welcome.blade.php -->
<style>
    .card {
        border: none !important;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-size: 24px;
        font-weight: bold;
    }
</style>
@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Welcome to Hoan Store</h1>
    <div class="card-deck">
        @foreach ($products as $product)
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ $product->description }}</p>
                    <!-- <p class="card-text">Quantity: {{ $product->quantity }}</p> -->
                    <p class="card-text">Giá tiền: ${{ number_format($product->price, 2) }}</p>
                    <p class="card-text">Loại sản phẩm: {{ optional($product->category)->name }}</p>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Xem chi tiết</a>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Hiển thị liên kết phân trang -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection