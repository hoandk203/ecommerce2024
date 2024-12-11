<!-- views/welcome.blade.php -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    :root {
        --bs-body-bg: #1a1d20;
        --bs-body-color: #e9ecef;
    }

    .card {
        background-color: #212529;
        border-color: #373b3e;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .product-card img {
        height: 200px;
        object-fit: cover;
    }

    .price {
        font-size: 1.25rem;
        font-weight: bold;
        color: #0d6efd;
    }

    .card {
        border: none !important;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-size: 24px;
        font-weight: bold;
    }

    @media (max-width: 960px) {
        .product-row {
            flex-direction: column;
        }

        .product-col {
            width: 100%;
        }
    }

    .product-image-container {
        position: relative;
    }

    .add-to-cart-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: yellowgreen;
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        font-size: 20px;
        /* line-height: 30px;
        text-align: center; */
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.3s;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .add-to-cart-btn:hover {
        opacity: 1;
    }
</style>
@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <!-- <h1 class="mb-4">Welcome to ManhStore</h1>
    <h2>Tìm kiếm sản phẩm</h2>
    <form action="{{ route('welcome') }}" method="GET">
        <div class="form-group">
            <label for="query">Tên sản phẩm:</label>
            <input type="text" class="form-control" id="query" name="query" value="{{ request('query') }}">
        </div>
        <div class="form-group">
            <label for="price_range">Khoảng giá:</label>
            <select class="form-control" id="price_range" name="price_range">
                <option value="">Tất cả</option>
                <option value="0-10" {{ request('price_range') == '0-10' ? 'selected' : '' }}>0$ - 10$</option>
                <option value="10-50" {{ request('price_range') == '10-50' ? 'selected' : '' }}>10$ - 50$</option>
                <option value="50-100" {{ request('price_range') == '50-100' ? 'selected' : '' }}>50$ - 100$</option>
                <option value="100-500" {{ request('price_range') == '100-500' ? 'selected' : '' }}>100$ - 500$</option>
                <option value="500+" {{ request('price_range') == '500+' ? 'selected' : '' }}>Trên 500$</option>
            </select>
        </div>
        <div class="form-group">
            <label for="category">Loại sản phẩm:</label>
            <select class="form-control" id="category" name="category">
                <option value="">Tất cả</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </form>
    <h2>Kết quả tìm kiếm {{ !empty($query) ? "cho " . $query : '' }}</h2> -->
    <h2 class="mb-4">Sản phẩm</h2>
    <div class="row product-row">
        @if($products->isEmpty())
            <p>Không tìm thấy sản phẩm nào.</p>
        @else
            @foreach ($products as $product)
                <div class="col-md-3 mb-4 product-col">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="add-to-cart-btn" title="Thêm vào giỏ hàng"><i
                                        class="fas fa-cart-plus"></i></button>

                            </form>
                            <!-- <div class="product-image-container" style="margin-bottom: 10px">
                                        @if($product->image)
                                            <img src="{{ asset("storage/{$product->image}") }}" alt="{{ $product->name }}"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <span>Không có ảnh</span>
                                        @endif
                                    </div> -->
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <p class="card-text">Giá tiền: {{ number_format($product->price) }} VNĐ</p>
                            <p class="card-text">Loại sản phẩm: {{ optional($product->category)->name }}</p>
                            <a style="display: inline-block" href="{{ route('products.show', $product->id) }}"
                                class="btn btn-primary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <!-- Hiển thị liên kết phân trang -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>