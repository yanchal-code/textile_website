@extends('front.includes.layout')
@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ion-rangeslider@2.3.1/css/ion.rangeSlider.min.css" />
@endsection

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="display-3 h1 mb-3 animated slideInDown">Products</div>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">Products</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container-fluid">

            <div class="row g-0 gx-5 align-items-end">
                <div class="col-lg-12">
                    <div class="section-header text-start mb-5 wow fadeInUp" data-wow-delay="0.1s" style="">
                        <div class="display-5 d-flex justify-content-between h1 gap-3 mb-3">
                            <div>
                                Our Products
                            </div>
                            <div class="d-flex">
                                <div>
                                    <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas"
                                        data-bs-target="#filterShop" aria-controls="filterShop">
                                        <i class="bi bi-funnel-fill"></i> Filter
                                    </button>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="sortDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        @if ($sortBy == 'latest')
                                            Latest
                                        @elseif($sortBy == 'price_desc')
                                            Price, high to low
                                        @elseif($sortBy == 'price_asc')
                                            Price, low to high
                                        @else
                                            Latest
                                        @endif
                                    </button>

                                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                        <li>
                                            <a class="dropdown-item {{ $sortBy == 'latest' ? 'active' : '' }}"
                                                href="javascript:void(0)" data-sort-value="latest">
                                                Latest
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ $sortBy == 'price_desc' ? 'active' : '' }}"
                                                href="javascript:void(0)" data-sort-value="price_desc">
                                                Price, high to low
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ $sortBy == 'price_asc' ? 'active' : '' }}"
                                                href="javascript:void(0)" data-sort-value="price_asc">
                                                Price, low to high
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <script>
                                    document.querySelectorAll('.dropdown-item').forEach(item => {
                                        item.addEventListener('click', function() {
                                            let sortValue = this.getAttribute('data-sort-value');
                                            let url = new URL(window.location.href);
                                            url.searchParams.set('sort', sortValue);
                                            window.location.href = url.toString(); // Reload with updated URL
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                        <p>Browse our fresh and organic products from different categories.</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @if (count($products) > 0)
                    @foreach ($products as $key => $product)
                        @include('front.partials.product', ['product' => $product])
                    @endforeach
                @else
                    <div class="text-center fw-bold">No Data Found</div>
                @endif
            </div>
        </div>
    </div>
    <!-- Product End -->
    <!-- Firm Visit Start -->
    <div class="container-fluid bg-primary bg-icon mt-5 py-6">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-md-7 wow fadeIn" data-wow-delay="0.1s">
                    <div class="display-5 h1 text-white mb-3">Visit Our Organic Farm</div>
                    <p class="text-white mb-0">
                        Experience the freshness of nature firsthand! We welcome you to explore our organic farm,
                        where every fruit and vegetable is grown with love and care. Discover how we cultivate
                        chemical-free produce, learn about our sustainable farming practices, and see where your
                        healthy food truly comes from. A visit to our farm is not just a tour — it’s a journey into
                        natural living and wellness.
                    </p>
                </div>
                <div class="col-md-5 text-md-end wow fadeIn" data-wow-delay="0.5s">
                    <a class="btn btn-lg btn-secondary rounded-pill py-3 px-5"
                        href="{{ route('front.page', 'contact-us') }}">
                        Visit Now
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Firm Visit End -->
    <!-- Testimonial Start -->
    <div class="container-fluid bg-light bg-icon py-6 mb-5">
        <div class="container">
            <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s"
                style="max-width: 500px;">
                <div class="display-5 h1 mb-3">Customer Reviews</div>
                <p>peoples who enjoy the freshness and goodness of our organic products every day.</p>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                @foreach (getTestimonials() as $review)
                    <div class="testimonial-item position-relative bg-white p-5 mt-4">
                        <i class="fa fa-quote-left fa-3x text-primary position-absolute top-0 start-0 mt-n4 ms-5"></i>
                        <p class="mb-4">“{{ $review->comment }}”</p>
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 rounded-circle" src="{{ asset(config('settings.logo')) }}"
                                alt="{{ $review->username }}">
                            <div class="ms-3">
                                <h5 class="mb-1">{{ $review->username }}</h5>
                                <span>{{ $review->title ?? 'Customer' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Testimonial End -->

    <!-- Filter Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterShop" aria-labelledby="filterShopLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title d-flex align-items-center gap-2" id="filterShopLabel">
                <i class="bi bi-funnel-fill"></i> Filter
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <!-- Categories Widget -->
            <div class="accordion" id="accordionCategories">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingCategories">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories">
                            Product Categories
                        </button>
                    </h2>
                    <div id="collapseCategories" class="accordion-collapse collapse show"
                        aria-labelledby="headingCategories" data-bs-parent="#accordionCategories">
                        <div class="accordion-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach (categories() as $category)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#sub-{{ $category->id }}"
                                            aria-expanded="false">
                                            <span>{{ $category->name }}</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <div id="sub-{{ $category->id }}" class="collapse ps-3">
                                            <ul class="list-group list-group-flush">
                                                @foreach ($category->subCategories as $subCategory)
                                                    <li class="list-group-item">
                                                        <div class="d-flex justify-content-between align-items-center"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#leaf-{{ $subCategory->id }}"
                                                            aria-expanded="false">
                                                            <span>{{ $subCategory->name }}</span>
                                                            <i class="bi bi-chevron-down"></i>
                                                        </div>
                                                        <div id="leaf-{{ $subCategory->id }}" class="collapse ps-3">
                                                            <ul class="list-group list-group-flush">
                                                                @foreach ($subCategory->leafCategories as $leafCategory)
                                                                    <li class="list-group-item">
                                                                        <a
                                                                            href="{{ route('front.shop', [$category->slug, $subCategory->slug, $leafCategory->slug]) }}">
                                                                            {{ $leafCategory->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Form -->
            <form action="{{ url()->current() }}" method="GET" id="facet-filter-form" class="mt-4">

                <!-- Price Filter -->
                <div class="accordion mb-3" id="accordionPrice">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPrice">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapsePrice" aria-expanded="true" aria-controls="collapsePrice">
                                Price Range
                            </button>
                        </h2>
                        <div id="collapsePrice" class="accordion-collapse collapse show" aria-labelledby="headingPrice"
                            data-bs-parent="#accordionPrice">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <div class="range-slider-container">
                                        <input type="text" class="js-range-slider" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Apply Filters Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>

            </form>
        </div>
    </div>
    <script>
        $('#nav-link_product').addClass('active');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/ion-rangeslider@2.3.1/js/ion.rangeSlider.min.js"></script>

    <script>
        var rangeSlider = $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: 0,
            max: {{ maxPrice() }},
            from: {{ $priceMin }},
            step: 1,
            to: {{ $priceMax }},
            skin: "round",
            max_postfix: "+",
            prefix: "{{ config('settings.currency_symbol') }} ",


        });
        var slider = $(".js-range-slider").data("ionRangeSlider");

        $(".range-slider").mouseleave(function() {
            slider.dragging = false;
        });

        document.getElementById('facet-filter-form').addEventListener('submit', function(event) {
            event.preventDefault();
            let params = new URLSearchParams();
            let priceMin = slider.result.from;
            let priceMax = slider.result.to;
            if (priceMax) {
                params.append('price_min', priceMin);
                params.append('price_max', priceMax);
            }


            window.location.href = `${window.location.pathname}?${params.toString()}`;
        });
    </script>
    <script>
        $('#nav-link_product').addClass('active');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/ion-rangeslider@2.3.1/js/ion.rangeSlider.min.js"></script>

    <script>
        var rangeSlider = $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: 0,
            max: {{ maxPrice() }},
            from: {{ $priceMin }},
            step: 1,
            to: {{ $priceMax }},
            skin: "round",
            max_postfix: "+",
            prefix: "{{ config('settings.currency_symbol') }} ",


        });
        var slider = $(".js-range-slider").data("ionRangeSlider");

        $(".range-slider").mouseleave(function() {
            slider.dragging = false;
        });

        document.getElementById('facet-filter-form').addEventListener('submit', function(event) {
            event.preventDefault();
            let params = new URLSearchParams();
            let priceMin = slider.result.from;
            let priceMax = slider.result.to;
            if (priceMax) {
                params.append('price_min', priceMin);
                params.append('price_max', priceMax);
            }

            window.location.href = `${window.location.pathname}?${params.toString()}`;
        });
    </script>

    <style>
        #shop_content {
            transition: all 0.4s ease;
        }
    </style>
    <div class="text-center"><button class="btn btn-primary tf-btn mb-3" id="toggleButton">View More</button></div>

    <div class="container-fluid d-none" id="shop_content">

    </div>

    <script>
        $(document).ready(function() {
            $('#toggleButton').click(function() {
                $('#shop_content').toggleClass('d-none');

                if ($('#shop_content').hasClass('d-none')) {
                    $(this).text('View More');
                } else {
                    $(this).text('View Less');
                }
            });
        });
    </script>
@endsection


@section('scripts')
    <script></script>
@endsection
