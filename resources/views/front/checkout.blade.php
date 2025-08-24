@extends('front.includes.layout')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="display-3 h1 mb-3 animated slideInDown">Checkout</div>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Checkout Section -->
    <section class="checkout-section pt-4 pb-5">
        <div class="container-fluid px-lg-5">
            <div class="row px-lg-4">
                <!-- Billing Details Form -->
                <div class="col-lg-7">
                    <h4 class="fw-bold mb-4">Billing Details</h4>

                    <form id="orderForm" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="first-name" class="form-label">First Name</label>
                                <input type="text" id="first-name" name="name"
                                    value="{{ !empty($address) ? $address->first_name : Auth::user()->name }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="last-name" class="form-label">Last Name</label>
                                <input type="text" id="last-name" name="l_name"
                                    value="{{ !empty($address) ? $address->last_name : '' }}" class="form-control">
                            </div>

                            @if (empty($address->last_name))
                                <script>
                                    let fullName = "{{ Auth::user()->name }}";
                                    let names = fullName.split(' ');
                                    $('#first-name').val(names[0]);
                                    $('#last-name').val(names.slice(1).join(' '));
                                </script>
                            @endif

                            <div class="col-12">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" id="country" name="country" value="{{ $address->country ?? '' }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="state" class="form-label">State</label>
                                <input type="text" id="state" name="state" value="{{ $address->state ?? '' }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="zip" class="form-label">Zip Code</label>
                                <input type="text" id="zip" name="zip" value="{{ $address->zip ?? '' }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label for="city" class="form-label">City</label>
                                <input type="text" id="city" name="city" value="{{ $address->city ?? '' }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" id="address" name="address" value="{{ $address->address ?? '' }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="number" id="phone" name="phone"
                                    value="{{ $address->mobile ?? Auth::user()->phone }}" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email"
                                    value="{{ $address->email ?? Auth::user()->email }}" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label for="note" class="form-label">Order Notes (Optional)</label>
                                <textarea id="note" name="note" rows="3" class="form-control"></textarea>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-5">
                    <h4 class="fw-bold mb-4">Your Order</h4>

                    <div class="card p-4 shadow-sm  sticky-top">

                        <ul class="list-group list-group-flush mb-3">
                            @foreach (Cart::content() as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->options->product_image ? asset($item->options->product_image) : asset('front-assets/images/products/white-2.jpg') }}"
                                            alt="{{ $item->name }}" class="img-thumbnail me-3" width="50">
                                        <div>
                                            <div>{{ $item->name }}</div>
                                            <small class="text-muted">{{ $item->options->color }} /
                                                {{ $item->options->size }}</small>
                                        </div>
                                    </div>
                                    <span>{{ config('settings.currency_symbol') }} {{ $item->qty * $item->price }}</span>
                                </li>
                            @endforeach
                        </ul>


                        @php
                            $discount = session('code') ? session('code')->discount_amount : 0;
                            $discountType = session('code') ? session('code')->type : '';
                            $coupon = session('code') ? session('code')->code : '';
                        @endphp

                        @if (session('code'))
                            <div class="d-flex justify-content-between">
                                <span>Discount:</span>
                                <span class="fw-bold">
                                    @if ($discountType == 'freeShipping')
                                        Free Shipping
                                    @elseif ($discountType == 'percent')
                                        {{ (session('code')->discount_amount * 100) / Cart::subtotal(2, '.', '') }}% off
                                    @else
                                        {{ $discount }}
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Coupon:</span>
                                <span class="fw-bold">{{ $coupon }}</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-3">
                            <span>Shipping:</span>
                            <span>{{ config('settings.currency_symbol') }} {{ calculateShipping() }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total:</span>
                            <span>{{ config('settings.currency_symbol') }}
                                {{ Cart::subtotal(2, '.', '') + calculateShipping() - $discount }}</span>
                        </div>

                        <div class="mt-4">

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="phonePe"
                                    value="phonePe" checked>
                                <label class="form-check-label" for="phonePe">
                                    <img src="https://media.licdn.com/dms/image/v2/D560BAQHLOrShxWW33g/company-logo_200_200/company-logo_200_200/0/1732870614932/phonepe_internet_logo?e=2147483647&v=beta&t=ADpboFA5Osbqra1iZzn343_VA2mUGAblUQe2-gejglo"
                                        width="30" class="me-2" alt="PhonePe"> PhonePe
                                </label>
                            </div>

                            <small class="text-muted">
                                Your data will be used to process your order, support your experience, and for
                                other purposes described in our
                                <a href="{{ route('front.page', 'privacy-policy') }}">Privacy Policy</a>.
                            </small>
                        </div>
                        <div class="checkout-button">

                            <button id="checkoutSubmitBtn" class="btn btn-primary w-100 mt-4">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById("applyCoupon").addEventListener("click", function(e) {
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
@endsection
