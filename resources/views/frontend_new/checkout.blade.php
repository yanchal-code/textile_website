<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Suruchi - Checkout</title>
  <meta name="description" content="Morden Bootstrap HTML5 Template">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="shortcut icon" type="image/x-icon" href="{{asset('frontend/img/favicon.ico') }}">
    
  <!-- ======= All CSS Plugins here ======== -->
<link rel="stylesheet" href="{{ asset('frontend/css/plugins/swiper-bundle.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/plugins/glightbox.min.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Plugin css -->
<link rel="stylesheet" href="{{ asset('frontend/css/vendor/bootstrap.min.css') }}">

<!-- Custom Style CSS -->
<link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

</head>

<body>

     <!-- Start preloader -->
     <div id="preloader">
        <div id="ctn-preloader" class="ctn-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
                <div class="txt-loading">
                    <span data-text-preloader="L" class="letters-loading">
                        L
                    </span>
                    
                    <span data-text-preloader="O" class="letters-loading">
                        O
                    </span>
                    
                    <span data-text-preloader="A" class="letters-loading">
                        A
                    </span>
                    
                    <span data-text-preloader="D" class="letters-loading">
                        D
                    </span>
                    
                    <span data-text-preloader="I" class="letters-loading">
                        I
                    </span>
                    
                    <span data-text-preloader="N" class="letters-loading">
                        N
                    </span>
                    
                    <span data-text-preloader="G" class="letters-loading">
                        G
                    </span>
                </div>
            </div>	

            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
    </div>
    <!-- End preloader -->

    <!-- Start checkout page area -->
    <div class="checkout__page--area">
        <div class="container">
            <div class="checkout__page--inner d-flex">
                <div class="main checkout__mian">
                    <header class="main__header checkout__mian--header mb-30">
                        <h1 class="main__logo--title"><a class="logo logo__left mb-20" href="/"><img src="{{asset('frontend/img/logo/nav-log.png')}}" alt="logo"></a></h1>
                        <details class="order__summary--mobile__version">
                            <summary class="order__summary--toggle border-radius-5">
                                <span class="order__summary--toggle__inner">
                                    <span class="order__summary--toggle__icon">
                                        <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <span class="order__summary--toggle__text show">
                                        <span>Show order summary</span>
                                        <svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="currentColor"><path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path></svg>
                                    </span>
                                    <span class="order__summary--final__price">$227.70</span>
                                </span>
                            </summary>
                            <div class="order__summary--section">
                                <div class="cart__table checkout__product--table">
                                    <table class="summary__table">
                                        <tbody class="summary__table--body">
                                            <tr class=" summary__table--items">
                                                <td class=" summary__table--list">
                                                    <div class="product__image two  d-flex align-items-center">
                                                        <div class="product__thumbnail border-radius-5">
                                                            <a href="product-details.html"><img class="border-radius-5" src="{{asset('frontend/img/product/small-pr')}}oduct7.png" alt="cart-product"></a>
                                                            <span class="product__thumbnail--quantity">1</span>
                                                        </div>
                                                        <div class="product__description">
                                                            <h3 class="product__description--name h4"><a href="product-details.html">Fresh-whole-fish</a></h3>
                                                            <span class="product__description--variant">COLOR: Blue</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class=" summary__table--list">
                                                    <span class="cart__price">£65.00</span>
                                                </td>
                                            </tr>
                                            <tr class="summary__table--items">
                                                <td class=" summary__table--list">
                                                    <div class="cart__product d-flex align-items-center">
                                                        <div class="product__thumbnail border-radius-5">
                                                            <a href="product-details.html"><img class="border-radius-5" src="{{asset('frontend/img/product/small-product2.png')}}" alt="cart-product"></a>
                                                            <span class="product__thumbnail--quantity">1</span>
                                                        </div>
                                                        <div class="product__description">
                                                            <h3 class="product__description--name h4"><a href="product-details.html">Vegetable-healthy</a></h3>
                                                            <span class="product__description--variant">COLOR: Green</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class=" summary__table--list">
                                                    <span class="cart__price">£82.00</span>
                                                </td>
                                            </tr>
                                            <tr class=" summary__table--items">
                                                <td class=" summary__table--list">
                                                    <div class="cart__product d-flex align-items-center">
                                                        <div class="product__thumbnail border-radius-5">
                                                            <a href="product-details.html"><img class="border-radius-5" src="{{asset('frontend/img/product/small-product4.png')}}" alt="cart-product"></a>
                                                            <span class="product__thumbnail--quantity">1</span>
                                                        </div>
                                                        <div class="product__description">
                                                            <h3 class="product__description--name h4"><a href="product-details.html">Raw-onions-surface</a></h3>
                                                            <span class="product__description--variant">COLOR: White</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class=" summary__table--list">
                                                    <span class="cart__price">£78.00</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>
                                <div class="checkout__discount--code">
                                    <form class="d-flex" action="#">
                                        <label>
                                            <input class="checkout__discount--code__input--field border-radius-5" placeholder="Gift card or discount code" type="text">
                                        </label>
                                        <button class="checkout__discount--code__btn primary__btn border-radius-5" type="submit">Apply</button>
                                    </form>
                                </div>
                                <div class="checkout__total">
                                    <table class="checkout__total--table">
                                        <tbody class="checkout__total--body">
                                            <tr class="checkout__total--items">
                                                <td class="checkout__total--title text-left">Subtotal </td>
                                                <td class="checkout__total--amount text-right">$860.00</td>
                                            </tr>
                                            <tr class="checkout__total--items">
                                                <td class="checkout__total--title text-left">Shipping</td>
                                                <td class="checkout__total--calculated__text text-right">Calculated at next step</td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="checkout__total--footer">
                                            <tr class="checkout__total--footer__items">
                                                <td class="checkout__total--footer__title checkout__total--footer__list text-left">Total </td>
                                                <td class="checkout__total--footer__amount checkout__total--footer__list text-right">$860.00</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </details>
                        <nav>
                            <ol class="breadcrumb checkout__breadcrumb d-flex">
                                <li class="breadcrumb__item breadcrumb__item--completed d-flex align-items-center">
                                    <a class="breadcrumb__link" href="cart.html">Cart</a>
                                    <svg class="readcrumb__chevron-icon" xmlns="http://www.w3.org/2000/svg" width="17.007" height="16.831" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M184 112l144 144-144 144"></path></svg>
                                </li>
                        
                                <li class="breadcrumb__item breadcrumb__item--current d-flex align-items-center">
                                    <span class="breadcrumb__text current">Information</span>
                                    <svg class="readcrumb__chevron-icon" xmlns="http://www.w3.org/2000/svg" width="17.007" height="16.831" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M184 112l144 144-144 144"></path></svg>
                                </li>
                                <li class="breadcrumb__item breadcrumb__item--blank d-flex align-items-center">
                                    <span class="breadcrumb__text">Shipping</span>
                                    <svg class="readcrumb__chevron-icon" xmlns="http://www.w3.org/2000/svg" width="17.007" height="16.831" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M184 112l144 144-144 144"></path></svg>
                                </li>
                                    <li class="breadcrumb__item breadcrumb__item--blank">
                                    <span class="breadcrumb__text">Payment</span>
                                </li>
                            </ol>
                            </nav>
                    </header>
                    <main class="main__content_wrapper">
                    <form action="" method="POST">
                        @csrf
                        <div class="checkout__content--step section__contact--information">
                            <div class="section__header checkout__section--header d-flex align-items-center justify-content-between mb-25">
                                <h2 class="section__header--title h3">Contact information</h2>
                                <p class="layout__flex--item">
                                    Already have an account?
                                    <a class="layout__flex--item__link" href="{{ route('login') }}">Log in</a>  
                                </p>
                            </div>
                            <div class="customer__information">
                                <!-- Email -->
                                <div class="checkout__email--phone mb-12">
                                    <label>
                                        <input class="checkout__input--field border-radius-5" 
                                            name="email" type="email" 
                                            value="{{ old('email', auth()->user()->email ?? '') }}" 
                                            placeholder="Email">
                                    </label>
                                    @error('email')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="checkout__email--phone mb-12">
                                    <label>
                                        <input class="checkout__input--field border-radius-5" 
                                            name="phone" type="text" 
                                            value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                            placeholder="Mobile phone number">
                                    </label>
                                    @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="checkout__checkbox">
                                    <input class="checkout__checkbox--input" id="subscribe" type="checkbox" name="subscribe" {{ old('subscribe') ? 'checked' : '' }}>
                                    <span class="checkout__checkbox--checkmark"></span>
                                    <label class="checkout__checkbox--label" for="subscribe">
                                        Email me with news and offers
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Shipping Address -->
                        <div class="checkout__content--step section__shipping--address">
                            <div class="section__header mb-25">
                                <h3 class="section__header--title">Shipping address</h3>
                            </div>
                            <div class="section__shipping--address__content">
                                <div class="row">
                                    <div class="col-lg-6 mb-12">
                                        <div class="checkout__input--list">
                                            <label>
                                                <input class="checkout__input--field border-radius-5" name="first_name" type="text" value="{{ old('first_name') }}" placeholder="First name">
                                            </label>
                                            @error('first_name')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-12">
                                        <div class="checkout__input--list">
                                            <label>
                                                <input class="checkout__input--field border-radius-5" name="last_name" type="text" value="{{ old('last_name') }}" placeholder="Last name">
                                            </label>
                                            @error('last_name')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-12">
                                        <div class="checkout__input--list">
                                            <label>
                                                <input class="checkout__input--field border-radius-5" name="company" type="text" value="{{ old('company') }}" placeholder="Company (optional)">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-12">
                                        <div class="checkout__input--list">
                                            <label>
                                                <input class="checkout__input--field border-radius-5" name="address1" type="text" value="{{ old('address1') }}" placeholder="Address 1">
                                            </label>
                                            @error('address1')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-12">
                                        <div class="checkout__input--list">
                                            <label>
                                                <input class="checkout__input--field border-radius-5" name="address2" type="text" value="{{ old('address2') }}" placeholder="Apartment, suite, etc. (optional)">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-12">
                                        <div class="checkout__input--list">
                                            <label>
                                                <input class="checkout__input--field border-radius-5" name="city" type="text" value="{{ old('city') }}" placeholder="City">
                                            </label>
                                            @error('city')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-12">
                                        <div class="checkout__input--list checkout__input--select select">
                                            <label class="checkout__select--label" for="country">Country/region</label>
                                            <select class="checkout__input--select__field border-radius-5" id="country" name="country">
                                                <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                                                <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                                                <option value="Netherlands" {{ old('country') == 'Netherlands' ? 'selected' : '' }}>Netherlands</option>
                                                <option value="Afghanistan" {{ old('country') == 'Afghanistan' ? 'selected' : '' }}>Afghanistan</option>
                                            </select>
                                            @error('country')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-12">
                                        <div class="checkout__input--list">
                                            <label>
                                                <input class="checkout__input--field border-radius-5" name="postal_code" type="text" value="{{ old('postal_code') }}" placeholder="Postal code">
                                            </label>
                                            @error('postal_code')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Order Notes -->
                                    <div class="col-12 mb-12">
                                        <div class="checkout__input--list">
                                            <label>
                                                <textarea class="checkout__input--field border-radius-5" name="order_notes" placeholder="Order notes (optional)">{{ old('order_notes') }}</textarea>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout__checkbox">
                                    <input class="checkout__checkbox--input" id="save_info" type="checkbox" name="save_info" {{ old('save_info') ? 'checked' : '' }}>
                                    <span class="checkout__checkbox--checkmark"></span>
                                    <label class="checkout__checkbox--label" for="save_info">
                                        Save this information for next time
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Buttons -->
                        <div class="checkout__content--step__footer d-flex align-items-center">
                            <button type="submit" class="continue__shipping--btn primary__btn border-radius-5">Continue To Shipping</button>
                            <a class="previous__link--content" href="{{route('front.cart')}}">Return to cart</a>
                        </div>
                    </form>
                    </main>
                    <footer class="main__footer checkout__footer">
                        <p class="copyright__content">Copyright © 2022 <a class="copyright__content--link text__primary" href="index.html">Suruchi</a> . All Rights Reserved.Design By Suruchi</p>
                    </footer>
                </div>
                <aside class="checkout__sidebar sidebar">
                    <div class="cart__table checkout__product--table">
                        <table class="cart__table--inner">
                            <tbody class="cart__table--body">
                                @foreach (Cart::content() as $item)
                                    <tr class="cart__table--body__items">
                                        <td class="cart__table--body__list">
                                            <div class="product__image two d-flex align-items-center">
                                                <div class="product__thumbnail border-radius-5">
                                                    <a href="">
                                                        <img class="border-radius-5"
                                                            src="{{ $item->options->product_image ? asset($item->options->product_image) : asset('frontend/img/product/default.png') }}"
                                                            alt="{{ $item->name }}">
                                                    </a>
                                                    <span class="product__thumbnail--quantity">{{ $item->qty }}</span>
                                                </div>
                                                <div class="product__description">
                                                    <h3 class="product__description--name h4">
                                                        <a href="">{{ $item->name }}</a>
                                                    </h3>
                                                    <span class="product__description--variant">
                                                        COLOR: {{ $item->options->color ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cart__table--body__list">
                                            <span class="cart__price">{{ config('settings.currency_symbol') }} {{ $item->price * $item->qty }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Coupon/Discount -->
                    <div class="checkout__discount--code mb-3">
                        <form id="applyCouponForm" class="d-flex">
                            <input type="text" id="couponCodeInput" class="checkout__discount--code__input--field border-radius-5" placeholder="Gift card or discount code">
                            <button type="submit" id="applyCoupon" class="checkout__discount--code__btn primary__btn border-radius-5">Apply</button>
                        </form>
                    </div>

                    <!-- Totals -->
                    @php
                        $discount = session('code') ? session('code')->discount_amount : 0;
                        $discountType = session('code') ? session('code')->type : '';
                        $coupon = session('code') ? session('code')->code : '';
                        $subtotal = Cart::subtotal(2, '.', '');
                        $shipping = calculateShipping();
                        $total = $subtotal + $shipping - $discount;
                    @endphp

                    <div class="checkout__total">
                        <table class="checkout__total--table">
                            <tbody class="checkout__total--body">
                                <tr class="checkout__total--items">
                                    <td class="checkout__total--title text-left">Subtotal</td>
                                    <td class="checkout__total--amount text-right">{{ config('settings.currency_symbol') }} {{ $subtotal }}</td>
                                </tr>
                                <tr class="checkout__total--items">
                                    <td class="checkout__total--title text-left">Shipping</td>
                                    <td class="checkout__total--calculated__text text-right">Calculated at next step</td>
                                </tr>
                                @if(session('code'))
                                <tr class="checkout__total--items">
                                    <td class="checkout__total--title text-left">Discount ({{ $coupon }})</td>
                                    <td class="checkout__total--amount text-right">
                                        @if($discountType == 'freeShipping')
                                            Free Shipping
                                        @elseif($discountType == 'percent')
                                            {{ round(($discount * 100) / $subtotal, 2) }}% off
                                        @else
                                            {{ config('settings.currency_symbol') }} {{ $discount }}
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot class="checkout__total--footer">
                                <tr class="checkout__total--footer__items">
                                    <td class="checkout__total--footer__title text-left">Total</td>
                                    <td class="checkout__total--footer__amount text-right">{{ config('settings.currency_symbol') }} {{ $total }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Payment Method -->
                    <div class="mt-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="phonePe" value="phonePe" checked>
                            <label class="form-check-label" for="phonePe">
                                <img src="https://media.licdn.com/dms/image/v2/D560BAQHLOrShxWW33g/company-logo_200_200/company-logo_200_200/0/1732870614932/phonepe_internet_logo?e=2147483647&v=beta&t=ADpboFA5Osbqra1iZzn343_VA2mUGAblUQe2-gejglo"
                                    width="30" class="me-2" alt="PhonePe"> PhonePe
                            </label>
                        </div>
                        <small class="text-muted">
                            Your data will be used to process your order, support your experience, and for other purposes described in our
                            <a href="{{ route('front.page', 'privacy-policy') }}">Privacy Policy</a>.
                        </small>
                    </div>

                    <div class="checkout-button mt-4">
                        <button id="checkoutSubmitBtn" class="btn btn-primary w-100">Place Order</button>
                    </div>
                </aside>

<!-- Coupon Script -->


            </div>
        </div>
    </div>
    <!-- End checkout page area -->

    <!-- Scroll top bar -->
    <button id="scroll__top"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M112 244l144-144 144 144M256 120v292"></path></svg></button>

  <!-- All Script JS Plugins here  -->
<script src="{{ asset('frontend/js/vendor/popper.js') }}" defer></script>
<script src="{{ asset('frontend/js/vendor/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('frontend/js/plugins/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('frontend/js/plugins/glightbox.min.js') }}"></script>

<!-- Custom script js -->
<script src="{{ asset('frontend/js/script.js') }}"></script>
<script>
    document.getElementById("applyCouponForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let couponCode = document.getElementById("couponCodeInput").value;
        let form = document.createElement("form");
        form.method = "POST";
        form.action = "{{ route('front.applyCoupon') }}";
        let csrfField = document.createElement("input");
        csrfField.type = "hidden";
        csrfField.name = "_token";
        csrfField.value = "{{ csrf_token() }}";
        form.appendChild(csrfField);
        let couponField = document.createElement("input");
        couponField.type = "hidden";
        couponField.name = "code";
        couponField.value = couponCode;
        form.appendChild(couponField);
        document.body.appendChild(form);
        form.submit();
    });
</script>
 <script>
        $('#checkoutSubmitBtn').click(function() {
            $('#orderForm').submit();
        });
        $('#orderForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var html = $('#checkoutSubmitBtn').html();

            if (form[0].checkValidity() === true) {
                var formData = new FormData(this);
                var paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

                if (paymentMethod) {
                    formData.append('paymentMethod', paymentMethod.value);
                }

                $.ajax({
                    type: "post",
                    url: "{{ route('checkout.process') }}",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('#checkoutSubmitBtn').html(
                            '<span id="slugLoader"><span class="loader"></span> Loading...</span>'
                        );
                    },
                    success: function(response) {

                        $('#checkoutSubmitBtn').html(html);


                        if (response.status === 'validate') {
                            var errorsHtml = '';
                            var errors = response.errors;
                            var count = 1;
                            for (var key in errors) {

                                if (errors.hasOwnProperty(key)) {
                                    errorsHtml += '<p>' + count + '. ' + errors[key][0] + '</p>';
                                }
                                count = count + 1;
                            }
                            showNotification(errorsHtml, 'danger', 'html');

                        } else if (response.status === true) {
                            window.location.href = response.redirect_url;

                        } else if (response.status == 'duplicate') {
                            window.location.href = response.redirect_url;

                        } else {
                            showNotification(response.message, 'danger', 'text');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#checkoutSubmitBtn').html(html);

                        var errorMessage = "";
                        try {
                            var responseJson = JSON.parse(xhr.responseText);
                            errorMessage = responseJson.message;
                        } catch (e) {
                            errorMessage = "An error occurred: " + xhr.status + " " + xhr
                                .statusText;
                        }

                        showNotification(errorMessage, 'danger', 'html');
                    }
                });

            } else {
                form.addClass('was-validated');
            }
        });
    </script>

  
</body>
</html>