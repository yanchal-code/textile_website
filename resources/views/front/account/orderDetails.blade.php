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

                    <div class="order-head d-flex align-items-center gap-3 p-3 border rounded my-3">

                        <div class="content flex-grow-1">
                            @if ($order->status == 'pending')
                                <span class="badge bg-secondary text-white">In Progress</span>
                            @elseif ($order->status == 'viewed')
                                <span class="badge bg-info text-white">Viewed by Admin</span>
                            @elseif ($order->status == 'shipped')
                                <span class="badge bg-warning text-white">Shipped</span>
                            @elseif ($order->status == 'cancelled')
                                <span class="badge bg-danger">Canceled</span>
                            @else
                                <span class="badge bg-success">Delivered</span>
                            @endif

                            <h6 class="mt-2 fw-semibold mb-0">Order #{{ $order->orderId }}</h6>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div>
                                <div class="text-muted fw-semibold">Total Items</div>
                                <div class="fs-5 fw-bold mt-1">{{ $order->items->count() }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div>
                                <div class="text-muted fw-semibold">Courier</div>
                                <div class="fs-5 fw-bold mt-1 text-truncate" style="max-width: 100%;">
                                    @foreach ($order->items as $item)
                                        {{ $item->sku }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div>
                                <div class="text-muted fw-semibold">Order Time</div>
                                <div class="fs-5 fw-bold mt-1">{{ $order->created_at->format('d F Y, H:i:s') }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div>
                                <div class="text-muted fw-semibold">Address</div>
                                <div class="fs-5 fw-bold mt-1">{{ $order->address }}</div>
                            </div>
                        </div>
                    </div>

                    <style>
                        .widget-tabs .nav-tabs .nav-link {
                            font-weight: 600;
                            color: #333;
                            border: none;
                            border-bottom: 3px solid transparent;
                            transition: border-color 0.3s ease;
                        }

                        .widget-tabs .nav-tabs .nav-link.active {
                            border-bottom-color: #0d6efd;
                            /* Bootstrap primary */
                            color: #0d6efd;
                        }

                        .widget-tabs .timeline-badge {
                            width: 18px;
                            height: 18px;
                            margin-top: 6px;
                        }

                        .widget-tabs .timeline li {
                            border-left: 3px solid #0d6efd;
                            padding-left: 12px;
                            margin-bottom: 15px;
                            position: relative;
                        }

                        .widget-tabs .timeline li::before {
                            content: "";
                            position: absolute;
                            left: -9px;
                            top: 6px;
                            width: 12px;
                            height: 12px;
                            background-color: #0d6efd;
                            border-radius: 50%;
                            border: 3px solid white;
                        }

                        .widget-tabs .btn-write-review {
                            white-space: nowrap;
                        }

                        @media (max-width: 576px) {
                            .widget-tabs .d-flex {
                                flex-direction: column !important;
                                align-items: flex-start !important;
                            }

                            .widget-tabs .btn-write-review {
                                margin-top: 10px !important;
                            }
                        }
                    </style>

                    <div class="widget-tabs border rounded p-3">
                        <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="history-tab" data-bs-toggle="tab"
                                    data-bs-target="#history" type="button" role="tab" aria-controls="history"
                                    aria-selected="true">
                                    Order History
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="items-tab" data-bs-toggle="tab" data-bs-target="#items"
                                    type="button" role="tab" aria-controls="items" aria-selected="false">
                                    Item Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="courier-tab" data-bs-toggle="tab" data-bs-target="#courier"
                                    type="button" role="tab" aria-controls="courier" aria-selected="false">
                                    Courier
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="receiver-tab" data-bs-toggle="tab" data-bs-target="#receiver"
                                    type="button" role="tab" aria-controls="receiver" aria-selected="false">
                                    Receiver
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="orderTabsContent">
                            <div class="tab-pane fade show active" id="history" role="tabpanel"
                                aria-labelledby="history-tab">

                                @php
                                    $statusSteps = [
                                        1 => [
                                            'status' => 'pending',
                                            'title' => 'Order Placed',
                                            'date' => $order->created_at,
                                        ],
                                        2 => [
                                            'status' => 'viewed',
                                            'title' => 'Order Confirmed',
                                            'date' => '',
                                        ],
                                        3 => [
                                            'status' => 'shipped',
                                            'title' => 'Product Shipped',
                                            'date' => '',
                                        ],
                                        4 => [
                                            'status' => 'delivered',
                                            'title' => 'Delivered',
                                            'date' => '',
                                        ],
                                    ];

                                @endphp

                                @if ($order->status == 'pending')
                                    @php
                                        $step = 1;
                                        $status = 'In Progress';
                                    @endphp
                                @elseif ($order->status == 'viewed')
                                    @php
                                        $step = 2;
                                        $status = 'Getting Ready';

                                    @endphp
                                @elseif ($order->status == 'shipped')
                                    @php
                                        $step = 3;
                                        $status = 'Order Shipped';
                                    @endphp
                                @elseif($order->status == 'delivered')
                                    @php
                                        $step = 4;
                                        $status = 'Order Delivered';

                                    @endphp
                                @endif


                                @php

                                    $shippmentDetail = !empty($order->shippment_detail) ? $order->shippment_detail : '';
                                    $shippmentParts = explode('|', $shippmentDetail);
                                    $shippmentCompany = $shippmentParts[0] ?? '';
                                    $shippmentTrackingId = $shippmentParts[1] ?? '';
                                @endphp

                                <!-- Your timeline content here -->
                                <ul class="timeline list-unstyled">
                                    @for ($i = 1; $i <= $step; $i++)
                                        <li class="d-flex mb-3 align-items-start">
                                            <span class="badge rounded-circle bg-success me-3 timeline-badge"></span>
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $statusSteps[$i]['title'] }}</h6>
                                                <small class="text-muted">{{ $statusSteps[$i]['date'] }}</small>
                                                @if ($i == 3)
                                                    <p class="mb-0 mt-2"><strong>Courier Service:</strong>
                                                        {{ $shippmentCompany }}</p>
                                                    <p class="mb-0"><strong>Tracking ID:</strong>
                                                        {{ $shippmentTrackingId }}</p>
                                                    <p class="mb-0"><strong>Shipped At:</strong>
                                                        {{ $order->shipped_date }}</p>
                                                @endif
                                            </div>
                                        </li>
                                    @endfor
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="items" role="tabpanel" aria-labelledby="items-tab">
                                @if ($orderItems->isNotEmpty())
                                    @foreach ($orderItems as $item)
                                        @php
                                            $product = getProduct($item->sku);
                                            $image =
                                                $product->defaultImage->image ?? $product->images()->first()->image;
                                        @endphp
                                        <div class="d-flex mb-3 align-items-center border rounded p-2">
                                            <a href="{{ route('front.product', ['slug' => $item->sku, 'sku' => $item->sku]) }}"
                                                class="me-3 flex-shrink-0">
                                                <img src="{{ asset($image) }}" alt="product" class="img-thumbnail"
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                            </a>
                                            <div class="flex-grow-1">
                                                <a href="{{ route('front.product', ['slug' => $item->sku, 'sku' => $item->sku]) }}"
                                                    class="fw-bold text-decoration-none text-dark">{{ $item->name }}</a>
                                                <div>Price: {{ $order->currency }} {{ $item->price }}</div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Offcanvas review form stays the same -->
                                @endif

                                <ul class="list-unstyled mt-4 border-top pt-3">
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Total Price</span>
                                        <span class="fw-semibold">{{ $order->currency }} {{ $order->subtotal }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Total Discounts</span>
                                        <span class="fw-semibold">{{ $order->currency }} {{ $order->discount }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Total Shipping</span>
                                        <span class="fw-semibold">{{ $order->currency }} {{ $order->shipping }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between fw-bold fs-5 border-top pt-2">
                                        <span>Order Total</span>
                                        <span>{{ $order->currency }} {{ $order->grand_total }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="courier" role="tabpanel" aria-labelledby="courier-tab">
                                <p>
                                    Our courier service is dedicated to providing fast, reliable, and secure delivery
                                    solutions tailored to meet your needs. Whether you're sending documents, parcels, or
                                    larger shipments, our team ensures that your items are handled with the utmost care
                                    and delivered on time. With a commitment to customer satisfaction, real-time
                                    tracking, and a wide network of routes, we make it easy for you to send and receive
                                    packages both locally and internationally. Choose our service for a seamless and
                                    efficient delivery experience.
                                </p>
                            </div>

                            <div class="tab-pane fade" id="receiver" role="tabpanel" aria-labelledby="receiver-tab">
                                <p class="text-success fw-semibold">Thank you, your order has been received.</p>
                                <ul class="list-unstyled mt-3">
                                    <li>Order Number: <span class="fw-bold">#{{ $order->orderId }}</span></li>
                                    <li>Date: <span
                                            class="fw-bold">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y, h:ia') }}</span>
                                    </li>
                                    <li>Total: <span class="fw-bold">{{ $order->currency }}
                                            {{ $order->grand_total }}</span></li>
                                    <li>Payment Methods: <span class="fw-bold">{{ $order->payment_type }}</span></li>
                                </ul>
                                @if ($order->order_note)
                                    <div class="bg-light p-3 rounded mt-3">
                                        <strong>Order Note:</strong> {{ $order->order_note }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('.btn-write-review').on('click', function() {
                                let sku = $(this).val();
                                $('#skuHiddenField').val(sku);
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </section>
    <!-- page-cart -->

@endsection
