<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .mt-100{
        margin-top: 100px;
    }
</style>

@extends('layouts.app')

@section('title', 'Báo cáo thống kê')

@section('content')
<div class="container">
    <h1 class="mb-4">Báo cáo thống kê</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tổng số đơn hàng</h5>
                    <p class="card-text display-4">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tổng số khách hàng</h5>
                    <p class="card-text display-4">{{ $totalCustomers }}</p>
                </div>
            </div>
        </div>
    </div>

    <h3>Doanh thu theo từng danh mục</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Danh mục</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryRevenue as $revenue)
                <tr>
                    <td>{{ $revenue->category_name }}</td>
                    <td>{{ number_format($revenue->total_revenue, 0, ',', '.') }} $</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Doanh thu theo ngày</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Ngày</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByDate as $revenue)
                <tr>
                    <td>{{ $revenue->date }}</td>
                    <td>{{ number_format($revenue->total_revenue, 0, ',', '.') }} $</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Doanh thu theo tháng</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByMonth as $revenue)
                <tr>
                    <td>{{ $revenue->month }}/{{ $revenue->year }}</td>
                    <td>{{ number_format($revenue->total_revenue, 0, ',', '.') }} $</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Doanh thu theo năm</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Năm</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByYear as $revenue)
                <tr>
                    <td>{{ $revenue->year }}</td>
                    <td>{{ number_format($revenue->total_revenue, 0, ',', '.') }} $</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Doanh thu theo phương thức thanh toán</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Phương thức thanh toán</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByPaymentMethod as $revenue)
                <tr>
                    <td>
                        @switch($revenue->payment_method)
                            @case('cod')
                                Thanh toán khi nhận hàng (COD)
                                @break
                            @case('bank_transfer')
                                Chuyển khoản ngân hàng
                                @break
                            @case('vnpay')
                                Ví VNPay
                                @break
                            @default
                                {{ $revenue->payment_method }}
                        @endswitch
                    </td>
                    <td>{{ number_format($revenue->total_revenue, 0, ',', '.') }} $</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
<h3 class="mt-100">Biểu đồ doanh thu theo tháng</h3>
<canvas id="revenueChart" width="400" height="200"></canvas>

<h3 class="mt-100">Biểu đồ tròn doanh thu theo danh mục</h3>
<canvas id="categoryRevenueChart" width="400" height="200"></canvas>

<h3 class="mt-100">Biểu đồ cột doanh thu theo phương thức thanh toán</h3>
<canvas id="paymentMethodRevenueChart" width="400" height="200"></canvas>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var chartData = @json($fullRevenueByMonth);
    
    var labels = chartData.map(function(item) {
        return item.month + '/' + item.year;
    });
    
    var data = chartData.map(function(item) {
        return item.total_revenue;
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu ($)',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'USD' }).format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'USD' }).format(context.raw);
                        }
                    }
                }
            }
        }
    });

    // Category Revenue Pie Chart
    var categoryCtx = document.getElementById('categoryRevenueChart').getContext('2d');
    var categoryData = @json($categoryRevenue);
    
    new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: categoryData.map(item => item.category_name),
            datasets: [{
                data: categoryData.map(item => item.total_revenue),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Doanh thu theo danh mục'
                }
            }
        }
    });

    // Payment Method Revenue Bar Chart
    var paymentMethodCtx = document.getElementById('paymentMethodRevenueChart').getContext('2d');
    var paymentMethodData = @json($revenueByPaymentMethod);
    
    new Chart(paymentMethodCtx, {
        type: 'bar',
        data: {
            labels: paymentMethodData.map(item => {
                switch(item.payment_method) {
                    case 'cod': return 'Thanh toán khi nhận hàng (COD)';
                    case 'bank_transfer': return 'Chuyển khoản ngân hàng';
                    case 'vnpay': return 'Ví VNPay';
                    default: return item.payment_method;
                }
            }),
            datasets: [{
                label: 'Doanh thu ($)',
                data: paymentMethodData.map(item => item.total_revenue),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'USD' }).format(value);
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Doanh thu theo phương thức thanh toán'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'USD' }).format(context.raw);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
