<div class="tf-product-media-wrap">
    <div dir="ltr" class="swiper tf-single-slide">
        <div class="swiper-wrapper">

            @foreach (getImagesBySku($product->sku) as $item)
                <div class="swiper-slide">
                    <div class="item text-center d-flex align-items-center justify-content-center">
                        <img src="{{ asset($item) }}" alt="img">
                    </div>
                </div>
            @endforeach

        </div>
        <div class="swiper-button-next button-style-arrow single-slide-prev"></div>
        <div class="swiper-button-prev button-style-arrow single-slide-next"></div>
    </div>
</div>
<div class="tf-product-info-wrap position-relative">
    <div class="tf-product-info-list">
        <div class="tf-product-info-title">
            <h5><a class="link" href="{{ route('front.product', $product->sku) }}">{{ $product->name }}</a></h5>
        </div>
        <div class="tf-product-info-badges">
            <div class="badges text-uppercase">Best seller</div>
             <div class="product-status-content">
                                        <i class="icon-lightning"></i>

                                        <div class="tf-product-info-liveview">
                                            <div class="liveview-count">
                                                <?php echo rand(50, 200); ?>
                                            </div>
                                            <p class="fw-6">People are viewing this right now</p>
                                        </div>

            </div>
        </div>
        <div class="tf-product-info-price">
            <div class="price">{{ $settings->currency_symbol }} {{ $product->price }}</div>
            <div class="badges-on-sale">
                <span>{{ floor((($product->compare_price - $product->price) / $product->price) * 100) }}</span>%
                OFF
            </div>
        </div>
        <div class="tf-product-description">
            <p>{{ $product->short_description }}</p>
        </div>
        <div class="tf-product-info-variant-picker">
            <div class="variant-picker-item">
                 @if( $product->color )
                    <div class="variant-picker-label">
                         {{ $product->leafCategory->v1st }}: <span class="fw-6 variant-picker-label-value">{{ $product->color }}</span>
                    </div>
                @endif
                <div class="variant">
                  
                    @foreach ($product->variations->unique('color') as $color)
                        <button onclick="loadQuickView('{{ $color->sku }}')"
                            class=" product-variation-btn-quick  p-1 animate-hover-btn {{ $color->color == $product->color ? 'btn-fill' : 'btn-outline' }}">
                            <p>{{ $color->color }}</p>
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="variant-picker-item">
                <div class="d-flex justify-content-between align-items-center">
                    @if( $product->size )
                    <div class="variant-picker-label">
                         {{ $product->leafCategory->v2nd }}: <span class="fw-6 variant-picker-label-value">{{ $product->size }}</span>
                    </div>
                    @endif
                </div>
                <div class="variant">
                  

                    @foreach ($product->variations->unique('size') as $size)
              
                      @if ($size->size)            
                        <button onclick="loadQuickView('{{ $size->sku }}')"
                            class=" product-variation-btn-quick  p-1 animate-hover-btn {{ $size->size == $product->size ? 'btn-fill' : 'btn-outline' }}">
                            <p>{{ $size->size }}</p>
                        </button>
                       @endif
                    @endforeach
                </div>
            </div>
        </div>
        
        
          <div>
            @if ($product->specs)
                @php
                    $specs = json_decode($product->specs, true);
                @endphp

                @foreach ($specs as $key => $value)
                    @if (strpos($value, ',') !== false)
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>

                            @php
                                $options = explode(',', $value);
                            @endphp
                            <select name="{{ $key }}" class="options_select_input form-select">
                                @foreach ($options as $option)
                                    <option value="{{ trim($option) }}">{{ trim($option) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        

        <div class="tf-product-info-quantity">
            <div class="quantity-title fw-6">Quantity</div>

            <div class="wg-quantity">
                <span class="btn-quantity btn-decrease">-</span>
                <input type="text" class="quantity-product product-quantity" name="number" value="1">
                <span class="btn-quantity btn-increase">+</span>
            </div>
        </div>
        <div class="tf-product-info-buy-button">
            <form class="">
                <a href="javascript:void(0);" data-bs-id="{{ $product->sku }}"
                    class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart quick-add-qty"><span>Add
                        to cart -&nbsp;</span><span class="tf-qty-price">{{ $settings->currency_symbol }}
                        {{ $product->price }}</span></a>
                <a onclick="addToWishlist({{ $product->id }})" href="javascript:void(0);"
                    class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action">
                    <span class="icon icon-heart"></span>
                    <span class="tooltip">Add to Wishlist</span>
                    <span class="icon icon-delete"></span>
                </a>

            </form>
        </div>
        <div>
            <a href="{{ route('front.product', $product->sku) }}" class="tf-btn fw-6 btn-line">View full details<i
                    class="icon icon-arrow1-top-left"></i></a>
        </div>
    </div>
</div>
