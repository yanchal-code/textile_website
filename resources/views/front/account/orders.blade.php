@extends('front.includes.layout')
@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">My Orders</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li class="current">orders</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Title -->

    <!-- page-cart -->
    <section class="flat-spacing-11">
        <div class="container-fluid px-lg-5">
            <div class="row">
                <div class="col-lg-3">
                    <div class="wrap-sidebar-account sticky-top">
                        <ul class="my-account-nav">
                            <li><a href="{{ route('account.profile') }}" class="my-account-nav-item ">Dashboard</a></li>
                            <li><span class="my-account-nav-item active">Orders</span></li>
                            <li><a href="{{ route('account.wishlist') }}" class="my-account-nav-item">Wishlist</a></li>
                            <li><a href="{{ route('account.logout') }}" class="my-account-nav-item">Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="my-account-content account-order py-4">
                        <div class="wrap-account-order table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-dark text-uppercase">
                                    <tr>
                                        <th scope="col" class="fw-semibold">Order</th>
                                        <th scope="col" class="fw-semibold">Date</th>
                                        <th scope="col" class="fw-semibold">Method</th>
                                        <th scope="col" class="fw-semibold">Status</th>
                                        <th scope="col" class="fw-semibold">Total</th>
                                        <th scope="col" class="fw-semibold text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr class="tf-order-item">
                                            <td>#{{ $order->orderId }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y') }}</td>
                                            <td>{{ $order->payment_gateway }}</td>
                                            <td>{{ $order->status }}</td>
                                            <td>{{ $order->currency }} {{ number_format($order->grand_total, 2) }} for
                                                {{ $order->items->count() }} items</td>
                                            <td class="text-center">
                                                <a href="{{ route('account.orderDetail', $order->id) }}"
                                                    class="btn btn-primary btn-sm rounded-0 px-3 animate-hover-btn">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- page-cart -->
@endsection
