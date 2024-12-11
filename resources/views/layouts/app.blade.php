<!-- views/layout/app.blade.php -->
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop xe máy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --bs-body-bg: #1a1d20;
            --bs-body-color: #e9ecef;
        }

        .navbar {
            background-color: #212529 !important;
        }

        .card {
            background-color: #212529;
            border-color: #373b3e;
        }

        .table {
            color: #e9ecef;
        }

        .modal-content {
            background-color: #212529;
        }

        .form-control,
        .form-select {
            background-color: #2b3035;
            border-color: #373b3e;
            color: #e9ecef;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #2b3035;
            border-color: #0d6efd;
            color: #e9ecef;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .dropdown-menu {
            background-color: #212529;
            border-color: #373b3e;
        }

        .dropdown-item {
            color: #e9ecef;
        }

        .dropdown-item:hover {
            background-color: #2b3035;
            color: #fff;
        }

        .page-link {
            background-color: #212529;
            border-color: #373b3e;
        }

        .alert {
            background-color: #2b3035;
            border-color: #373b3e;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <i class="bi bi-car-front"></i> HoanShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                                    <i class="bi bi-tags"></i> Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.products.index') }}">
                                    <i class="bi bi-car-front-fill"></i> Products
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                                    <i class="bi bi-cart3"></i> Orders
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                                                <a class="nav-link" href="{{ route('admin.reports.index') }}">
                                                                    <i class="bi bi-graph-up"></i> Reports
                                                                </a>
                                                            </li> -->
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cart.index') }}">
                                    <i class="bi bi-cart3"></i> Cart
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3"></i> Cart
                            </a>
                        </li>
                    @endauth
                    @auth
                        @if(Auth::user()->role === 'user')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('my-orders') }}">
                                    <i class="bi bi-box-seam"></i> Đơn hàng của tôi
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>