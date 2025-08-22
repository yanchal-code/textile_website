    @php
        use Carbon\Carbon;
    @endphp

    @extends('front.includes.layout')
    @section('head')
        <link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
        <style>
            #sliderImage {
                transition: opacity 0.5s;
            }

            .tf-marquee {
                background-color: #fff8db;
                /* light yellow background, similar to Bootstrap's bg-yellow-200 */
                position: relative;
                white-space: nowrap;
            }

            .wrap-marquee {
                display: inline-flex;
                animation: marqueeScroll 25s linear infinite;
            }

            .marquee-item {
                flex: 0 0 auto;
            }

            .icon svg {
                fill: #f59e0b;
                /* amber-500 from Tailwind for a nice yellow */
                display: block;
            }

            @keyframes marqueeScroll {
                0% {
                    transform: translateX(0%);
                }

                100% {
                    transform: translateX(-50%);
                }
            }
        </style>
    @endsection

    @section('content')
        <!-- Carousel Start -->
        <div class="container-fluid p-0 mb-5 wow fadeIn" data-wow-delay="0.1s">
            <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach (carousel() as $key => $carousel)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img class="w-100" src="{{ asset($carousel->image_path) }}" alt="Image">
                            <div class="carousel-caption">
                                <div class="container">
                                    <div class="row justify-content-start">
                                        <div class="col-lg-7">
                                            <div class="display-2 h1 mb-5 animated slideInDown">{{ $carousel->title }}</div>
                                            <p class="animated slideInDown text-dark">{{ $carousel->description }}</p>

                                            <a href="{{ $carousel->btn_link }}"
                                                class="btn btn-primary rounded-pill py-sm-3 px-sm-5">{{ $carousel->btn_text }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <!-- Carousel End -->

        <!-- About Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                        <div class="about-img position-relative overflow-hidden p-5 pe-0">
                            <img class="img-fluid w-100"
                                src="https://healthybuddha.in/image/catalog/cat%20icon/new%20addition%20latest.jpg"
                                alt="Fresh Organic Fruits and Vegetables">
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                        <div class="display-5 h1 mb-4">Fresh & Organic Fruits and Vegetables</div>
                        <p class="mb-4">
                            We bring you nature’s best – handpicked, chemical-free, and naturally grown fruits
                            and vegetables delivered straight from local farms to your table. Our goal is to
                            make healthy living simple, sustainable, and accessible to everyone.
                        </p>
                        <p><i class="fa fa-check text-primary me-3"></i>100% Fresh, Seasonal & Organic Produce</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Directly Sourced from Trusted Farmers</p>
                        <p><i class="fa fa-check text-primary me-3"></i>No Chemicals, Pesticides, or Preservatives</p>
                        <a class="btn btn-primary rounded-pill py-3 px-5 mt-3"
                            href="{{ route('front.page', 'about-us') }}">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

        <!-- Feature Start -->
        <div class="container-fluid bg-light bg-icon my-5 py-6">
            <div class="container">
                <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s"
                    style="max-width: 500px;">
                    <div class="display-5 h1 mb-3">Why Choose Us</div>
                    <p>We believe in purity, sustainability, and healthy living. Our organic products are carefully sourced
                        and delivered fresh, ensuring you and your family enjoy nature’s true goodness.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="bg-white text-center h-100 p-4 p-xl-5">
                            <img class="img-fluid mb-4" src="{{ asset('assets/img/icon-1.png') }}" alt="Natural Farming">
                            <i class="display-1">🌱</i>
                            <h4 class="mb-3">Natural Process</h4>
                            <p class="mb-4">Our fruits and vegetables are grown using traditional farming practices – free
                                from harmful chemicals and pesticides.</p>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="bg-white text-center h-100 p-4 p-xl-5">
                            <img class="img-fluid mb-4" src="{{ asset('assets/img/icon-2.png') }}" alt="Organic Products">
                            <i class="display-1">🥕</i>
                            <h4 class="mb-3">100% Organic</h4>
                            <p class="mb-4">We bring you certified organic produce, handpicked daily from trusted farmers
                                to guarantee freshness and nutrition.</p>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="bg-white text-center h-100 p-4 p-xl-5">
                            <img class="img-fluid mb-4" src="{{ asset('assets/img/icon-3.png') }}" alt="Safe & Healthy">
                            <i class="display-1">🛡️</i>
                            <h4 class="mb-3">Safe & Healthy</h4>
                            <p class="mb-4">Every product we deliver is biologically safe, nutrient-rich, and handled with
                                utmost care to keep your family healthy.</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feature End -->


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

                                <div class="col-12 text-center">
                                    <a class="btn btn-primary rounded-pill py-3 px-5"
                                        href="{{ route('front.shop', ['category' => $sub->slug]) }}">
                                        Browse More {{ $sub->name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            X
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
            <div class="display-5 h1 mb-3">What Our Customers Say</div>
            <p>Real stories from people who enjoy the freshness and goodness of our organic products every day.</p>
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

<!-- Blog Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <div class="display-5 h1 mb-3">Latest Blog</div>
            <p>Explore tips, insights, and stories about organic farming, sustainability, and healthy living directly from our experts.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <img class="img-fluid" src="assets/img/blog-1.jpg" alt="Organic Farming Blog">
                <div class="bg-light p-4">
                    <a class="d-block h5 lh-base mb-4" href="">
                        5 Benefits of Choosing Organic Fruits and Vegetables
                    </a>
                    <p class="mb-3">Discover why going organic is healthier for you and better for the environment. From nutrition to taste, here’s what makes the difference.</p>
                    <div class="text-muted border-top pt-4">
                        <small class="me-3"><i class="fa fa-user text-primary me-2"></i>Admin</small>
                        <small class="me-3"><i class="fa fa-calendar text-primary me-2"></i>01 Aug, 2025</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <img class="img-fluid" src="assets/img/blog-2.jpg" alt="Healthy Lifestyle Blog">
                <div class="bg-light p-4">
                    <a class="d-block h5 lh-base mb-4" href="">
                        How to Start Your Own Organic Kitchen Garden
                    </a>
                    <p class="mb-3">Learn simple steps to grow fresh vegetables and herbs at home without chemicals — perfect for beginners who love healthy food.</p>
                    <div class="text-muted border-top pt-4">
                        <small class="me-3"><i class="fa fa-user text-primary me-2"></i>Admin</small>
                        <small class="me-3"><i class="fa fa-calendar text-primary me-2"></i>10 Aug, 2025</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <img class="img-fluid" src="assets/img/blog-3.jpg" alt="Sustainable Farming Blog">
                <div class="bg-light p-4">
                    <a class="d-block h5 lh-base mb-4" href="">
                        Sustainable Farming: Building a Greener Future
                    </a>
                    <p class="mb-3">Explore how organic farming practices support biodiversity, reduce pollution, and create a healthier planet for future generations.</p>
                    <div class="text-muted border-top pt-4">
                        <small class="me-3"><i class="fa fa-user text-primary me-2"></i>Admin</small>
                        <small class="me-3"><i class="fa fa-calendar text-primary me-2"></i>20 Aug, 2025</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blog End -->

        <style>
            #shop_content {
                transition: all 0.4s ease;
            }
        </style>
        <div class="text-center"><button class="btn btn-primary tf-btn mb-3" data-aos="fade-up" id="toggleButton">View
                More</button></div>
        <div class="container-fluid d-none" id="home_content">
            @include('seo.bodyBottomTagCodes')
        </div>

        <script>
            $(document).ready(function() {
                $('#toggleButton').click(function() {
                    $('#home_content').toggleClass('d-none');

                    if ($('#home_content').hasClass('d-none')) {
                        $(this).text('View More');
                    } else {
                        $(this).text('View Less');
                    }
                });
            });
        </script>
    @endsection

    @section('scripts')
        <script>
            $('#nav-link_home').addClass('active');
        </script>
    @endsection
