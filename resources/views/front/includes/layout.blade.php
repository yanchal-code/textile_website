<!DOCTYPE html>
<html lang="en">

<head>

    @include('front.includes.head')

    @yield('head')

</head>

<body>

    @include('admin.includes.notification')
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <div class="container-fluid fixed-top px-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="top-bar row gx-0 align-items-center d-none d-lg-flex">
            <div class="col-lg-6 px-5 text-start">
                <small><i class="fa fa-map-marker-alt me-2"></i>{{ config('settings.address') }}</small>
                <small class="ms-4"><i class="fa fa-envelope me-2"></i>{{ config('settings.email') }}</small>
            </div>
            <div class="col-lg-6 px-5 text-end">
                <small>Follow us:</small>
                <a class="text-body ms-3" href="{{ config('settings.facebook') }}"><i class="fab fa-facebook-f"></i></a>
                <a class="text-body ms-3" href="{{ config('settings.twitter') }}"><i class="fab fa-twitter"></i></a>
                <a class="text-body ms-3" href="{{ config('settings.linkedin') }}"><i
                        class="fab fa-linkedin-in"></i></a>
                <a class="text-body ms-3" href="{{ config('settings.instagram') }}"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
            <a href="{{ route('front.home') }}" class="navbar-brand ms-4 ms-lg-0">
                <div class="fw-bold text-primary h1 m-0">{{ config('settings.name') }}</div>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="{{ route('front.home') }}" class="nav-item nav-link active">Home</a>
                    <a href="{{ route('front.page', 'about-us') }}" class="nav-item nav-link">About Us</a>
                    <a href="{{ route('front.shop') }}" class="nav-item nav-link">Products</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="" class="dropdown-item">Blog Grid</a>
                            <a href="" class="dropdown-item">Our Features</a>
                            <a href="" class="dropdown-item">Testimonial</a>
                            <a href="" class="dropdown-item">404 Page</a>
                        </div>
                    </div>
                    <a href="{{ route('front.page', 'contact-us') }}" class="nav-item nav-link">Contact Us</a>
                </div>
                <div class="d-none d-lg-flex ms-2">
                    <a class="btn-sm-square bg-white rounded-circle ms-3" href="">
                        <small class="fa fa-search text-body"></small>
                    </a>
                    <a class="btn-sm-square bg-white rounded-circle ms-3" href="">
                        <small class="fa fa-user text-body"></small>
                    </a>
                    <a class="btn-sm-square bg-white rounded-circle ms-3" href="">
                        <small class="fa fa-shopping-bag text-body"></small>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <main class="main">
        @yield('content')

        @hasSection('content')
        @else
            <div style="min-height: 50vh" class="py-2">
                <div class="h2 text-center fs-3 py-5 my-5"> Content Not Found. </div>
            </div>
        @endif
    </main>


    <!-- Footer Start -->
    <div class="container-fluid bg-dark footer pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <div class="fw-bold h1 text-primary mb-4">{{config('settings.name')}}</div>

                    <p>
                        {{ config('settings.footer_description') }}
                    </p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href="{{config('settings.twitter')}}"><i
                                class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href="{{config('settings.facebook')}}"><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href="{{config('settings.youtube')}}"><i
                                class="fab fa-youtube"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-0" href="{{config('settings.linkedin')}}"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Address</h4>
                    <p><i class="fa fa-map-marker-alt me-3"></i>{{ config('settings.address') }}</p>
                    <p><i class="fa fa-phone-alt me-3"></i>+{{ config('settings.phone') }}</p>
                    <p><i class="fa fa-envelope me-3"></i>{{ config('settings.email') }}</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Quick Links</h4>
                    <a class="btn btn-link" href="{{ route('front.page', 'about-us') }}">About Us</a>
                    <a class="btn btn-link" href="{{ route('front.page', 'contact-us') }}">Contact Us</a>
                    <a class="btn btn-link" href="">Our Services</a>
                    <a class="btn btn-link" href="{{ route('front.page', 'terms-conditions') }}">Terms &
                        Condition</a>
                    <a class="btn btn-link" href="{{ route('front.page', 'contact-us') }}">Support</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Join Our Organic Family</h4>
                    <p>Get the freshest updates on seasonal produce, healthy recipes, and exclusive offers delivered
                        straight to your inbox.</p>
                    <form onsubmit="return handleFormSubmit('#popup', '{{ route('front.newsletter') }}')"
                        id="popup" class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" name="email" type="email"
                            placeholder="your email">
                        <button type="submit"
                            class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="container-fluid copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a href="/">{{ config('settings.name') }}</a>, All Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i
            class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->

    @include('front.includes.foot')

    @yield('scripts')


</body>

</html>
