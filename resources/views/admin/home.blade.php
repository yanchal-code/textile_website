@extends('admin.includes.layout')
@section('headTag')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 500px;
            border: 1px solid #ccc;
        }
    </style>
@endsection
@section('content')
    <script>
        $('#nav-item_dashboard').addClass('active');
    </script>
    <div class="container-fluid py-4">
        @if (config('settings.maintenance_mode'))
            <div class="alert alert-warning text-center">
                <strong>Maintenance Mode:</strong> Some features may not work properly.
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-4 g-4 mb-4 text-center">
            @can('manage_inventory')
                <div class="col-lg-3 col-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title">Active Products</h6>
                            <span class="badge bg-success fs-6">{{ number_format($totalProduct) }}</span>
                        </div>
                    </div>

                </div>

                <div class="col-lg-3 col-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title">Products Sold</h6>
                            <span class="badge bg-success fs-6">{{ number_format($totalProductSold) }}</span>
                        </div>
                    </div>
                </div>
            @endcan
            @can('manage_orders')
                <div class="col-lg-3 col-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title">New Orders</h6>
                            <span class="badge bg-danger fs-6">{{ number_format($totalNewOrders) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title">Total Orders</h6>
                            <span class="badge bg-success fs-6">{{ number_format($totalOrders) }}</span>
                        </div>
                    </div>
                </div>
            @endcan

            @can('manage_orders')
                <div class="col-lg-3 col-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title">Total Sales</h6>
                            <span class="badge bg-success fs-6">{{ number_format($totalSales, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title">This Month</h6>
                            <span class="badge bg-warning text-dark fs-6">{{ number_format($totalSalesThisMonth, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title">Last Month</h6>
                            <span class="badge bg-success fs-6">{{ number_format($totalSalesLastMonth, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endcan
            @can('manage_users')
                <div class="col-lg-3 col-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title">Total Users</h6>
                            <span class="badge bg-primary fs-6">{{ number_format($totalUsers) }}</span>
                        </div>
                    </div>
                </div>
            @endcan
        </div>


        <div class="row g-4">
            @can('manage_users')
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">Users</div>
                        <div class="card-body">
                            <p>Active Users: <span class="badge bg-success">{{ $totalUsersActive }}</span></p>
                            <p>Blocked Users: <span class="badge bg-danger">{{ $totalUsersBlocked }}</span></p>
                        </div>
                    </div>
                </div>
            @endcan
            @can('manage_orders')
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">Top Selling Products</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($hotSellingProducts as $product)
                                    <li class="list-group-item">
                                        <strong>{{ $loop->iteration }}.</strong> <a
                                            href="{{ route('front.product', $product->sku) }}">{{ $product->sku }}</a>
                                        <span class="badge bg-success ms-2">{{ $product->total_quantity }} sold</span>
                                        @if ($loop->iteration == 1)
                                            <i class="fas fa-trophy text-warning ms-2"></i>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">Top Customers</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($topCustomers as $customer)
                                    <li class="list-group-item">
                                        <strong>{{ $loop->iteration }}.</strong>
                                        <span class="d-block">{{ $customer->name }} ({{ $customer->email }})</span>
                                        <span
                                            class="text-success">{{ config('settings.currency_symbol') }}{{ number_format($customer->total_grand_total, 2) }}</span>
                                        @if ($loop->iteration == 1)
                                            <i class="fas fa-crown text-warning ms-2"></i>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">Top Recent Orders</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($topRecentOrders as $order)
                                    <li class="list-group-item">
                                        <a href="{{ route('order.detail', $order->id) }}">{{ $order->orderId }}</a>
                                        - <strong>{{ number_format($order->grand_total, 2) }}</strong>
                                        <br>
                                        <small>{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</small>
                                        <br>
                                        <span
                                            class="badge bg-{{ $order->status == 'pending'
                                                ? 'danger'
                                                : ($order->status == 'viewed'
                                                    ? 'info'
                                                    : ($order->status == 'shipped'
                                                        ? 'warning'
                                                        : ($order->status == 'cancelled'
                                                            ? 'danger'
                                                            : 'success'))) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header fw-bold">Top Biggest Orders</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($topOrders as $order)
                                    <li class="list-group-item">
                                        <a href="{{ route('order.detail', $order->id) }}">{{ $order->orderId }}</a>
                                        - <strong>{{ number_format($order->grand_total, 2) }}</strong>
                                        <br>
                                        <small>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</small>
                                        <br>
                                        <span
                                            class="badge bg-{{ $order->status == 'pending' ? 'danger' : ($order->status == 'viewed' ? 'info' : ($order->status == 'shipped' ? 'warning' : ($order->status == 'cancelled' ? 'danger' : 'success'))) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endcan

        </div>
    </div>
@endsection
@section('scripts')
@endsection
