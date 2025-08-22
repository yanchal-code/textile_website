@extends('front.includes.layout')
@section('content')
   <div class="page-title light-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">My WIshlist</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li class="current">wishlist</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Section Product -->
    <section class="flat-spacing-2">
        <div class="container-fluid px-lg-5">
            <div class="row">
                <div class="col-lg-3">
                    <div class="wrap-sidebar-account sticky-top">
                        <ul class="my-account-nav">
                            <li><a href="{{ route('account.profile') }}" class="my-account-nav-item">Dashboard</a>
                            </li>
                            <li><a href="{{ route('account.orders') }}" class="my-account-nav-item">Orders</a></li>
                            <li><a href="{{ route('account.wishlist') }}" class="my-account-nav-item active">Wishlist</a>
                            </li>
                            <li><a href="{{ route('account.logout') }}" class="my-account-nav-item">Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="grid-layout wrapper-shop row">

                        @foreach ($products as $product)
                            <div class="col-lg-3 col-md-4 col-sm-6 pb-1 mb-3">
                                <div class="product-item bg-light mb-4">
                                    <figure>
                                        <a href="{{ route('front.product', $product->slug) }}" title="{{ $product->name }}">
                                            <img class="img-fluid w-100" style="height: 280px;"
                                                src="{{ asset($product->defaultImage->image ?? $product->images->first()->image) }}"
                                                alt="{{ $product->name }}" class="tab-image">
                                        </a>
                                    </figure>

                                    <div class="d-flex flex-column text-center">
                                        <h6 class="fs-6 fw-normal product-name">{{ $product->name }}</h6>

                                        <div class="d-flex justify-content-center align-items-center pb-2">

                                            <span class="mr-1 text-primary ">{{ config('settings.currency_symbol') }}
                                                {{ $product->price }}</span>

                                            @if ($product->compare_price > $product->price)
                                                <del class="fw-semibold">{{ config('settings.currency_symbol') }}
                                                    {{ $product->compare_price }}</del>
                                            @endif

                                            @if ($product->compare_price > $product->price)
                                                <span
                                                    class="badge ml-1 border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">
                                                    {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                                                    OFF
                                                </span>
                                            @endif
                                        </div>
                                        <div class="button-area p-2 pt-0">
                                            <div class="row g-1 mt-2">

                                                <div class="col-7">

                                                    <button data-bs-id="{{ $product->sku }}"
                                                        class="btn btn-primary rounded-1 p-2 fs-7 btn-cart quick-add">
                                                        <svg width="18" height="18">
                                                            <use xlink:href="#cart"></use>
                                                        </svg> Add to Cart
                                                    </button>
                                                </div>

                                                <div class="col-2">
                                                    <button onclick="addToWishlist({{ $product->id }})"
                                                        class="btn btn-outline-dark rounded-1 p-2 fs-6">
                                                        <svg width="18" height="18">
                                                            <use xlink:href="#heart"></use>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <!-- pagination -->
            {{ $products->withQueryString()->links('vendor.pagination.custom') }}
        </div>
    </section>
    <!-- /Section Product -->
@endsection
