<section id="product-details" class="product-details section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-evenly position-relative" id="product_div">
            <!-- Product Images -->
            <!-- Product Images -->
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-delay="200">
                <div class="product-images">
                    <div class="main-image-container mb-3">
                        <div class="image-zoom-container">
                            @php
                                $images = getImagesBySku($product->sku);
                                $videos = $product->videos;
                                $totalImages = count($images);
                                $mainImage = $images[0] ?? 'admin-assets/img/default-150x150.png';
                            @endphp
                            <img src="{{ $mainImage }}" alt="{{ $product->alt_image_text }}"
                                class="img-fluid main-image drift-zoom" id="main-product-image"
                                data-zoom="{{ $mainImage }}">
                        </div>
                    </div>

                    <div class="product-thumbnails">
                        <div class="swiper product-thumbnails-slider init-swiper">
                            <script type="application/json" class="swiper-config">
                                        {
                                        "loop": false,
                                        "speed": 400,
                                        "slidesPerView": 4,
                                        "spaceBetween": 10,
                                        "navigation": {
                                            "nextEl": ".swiper-button-next",
                                            "prevEl": ".swiper-button-prev"
                                        },
                                        "breakpoints": {
                                            "320": {"slidesPerView": 3},
                                            "576": {"slidesPerView": 4}
                                        }
                                        }
                                    </script>
                            <div class="swiper-wrapper">
                                @foreach ($images as $image)
                                    <div class="swiper-slide thumbnail-item @if ($loop->first) active @endif"
                                        data-image="{{ $image }}">
                                        <img src="{{ $image }}" alt="{{ $product->alt_image_text }}"
                                            class="img-fluid">
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="secondDiv">
                <div class="card border-0 product-info sticky-top rounded-4 p-4" style="z-index: 100;">


                    <div class="product-meta mb-2">
                        <span class="product-category">{{ $product->leafCategory->name ?? 'Category' }}</span>
                    </div>

                    <h1 class="product-title">{{ $product->name }}</h1>

                    <div class="product-price-container mb-4">
                        <span
                            class="current-price">{{ config('settings.currency_symbol') }}{{ number_format($product->price, 2) }}</span>
                        @if ($product->compare_price > $product->price)
                            <del
                                class="original-price">{{ config('settings.currency_symbol') }}{{ number_format($product->compare_price, 2) }}</del>
                            <span
                                class="discount-badge badge bg-danger">-{{ floor((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%</span>
                        @endif
                    </div>

                    <div class="product-short-description mb-4">
                        <p>{{ $product->short_description }}</p>
                    </div>

                    <!-- Variations -->
                    <div class="mb-4">
                        <!-- Color Picker -->
                        @if ($product->color)
                            <div class="mb-3 text-start">
                                <label
                                    class="form-label text-start fw-semibold">{{ $product->leafCategory->v1st }}:</label>
                                <div class="d-flex gap-2 flex-wrap">

                                    @php
                                        $default = getProductByid($product->id);

                                        $variations = $product->variations;

                                        if (!empty($product->color)) {
                                            $defaultVariation = (object) [
                                                'color' => $default->color,
                                                'size' => $default->size,
                                                'sku' => $default->sku,
                                            ];

                                            $variations = $variations->push($defaultVariation);
                                        }

                                        $colorOptions = $variations->unique('color')->values();
                                    @endphp
                                    @foreach ($colorOptions as $color)
                                        <button onclick="return loadQuickView('{{ $color->sku }}')"
                                            class="btn btn-sm {{ $color->color == $product->color ? 'btn-primary' : 'btn-outline-secondary' }}">
                                            {{ $color->color }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Size Picker -->
                        @if ($product->size)

                            @php
                                $sizeOptions = $variations->where('color', $product->color)->unique('size')->values();
                            @endphp
                            <div class="text-start">
                                <label
                                    class="form-label text-start fw-semibold">{{ $product->leafCategory->v2nd }}:</label>
                                <div class="d-flex gap-2 flex-wrap" id="sizeContainer">
                                    @foreach ($sizeOptions as $size)
                                        @if ($size->size)
                                            <button onclick="return loadQuickView('{{ $size->sku }}')"
                                                class="btn btn-sm {{ $size->size == $product->size ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                {{ $size->size }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>


                    <!-- Specs Dropdowns -->
                    @if ($product->specs)
                        @php
                            $specs = json_decode($product->specs, true);
                        @endphp
                        <div class="mb-4 row gx-3 gy-3">
                            @foreach ($specs as $key => $value)
                                @if (strpos($value, ',') !== false)
                                    @php $options = explode(',', $value); @endphp
                                    <div class="col-md-6">
                                        <label for="{{ $key }}"
                                            class="form-label fw-semibold text-capitalize">
                                            {{ str_replace('_', ' ', $key) }}
                                        </label>
                                        <select name="{{ $key }}" id="{{ $key }}"
                                            class="form-select">
                                            @foreach ($options as $option)
                                                <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <!-- Quantity Selector -->
                    <div class="mb-4 d-flex align-items-center gap-3">
                        <span class="fw-semibold fs-6">Quantity:</span>

                        <style>
                            .cart-quantity {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                            }

                            .wg-quantity {
                                display: flex;
                                align-items: center;
                                border: 0.1px solid #ddd;
                                border-radius: 8px;
                                overflow: hidden;
                                background-color: #fff;
                            }

                            .btn-quantity {
                                background-color: #f8f9fa;
                                border: none;
                                padding: 8px 12px;
                                font-size: 18px;
                                font-weight: bold;
                                cursor: pointer;
                                transition: background-color 0.3s ease;
                                color: #333;
                            }

                            .btn-quantity:hover {
                                background-color: #e2e6ea;
                            }

                            .wg-quantity input[type="text"] {
                                width: 50px;
                                text-align: center;
                                border: none;
                                outline: none;
                                font-size: 16px;
                                padding: 8px 0;
                                background-color: transparent;
                            }
                        </style>
                        <div class="cart-quantity">
                            <div class="wg-quantity">
                                <button class="btn-quantity cart-minus-btn-qty btn">-</button>
                                <input type="text" value="1" name="number" id="product-quantity">
                                <button data-id="" class="btn-quantity cart-plus-btn-qty btn">+</button>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('.cart-minus-btn-qty').click(function() {
                                    let $input = $('#product-quantity');
                                    let currentVal = parseInt($input.val());
                                    if (currentVal > 0) {
                                        $input.val(currentVal - 1);
                                    }
                                });

                                $('.cart-plus-btn-qty').click(function() {
                                    let $input = $('#product-quantity');
                                    let currentVal = parseInt($input.val());
                                    $input.val(currentVal + 1);
                                });

                                // Optional: Prevent manual input of negative numbers
                                $('#product-quantity').on('input', function() {
                                    let val = $(this).val();
                                    if (val === '' || isNaN(val) || parseInt(val) < 0) {
                                        $(this).val(0);
                                    }
                                });
                            });
                        </script>

                    </div>

                    <!-- Add to Cart & Wishlist Buttons -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <a href="javascript:void(0);" data-bs-id="{{ $product->sku }}"
                            class="btn btn-primary flex-grow-1 quick-add-qty fw-semibold fs-5">
                            Add to cart - <span class="ms-2">{{ config('settings.currency_symbol') }}
                                {{ number_format($product->price, 2) }}</span>
                        </a>
                        <button onclick="addToWishlist({{ $product->id }})" class="btn btn-light texy-danger btn-icon"
                            title="Add to Wishlist">
                            <i class="bi bi-heart fs-5"></i>
                        </button>
                    </div>

                    <!-- Shipping Info -->
                    <p class="mb-4 text-center">
                        <span class="">Shipping:</span>

                        {{ $product->shipping > 0 ? config('settings.currency_symbol') . $product->shipping : 'Free Shipping' }}
                        {{ $product->shippingAddons > 0 ? ' | +' . $product->shippingAddons . ' each additional qiantity.' : '' }}


                    </p>

                    <!-- Share Buttons -->
                    <div class="mb-4 text-center">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank" class="btn me-1" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode(url()->current()) }}" target="_blank"
                            class="btn me-1" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->title) }}"
                            target="_blank" class="btn me-1" title="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}"
                            target="_blank" class="btn me-1" title="Pinterest">
                            <i class="bi bi-pinterest"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                            target="_blank" class="btn" title="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>

                    <!-- Delivery & Return Info -->
                    <div class="row text-center text-md-start mb-4">

                        <div class="col-md-6">
                            <div
                                class="d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                                <i class="bi bi-arrow-counterclockwise fs-3 text-primary"></i>
                                <small>Handling time:
                                    <strong>{{ $product->h_time ?? '1 Day' }}</strong></small>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3 mb-md-0">
                            <div
                                class="d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                                <i class="bi bi-truck fs-3 text-primary"></i>
                                <div>
                                    <p class="mb-0">Estimate delivery:
                                        <strong>{{ $product->d_time ?? '3-11 Days' }}</strong>
                                    </p>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- /default -->
