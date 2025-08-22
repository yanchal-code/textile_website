@extends('front.includes.layout')
@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ion-rangeslider@2.3.1/css/ion.rangeSlider.min.css" />
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('front-assets/js/nouislider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('front-assets/js/shop.js') }}"></script>
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
    <!-- Page Header End -->
    <!-- Section Product -->
    {{-- <div class="container-fluid px-lg-5">
        <div class="d-flex justify-content-between my-4">

            <div class="">
                <button class="btn btn-secondary me-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#filterShop" aria-controls="filterShop">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>

            </div>

            <div class="btn btn-secondary">
                <div class="tf-dropdown-sort" data-bs-toggle="dropdown">
                    <div class="btn-select">
                        <span class="text-sort-value">
                            @if ($sortBy == 'latest')
                                Latest
                            @elseif($sortBy == 'price_desc')
                                Price, high to low
                            @elseif($sortBy == 'price_asc')
                                Price, low to high
                            @else
                                Latest
                            @endif
                        </span>
                        <span class="icon icon-arrow-down"></span>
                    </div>
                    <div class="dropdown-menu p-2">


                        <div class="select-item {{ $sortBy == 'latest' ? 'active' : '' }}" data-sort-value="latest">
                            <span class="text-value-item">Latest</span>
                        </div>
                        <div class="select-item {{ $sortBy == 'price_desc' ? 'active' : '' }}" data-sort-value="price_desc">
                            <span class="text-value-item">Price, high to low</span>
                        </div>
                        <div class="select-item {{ $sortBy == 'price_asc' ? 'active' : '' }}" data-sort-value="price_asc">
                            <span class="text-value-item">Price, low to high</span>
                        </div>

                    </div>
                </div>
            </div>



            <script>
                document.querySelectorAll('.select-item').forEach(item => {
                    item.addEventListener('click', function() {
                        let sortValue = this.getAttribute('data-sort-value');
                        let url = new URL(window.location.href);
                        url.searchParams.set('sort', sortValue);
                        window.location.href = url.toString(); // Refresh with updated URL
                    });
                });
            </script>

        </div>


        <!-- Category Product List Section -->
        <section id="category-product-list" class="category-product-list section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">


                    @if ($products->isNotEmpty())
                        @foreach ($products as $product)
                            <!-- Product 1 -->
                            <div class="col-lg-3 col-md-4 col-6 pb-1 mb-3">
                                <div class="product-box">
                                    <a href="{{ route('front.product', $product->slug) }}">
                                        <div class="product-thumb">

                                            @if ($product->compare_price > $product->price)
                                                <span class="product-label">
                                                    {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                                                    OFF
                                                </span>
                                            @endif

                                            <img src="{{ asset($product->defaultImage->image ?? $product->images->first()->image) }}"
                                                alt="{{ $product->alt_image_text }}" class="img-fluid main-img">

                                            <div class="product-overlay">
                                                <div class="product-quick-actions">
                                                    <button onclick="addToWishlist({{ $product->id }})" type="button"
                                                        class="quick-action-btn">
                                                        <i class="bi bi-heart"></i>
                                                    </button>
                                                </div>
                                                <div class="add-to-cart-container">
                                                    <button type="button" data-bs-id="{{ $product->sku }}"
                                                        class="add-to-cart-btn btn-cart quick-add">Add to Cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="product-content">
                                        <div class="product-details">
                                            <h3 class="product-title"><a
                                                    href="{{ route('front.product', $product->slug) }}">{{ $product->name }}</a>
                                            </h3>
                                            <div class="product-price text-center">
                                                <span>{{ config('settings.currency_symbol') }}
                                                    {{ $product->price }}</span>
                                                <span><del>
                                                        {{ $product->compare_price }}</del></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- End Product 1 -->
                        @endforeach
                    @else
                        <div class="col-12 py-5 text-center fw-bold">
                            No Products Found.
                        </div>
                    @endif





                </div>

            </div>

        </section><!-- /Category Product List Section -->

        <section id="category-pagination" class="category-pagination section">

            <div class="container">
                {{ $products->withQueryString()->links('pagination::custom') }}
            </div>
        </section>



    </div>

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

                <!-- Brand Filter -->
                <div class="accordion mb-3" id="accordionBrand">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingBrand">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseBrand" aria-expanded="true" aria-controls="collapseBrand">
                                Brand
                            </button>
                        </h2>
                        <div id="collapseBrand" class="accordion-collapse collapse show" aria-labelledby="headingBrand"
                            data-bs-parent="#accordionBrand">
                            <div class="accordion-body">
                                <ul class="list-unstyled">
                                    @foreach ($brands as $brand)
                                        <li class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="{{ $brand->id }}"
                                                id="brand{{ $brand->id }}" name="brands[]">
                                            <label class="form-check-label" for="brand{{ $brand->id }}">
                                                {{ ucfirst($brand->name) }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
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
    </div> --}}



        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-0 gx-5 align-items-end">
                    <div class="col-lg-6">
                        <div class="section-header text-start mb-5 wow fadeInUp" data-wow-delay="0.1s"
                            style="max-width: 500px;">
                            <div class="display-5 h1 mb-3">Our Products</div>
                            <p>Browse our fresh and organic products from different categories.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                        <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
                            @foreach (getProductsBySubCategory() as $key => $sub)
                                <li class="nav-item me-2">
                                    <a class="btn btn-outline-primary border-2 {{ $loop->first ? 'active' : '' }}"
                                        data-bs-toggle="pill" href="#category-{{ $sub->id }}">
                                        {{ $sub->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    @foreach (getProductsBySubCategory() as $key => $sub)
                        <div id="category-{{ $sub->id }}"
                            class="tab-pane fade show p-0 {{ $loop->first ? 'active' : '' }}">
                            <div class="row g-4">
                                @foreach ($sub->products as $product)
                                    @include('front.partials.product', ['product' => $product])
                                @endforeach


                            </div>
                        </div>
                    @endforeach
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
        <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <div class="display-5 h1 mb-3">Customer Reviews</div>
            <p>peoples who enjoy the freshness and goodness of our organic products every day.</p>
        </div>
        <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">

            <div class="testimonial-item position-relative bg-white p-5 mt-4">
                <i class="fa fa-quote-left fa-3x text-primary position-absolute top-0 start-0 mt-n4 ms-5"></i>
                <p class="mb-4">“The vegetables I received were incredibly fresh and full of flavor.
                    It feels so good knowing my family is eating chemical-free produce straight from the farm.”</p>
                <div class="d-flex align-items-center">
                    <img class="flex-shrink-0 rounded-circle" src="assets/img/testimonial-1.jpg" alt="Customer">
                    <div class="ms-3">
                        <h5 class="mb-1">Anjali Mehra</h5>
                        <span>Homemaker</span>
                    </div>
                </div>
            </div>

            <div class="testimonial-item position-relative bg-white p-5 mt-4">
                <i class="fa fa-quote-left fa-3x text-primary position-absolute top-0 start-0 mt-n4 ms-5"></i>
                <p class="mb-4">“I love how easy it is to order online. The fruits taste just like they should—sweet,
                    juicy, and natural. This is my go-to place for organic shopping.”</p>
                <div class="d-flex align-items-center">
                    <img class="flex-shrink-0 rounded-circle" src="assets/img/testimonial-2.jpg" alt="Customer">
                    <div class="ms-3">
                        <h5 class="mb-1">Rahul Sharma</h5>
                        <span>IT Professional</span>
                    </div>
                </div>
            </div>

            <div class="testimonial-item position-relative bg-white p-5 mt-4">
                <i class="fa fa-quote-left fa-3x text-primary position-absolute top-0 start-0 mt-n4 ms-5"></i>
                <p class="mb-4">“I visited the farm last month and was amazed by the clean and sustainable methods
                    they use. It made me trust the products even more.”</p>
                <div class="d-flex align-items-center">
                    <img class="flex-shrink-0 rounded-circle" src="assets/img/testimonial-3.jpg" alt="Customer">
                    <div class="ms-3">
                        <h5 class="mb-1">Rajiv Kapoor</h5>
                        <span>Nutritionist</span>
                    </div>
                </div>
            </div>

            <div class="testimonial-item position-relative bg-white p-5 mt-4">
                <i class="fa fa-quote-left fa-3x text-primary position-absolute top-0 start-0 mt-n4 ms-5"></i>
                <p class="mb-4">“The quality of the organic grains and pulses is unmatched.
                    You can truly taste the difference compared to store-bought items.”</p>
                <div class="d-flex align-items-center">
                    <img class="flex-shrink-0 rounded-circle" src="assets/img/testimonial-4.jpg" alt="Customer">
                    <div class="ms-3">
                        <h5 class="mb-1">Sneha Patel</h5>
                        <span>Entrepreneur</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Testimonial End -->

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

            document.querySelectorAll('input[name="brands[]"]:checked').forEach(function(brand) {
                params.append('brands[]', brand.value);
            });

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
