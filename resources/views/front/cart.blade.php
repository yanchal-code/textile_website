@extends('front.includes.layout')


@section('content')

    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="display-3 h1 mb-3 animated slideInDown">Shopping Cart</div>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">cart</li>
                </ol>
            </nav>
        </div>
    </div>


    @if (!empty($cartContent) && Cart::count() > 0)

        <section class="py-4">
            <div class="container">
                <div class="row g-4">
                    <!-- Cart Items -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0">Your Items</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">Product</th>
                                                <th scope="col" class="text-center">Price</th>
                                                <th scope="col" class="text-center">Quantity</th>
                                                <th scope="col" class="text-center">Total</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cartContent as $item)
                                                <tr class="remove_item_row">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <a href="{{ route('front.product', $item->id ?? '') }}">
                                                                <img src="{{ $item->options->product_image != '' ? asset($item->options->product_image) : asset('admin-assets/img/default-150x150.png') }}"
                                                                    alt="{{ $item->name }}" class="img-thumbnail me-3"
                                                                    style="width:60px; height:60px; object-fit:cover;">
                                                            </a>
                                                            <div>
                                                                <h6 class="mb-1">
                                                                    <a href="{{ route('front.product', $item->id ?? '') }}"
                                                                        class="text-decoration-none">{{ $item->name }}</a>
                                                                </h6>
                                                                <small class="text-muted">
                                                                    @if ($item->options->ring_size)
                                                                        Size: {{ $item->options->ring_size }} |
                                                                    @endif
                                                                    @if ($item->options->color)
                                                                        Color: {{ $item->options->color }}
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        {!! config('settings.currency_symbol') !!} {{ $item->price }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="input-group input-group-sm justify-content-center"
                                                            style="max-width:120px;">
                                                            <button class="btn btn-outline-secondary cart-minus-btn"
                                                                data-id="{{ $item->rowId }}">−</button>
                                                            <input style="box-shadow:none; border:none;" type="text"
                                                                readonly class="form-control text-center"
                                                                value="{{ $item->qty }}">
                                                            <button class="btn btn-outline-secondary cart-plus-btn"
                                                                data-id="{{ $item->rowId }}">+</button>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        {!! config('settings.currency_symbol') !!}
                                                        <span class="total_price"
                                                            id="{{ $item->rowId }}_item_total">{{ $item->price * $item->qty }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" data-id="{{ $item->rowId }}"
                                                            class="btn btn-sm btn-outline-danger cart-remove-item"
                                                            data-id="{{ $item->rowId }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <form action="{{ route('front.applyCoupon') }}" method="post" class="d-flex">
                                        @csrf
                                        <input type="text" name="code" class="form-control form-control-sm me-2"
                                            value="{{ session()->has('code') ? session()->get('code')->code : '' }}"
                                            placeholder="Coupon code">
                                        @if (session()->has('code'))
                                            <input type="hidden" name="removeCoupon" value="1">
                                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                                        @else
                                            <button class="btn btn-sm btn-primary">Apply</button>
                                        @endif
                                    </form>

                                    <div>
                                        @if (Session::has('invalidCart'))
                                            <button onclick="reArrangeCart()" class="btn btn-sm btn-outline-secondary me-2">
                                                <i class="bi bi-arrow-clockwise"></i> Update
                                            </button>
                                        @endif
                                        <button onclick="return window.location.reload()"
                                            class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-arrow-clockwise"></i> Reload
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-3">Order Summary</h5>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Subtotal</span>
                                        <strong>{!! config('settings.currency_symbol') !!} <span
                                                class="card_subtotal">{{ Cart::subtotal() }}</span></strong>
                                    </li>
                                    @if (Session::has('code'))
                                        <li class="list-group-item d-flex justify-content-between text-success">
                                            <span>Discount</span>
                                            <strong>
                                                @if ($discountType == 'freeShipping')
                                                    Free Shipping
                                                @elseif ($discountType == 'percent')
                                                    {{ (Session::get('code')->discount_amount * 100) / Cart::subtotal(2, '.', '') }}%
                                                @else
                                                    {!! config('settings.currency_symbol') !!} {{ $discount }}
                                                @endif
                                            </strong>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between fw-bold">
                                        <span>Total</span>
                                        @php
                                            $discount = '';
                                        @endphp
                                        <strong class="card_subtotal">{!! config('settings.currency_symbol') !!}
                                            @if ($discount != '' && $discountType != 'freeShipping')
                                                {{ Cart::subtotal(2, '.', '') - $discount }}
                                            @else
                                                {{ Cart::subtotal(2, '.', '') }}
                                            @endif
                                        </strong>
                                    </li>
                                </ul>

                                <a href="{{ route('front.checkout') }}" class="btn btn-primary w-100 mb-2">
                                    Proceed to Checkout <i class="bi bi-arrow-right"></i>
                                </a>
                                <a href="{{ route('front.shop') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left"></i> Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <div class="text-center py-5">
            <h5 class="mb-3">Your Shopping Cart is Empty</h5>
            <a class="btn btn-dark" href="{{ route('front.shop') }}">Return to Shop</a>
        </div>
    @endif


    <script>
        function reArrangeCart() {
            var button = $(this);
            $.ajax({
                type: "post",
                url: "{{ route('front.reArrangeCart') }}",
                data: {
                    'data': 1,
                },
                dataType: "json",
                beforeSend: function() {
                    button.prop('disabled', true);

                },
                success: function(response) {
                    button.prop('disabled', false);
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    button.prop('disabled', false);
                    $('.discount').remove();
                    $('#discountForm').val('');
                    var errorMessage = "";
                    try {
                        var responseJson = JSON.parse(xhr.responseText);
                        errorMessage = responseJson.message;
                    } catch (e) {
                        errorMessage = "An error occurred: " + xhr.status + " " + xhr.statusText;
                    }
                    showNotification(errorMessage, 'danger', 'html');

                }
            });

        }
    </script>
@endsection