<style>
    /* Elegant Tab Design */
    .custom-tabs {
        border-bottom: 1px solid #dee2e6;
        gap: 20px;
    }

    .custom-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        background-color: transparent;
        color: #6c757d;
        /* muted gray */
        font-weight: 500;
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }

    .custom-tabs .nav-link:hover {
        color: var(--accent-color);
        /* Bootstrap blue */
    }

    .custom-tabs .nav-link.active {
        color: var(--accent-color);
        font-weight: 600;
        border-bottom: 3px solid var(--accent-color);
    }
</style>
<section class="product-tabs pt-4 pb-5">
    <div class="container-fluid px-lg-5">
        <div class="row">
            <div class="col-12">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs custom-tabs mb-4" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                            data-bs-target="#description" type="button" role="tab" aria-controls="description"
                            aria-selected="true">Description</button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specification-tab" data-bs-toggle="tab"
                            data-bs-target="#specification" type="button" role="tab"
                            aria-controls="specification" aria-selected="false">Additional Info</button>
                    </li>
                </ul>


                <!-- Tab Contents -->
                <div class="tab-content" id="productTabContent">

                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel"
                        aria-labelledby="description-tab">
                        <div class="p-3 bg-light rounded">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <!-- Specification Tab -->
                    <div class="tab-pane fade" id="specification" role="tabpanel"
                        aria-labelledby="specification-tab">
                        <div class="p-3 bg-light rounded">
                            @if ($product->specs)
                                @php
                                    $specs = json_decode($product->specs, true);
                                @endphp
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Specification</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($product->color)
                                            <tr>
                                                <td>{{ $product->leafCategory->v1st }}</td>
                                                <td>{{ $product->color }}</td>
                                            </tr>
                                            @if ($product->leafCategory->v2nd)
                                                <tr>
                                                    <td>{{ $product->leafCategory->v2nd }}</td>
                                                    <td>{{ $product->size }}</td>
                                                </tr>
                                            @endif
                                        @endif
                                        @foreach ($specs as $key => $value)
                                            @if ($value)
                                                <tr>
                                                    <td>{{ ucfirst($key) }}</td>
                                                    <td>{{ $value }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No specifications available.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
