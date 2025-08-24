@extends('front.includes.layout')
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="display-3 h1 mb-3 animated slideInDown">{{ Auth::user()->name }}</div>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                    <li class="breadcrumb-item">My Account</li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">My Wishlist</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Section Product -->
    <section class="flat-spacing-2">
        <div class="container-fluid px-lg-5">
            <div class="row">
                <div class="col-lg-3 mb-4 mb-lg-0 sticky-top top-100">
                    <div class="card shadow-sm border-0 sticky-top">
                        <div class="card-body p-0">
                            <nav class="nav flex-column nav-pill text-start">
                                <a href="{{ route('account.profile') }}"
                                    class=" nav-link {{ request()->routeIs('account.profile') ? ' bg-primary text-white ' : '' }}">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                                <a href="{{ route('account.wishlist') }}"
                                    class="nav-link {{ request()->routeIs('account.wishlist') ? ' bg-primary text-white ' : '' }}">
                                    <i class="bi bi-heart me-2"></i> Wishlist
                                </a>
                                <a href="{{ route('account.logout') }}" class="nav-link text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="grid-layout wrapper-shop row">
                        @if (count($products) > 0)
                            @foreach ($products as $product)
                                @include('front.partials.product', ['product' => $product])
                            @endforeach
                        @else
                            <div class="text-center fw-bold">
                                No Data Found
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            <!-- pagination -->
            {{ $products->withQueryString()->links('vendor.pagination.custom') }}
        </div>
    </section>
    <!-- /Section Product -->
@endsection
