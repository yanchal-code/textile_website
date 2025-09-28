@extends('frontend_new.layouts.main')
@section('content')
<style>
    .dropdown-menu .active {
        background-color: #198754 !important;
        color: #fff !important;
    }
</style>

  <main class="main__content_wrapper">
        
        <!-- Start breadcrumb section -->
        <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <h1 class="breadcrumb__content--title text-white mb-25">Shop Left Sidebar</h1>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items"><a class="text-white" href="index.html">Home</a></li>
                                <li class="breadcrumb__content--menu__items"><span class="text-white">Shop Left Sidebar</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End breadcrumb section -->
        <!-- Start shop section -->
        <section class="shop__section section--padding">
            <div class="container-fluid">
                <div class="shop__header bg__gray--color d-flex align-items-center justify-content-between mb-30">
                    <button class="widget__filter--btn d-flex d-lg-none align-items-center" data-offcanvas="">
                        <svg class="widget__filter--btn__icon" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28" d="M368 128h80M64 128h240M368 384h80M64 384h240M208 256h240M64 256h80"></path><circle cx="336" cy="128" r="28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28"></circle><circle cx="176" cy="256" r="28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28"></circle><circle cx="336" cy="384" r="28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28"></circle></svg> 
                        <span class="widget__filter--btn__text">Filter</span>
                    </button>
                    <div class="product__view--mode d-flex align-items-center">
                        <div class="product__view--mode__list product__short--by align-items-center d-none d-lg-flex">
                            <label class="product__view--label">Prev Page :</label>
                            <div class="select shop__header--select">
                                <select class="product__view--select">
                                    <option selected="" value="1">65</option>
                                    <option value="2">40</option>
                                    <option value="3">42</option>
                                    <option value="4">57 </option>
                                    <option value="5">60 </option>
                                </select>
                            </div>
                        </div>
                        <div class="product__view--mode__list product__short--by align-items-center d-none d-lg-flex">
                        <label class="product__view--label me-2 mb-0">Sort By:</label>
                        <div class="select shop__header--select">
                        <select id="sortSelect" class="product__view--select">
                            <option value="latest" {{ $sortBy == 'latest' ? 'selected' : '' }}>Sort by latest</option>
                            <option value="price_desc" {{ $sortBy == 'price_desc' ? 'selected' : '' }}>Price, high to low</option>
                            <option value="price_asc" {{ $sortBy == 'price_asc' ? 'selected' : '' }}>Price, low to high</option>
                        </select>
                    </div>
                    </div>
                    <script>
                        document.getElementById('sortSelect').addEventListener('change', function() {
                            let sortValue = this.value;
                            let url = new URL(window.location.href);
                            url.searchParams.set('sort', sortValue);
                            window.location.href = url.toString(); // Reload page with selected sort
                        });
                    </script>
                        <!-- <div class="product__view--mode__list">
                            <div class="product__grid--column__buttons d-flex justify-content-center">
                                <button class="product__grid--column__buttons--icons active" aria-label="product grid button" data-toggle="tab" data-target="#product_grid">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewbox="0 0 9 9">
                                        <g transform="translate(-1360 -479)">
                                          <rect id="Rectangle_5725" data-name="Rectangle 5725" width="4" height="4" transform="translate(1360 479)" fill="currentColor"></rect>
                                          <rect id="Rectangle_5727" data-name="Rectangle 5727" width="4" height="4" transform="translate(1360 484)" fill="currentColor"></rect>
                                          <rect id="Rectangle_5726" data-name="Rectangle 5726" width="4" height="4" transform="translate(1365 479)" fill="currentColor"></rect>
                                          <rect id="Rectangle_5728" data-name="Rectangle 5728" width="4" height="4" transform="translate(1365 484)" fill="currentColor"></rect>
                                        </g>
                                      </svg>
                                </button>
                                <button class="product__grid--column__buttons--icons" aria-label="product list button" data-toggle="tab" data-target="#product_list">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewbox="0 0 13 8">
                                        <g id="Group_14700" data-name="Group 14700" transform="translate(-1376 -478)">
                                          <g transform="translate(12 -2)">
                                            <g id="Group_1326" data-name="Group 1326">
                                              <rect id="Rectangle_5729" data-name="Rectangle 5729" width="3" height="2" transform="translate(1364 483)" fill="currentColor"></rect>
                                              <rect id="Rectangle_5730" data-name="Rectangle 5730" width="9" height="2" transform="translate(1368 483)" fill="currentColor"></rect>
                                            </g>
                                            <g id="Group_1328" data-name="Group 1328" transform="translate(0 -3)">
                                              <rect id="Rectangle_5729-2" data-name="Rectangle 5729" width="3" height="2" transform="translate(1364 483)" fill="currentColor"></rect>
                                              <rect id="Rectangle_5730-2" data-name="Rectangle 5730" width="9" height="2" transform="translate(1368 483)" fill="currentColor"></rect>
                                            </g>
                                            <g id="Group_1327" data-name="Group 1327" transform="translate(0 -1)">
                                              <rect id="Rectangle_5731" data-name="Rectangle 5731" width="3" height="2" transform="translate(1364 487)" fill="currentColor"></rect>
                                              <rect id="Rectangle_5732" data-name="Rectangle 5732" width="9" height="2" transform="translate(1368 487)" fill="currentColor"></rect>
                                            </g>
                                          </g>
                                        </g>
                                      </svg>
                                </button>
                            </div>
                        </div>
                        <div class="product__view--mode__list product__view--search d-none d-lg-block">
                            <form class="product__view--search__form" action="#">
                                <label>
                                    <input class="product__view--search__input border-0" placeholder="Search by" type="text">
                                </label>
                                <button class="product__view--search__btn" aria-label="shop button" type="submit">
                                    <svg class="product__view--search__btn--svg" xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewbox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></path><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"></path></svg>  
                                </button>
                            </form>
                        </div> -->
                    </div>
                    <p class="product__showing--count">Showing 1–9 of 21 results</p>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-4">
                        <div class="shop__sidebar--widget widget__area d-none d-lg-block">
                          <div class="single__widget widget__bg">
                            <h2 class="widget__title h3">Categories</h2>
                            <ul class="widget__categories--menu">
                                @foreach (categories() as $category)
                                    <li class="widget__categories--menu__list">
                                        <label class="widget__categories--menu__label d-flex align-items-center" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#sub-{{ $category->id }}" 
                                            aria-expanded="false">
                                            <img class="widget__categories--menu__img" 
                                                src="{{ asset($category->image ?? 'assets/img/product/small-product1.png') }}" 
                                                alt="{{ $category->name }}">
                                            <span class="widget__categories--menu__text">{{ $category->name }}</span>
                                            <svg class="widget__categories--menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12" height="8">
                                                <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z" transform="translate(-6 -8.59)" fill="currentColor"></path>
                                            </svg>
                                        </label>

                                        <ul class="widget__categories--sub__menu collapse" id="sub-{{ $category->id }}">
                                            @foreach ($category->subCategories as $sub)
                                                <li class="widget__categories--sub__menu--list">
                                                    <a class="widget__categories--sub__menu--link d-flex align-items-center" 
                                                    href="{{ route('front.shop', [$category->slug, $sub->slug]) }}">
                                                        <img class="widget__categories--sub__menu--img" 
                                                            src="{{ asset($sub->image ?? 'assets/img/product/small-product2.png') }}" 
                                                            alt="{{ $sub->name }}">
                                                        <span class="widget__categories--sub__menu--text">{{ $sub->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                            <!-- <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">Dietary Needs</h2>
                                <ul class="widget__form--check">
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check1">Denim shirt</label>
                                        <input class="widget__form--check__input" id="check1" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check2">Need Winter</label>
                                        <input class="widget__form--check__input" id="check2" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check3">Fashion Trends</label>
                                        <input class="widget__form--check__input" id="check3" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check4">Oversize Cotton</label>
                                        <input class="widget__form--check__input" id="check4" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check5">Baking material</label>
                                        <input class="widget__form--check__input" id="check5" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                </ul>
                            </div> -->
                            <div class="single__widget price__filter widget__bg">
                            <h2 class="widget__title h3">Filter By Price</h2>

                            <form id="price-filter-form" class="price__filter--form" method="GET" action="">
                                <div class="price__filter--form__inner mb-15 d-flex align-items-center">
                                    <div class="price__filter--group">
                                        <label class="price__filter--label">From</label>
                                        <div class="price__filter--input border-radius-5 d-flex align-items-center">
                                            <span class="price__filter--currency">{{ config('settings.currency_symbol') }}</span>
                                            <input class="price__filter--input__field border-0" 
                                                id="priceMin"
                                                name="price_min" 
                                                type="number" 
                                                value="{{ $priceMin }}" 
                                                min="0" 
                                                placeholder="0">
                                        </div>
                                    </div>
                                    <div class="price__divider"><span>-</span></div>
                                    <div class="price__filter--group">
                                        <label class="price__filter--label">To</label>
                                        <div class="price__filter--input border-radius-5 d-flex align-items-center">
                                            <span class="price__filter--currency">{{ config('settings.currency_symbol') }}</span>
                                            <input class="price__filter--input__field border-0" 
                                                id="priceMax"
                                                name="price_max" 
                                                type="number" 
                                                value="{{ $priceMax }}" 
                                                placeholder="{{ maxPrice() }}" 
                                                max="{{ maxPrice() }}">
                                        </div>
                                    </div>
                                </div>
                                <button class="price__filter--btn primary__btn" type="submit">Apply Filter</button>
                            </form>
                        </div>
                            <!-- <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">Top Rated Product</h2>
                                <div class="product__grid--inner">
                                    <div class="product__items product__items--grid d-flex align-items-center">
                                        <div class="product__items--grid__thumbnail position__relative">
                                            <a class="product__items--link" href="product-details.html">
                                                <img class="product__items--img product__primary--img" src="assets/img/product/small-product1.png" alt="product-img">
                                                <img class="product__items--img product__secondary--img" src="assets/img/product/small-product2.png" alt="product-img">
                                            </a>
                                        </div>
                                        <div class="product__items--grid__content">
                                            <h3 class="product__items--content__title h4"><a href="product-details.html">Women Fish Cut</a></h3>
                                            <div class="product__items--price">
                                                <span class="current__price">$110</span>
                                                <span class="price__divided"></span>
                                                <span class="old__price">$78</span>
                                            </div>
                                            <ul class="rating product__rating d-flex">
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product__items product__items--grid d-flex align-items-center">
                                        <div class="product__items--grid__thumbnail position__relative">
                                            <a class="product__items--link" href="product-details.html">
                                                <img class="product__items--img product__primary--img" src="assets/img/product/small-product3.png" alt="product-img">
                                                <img class="product__items--img product__secondary--img" src="assets/img/product/small-product4.png" alt="product-img">
                                            </a>
                                        </div>
                                        <div class="product__items--grid__content">
                                            <h3 class="product__items--content__title h4"><a href="product-details.html">Gorgeous Granite is</a></h3>
                                            <div class="product__items--price">
                                                <span class="current__price">$140</span>
                                                <span class="price__divided"></span>
                                                <span class="old__price">$115</span>
                                            </div>
                                            <ul class="rating product__rating d-flex">
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                        <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product__items product__items--grid d-flex align-items-center">
                                        <div class="product__items--grid__thumbnail position__relative">
                                            <a class="product__items--link" href="product-details.html">
                                                <img class="product__items--img product__primary--img" src="assets/img/product/small-product5.png" alt="product-img">
                                                <img class="product__items--img product__secondary--img" src="assets/img/product/small-product6.png" alt="product-img">
                                            </a>
                                        </div>
                                        <div class="product__items--grid__content">
                                            <h4 class="product__items--content__title"><a href="product-details.html">Durable A Steel </a></h4>
                                            <div class="product__items--price">
                                                <span class="current__price">$160</span>
                                                <span class="price__divided"></span>
                                                <span class="old__price">$118</span>
                                            </div>
                                                        <ul class="rating product__rating d-flex">
                                                            <li class="rating__list">
                                                                <span class="rating__list--icon">
                                                                    <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                                    <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                            </li>
                                                            <li class="rating__list">
                                                                <span class="rating__list--icon">
                                                                    <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                                    <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                            </li>
                                                            <li class="rating__list">
                                                                <span class="rating__list--icon">
                                                                    <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                                    <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                            </li>
                                                            <li class="rating__list">
                                                                <span class="rating__list--icon">
                                                                    <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                                    <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                            </li>
                                                            <li class="rating__list">
                                                                <span class="rating__list--icon">
                                                                    <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg" width="14.105" height="14.732" viewbox="0 0 10.105 9.732">
                                                                    <path data-name="star - Copy" d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z" transform="translate(0 -0.018)" fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                            </li>
                                                        </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        <div class="single__widget widget__bg">
                            <h2 class="widget__title h3">Brands</h2>
                            <ul class="widget__tagcloud">
                                @foreach($brands as $brand)
                                    <li class="widget__tagcloud--list">
                                        <a class="widget__tagcloud--link" 
                                        href="">
                                        {{ $brand->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8">
                        <div class="shop__product--wrapper">
                            <div class="tab_content">
                                <div id="product_grid" class="tab_pane active show">
                                    <div class="product__section--inner product__grid--inner">
                                        <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-2 mb--n30">
                                            @if(count($products) > 0)
                                            @foreach($products as $product)
                                                <div class="col mb-30">
                                                <div class="product__items">
                                                    <div class="product__items--thumbnail" style="height:280px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                                                        
                                                        <a class="product__items--link" href="{{ route('front.product', $product->slug) }}">
                                                            <img 
                                                                class="product__items--img product__primary--img" 
                                                                src="{{ asset($product->defaultImage->image ?? $product->images->first()->image) }}" 
                                                                alt="{{ $product->alt_image_text }}"
                                                                style="width:100%; height:280px; object-fit:cover; border-radius:10px;"
                                                            >

                                                            @if($product->images->count() > 1)
                                                                <img 
                                                                    class="product__items--img product__secondary--img" 
                                                                    src="{{ asset($product->images[1]->image) }}" 
                                                                    alt="{{ $product->alt_image_text }}"
                                                                    style="width:100%; height:280px; object-fit:cover; border-radius:10px;"
                                                                >
                                                            @endif
                                                        </a>

                                                        @if ($product->created_at->gt(now()->subDays(7)))
                                                            <div class="product__badge">
                                                                <span class="product__badge--items sale">New</span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="product__items--content text-center">
                                                        <span class="product__items--content__subtitle">
                                                            {{ $product->category->name ?? 'Category' }}
                                                        </span>
                                                        <h3 class="product__items--content__title h4">
                                                            <a href="{{ route('front.product', $product->slug) }}">
                                                                {{ $product->name }}
                                                            </a>
                                                        </h3>

                                                        <div class="product__items--price">
                                                            <span class="current__price">{{ config('settings.currency_symbol') }}{{ $product->price }}</span>
                                                            <span class="old__price" style="text-decoration: line-through; color:#999;">
                                                                {{ config('settings.currency_symbol') }}{{ $product->compare_price }}
                                                            </span>
                                                        </div>
                                                         <ul class="rating product__rating d-flex">
                                                      @for ($i = 0; $i < 5; $i++)
                                                        <li class="rating__list">
                                                            <span class="rating__list--icon">
                                                                <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg"
                                                                    width="14.105" height="14.732" viewBox="0 0 10.105 9.732">
                                                                    <path
                                                                        d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                                        transform="translate(0 -0.018)" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </li>
                                                    @endfor
                                                </ul>

                                                {{-- Action buttons --}}
                                                <ul class="product__items--action d-flex">
                                                    <li class="product__items--action__list">
                                                        <button data-bs-sku="{{ $product->sku }}" value="{{ $product->sku }}"
                                                                class="product__items--action__btn quick-add">
                                                            <svg class="product__items--action__btn--svg" xmlns="http://www.w3.org/2000/svg"
                                                                width="22.51" height="20.443" viewBox="0 0 14.706 13.534">
                                                                <path
                                                                    d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z"
                                                                    transform="translate(0 -463.248)" fill="currentColor"></path>
                                                            </svg>
                                                            <span class="add__to--cart__text">+ Add to cart</span>
                                                        </button>
                                                    </li>

                                                    <li class="product__items--action__list">
                                                        <a class="product__items--action__btn" href="wishlist.html">
                                                            ❤️
                                                        </a>
                                                    </li>

                                                    <li class="product__items--action__list">
                                                        <button class="product__items--action__btn" onclick="return loadQuickView('{{ $product->sku }}')">
                                                            👁 
                                                        </button>
                                                    </li>
                                                </ul>
                
                                                    </div>
                                                </div>
                                            </div>

                                            @endforeach
                                        @else
                                            <p>No products found.</p>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pagination__area bg__gray--color">
                                <nav class="pagination justify-content-center">
                                    <ul class="pagination__wrapper d-flex align-items-center justify-content-center">
                                        <li class="pagination__list">
                                            <a href="shop.html" class="pagination__item--arrow  link ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"></path></svg>
                                                <span class="visually-hidden">pagination arrow</span>
                                            </a>
                                        <li>
                                        <li class="pagination__list"><span class="pagination__item pagination__item--current">1</span></li>
                                        <li class="pagination__list"><a href="shop.html" class="pagination__item link">2</a></li>
                                        <li class="pagination__list"><a href="shop.html" class="pagination__item link">3</a></li>
                                        <li class="pagination__list"><a href="shop.html" class="pagination__item link">4</a></li>
                                        <li class="pagination__list">
                                            <a href="shop.html" class="pagination__item--arrow  link ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"></path></svg>
                                                <span class="visually-hidden">pagination arrow</span>
                                            </a>
                                        <li>
                                    </li></ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End shop section -->
       <!-- Fashion CTA Section -->
        <div class="container-fluid bg-icon" style="background-color: #FFC76C; padding-top: 60px; padding-bottom: 60px; margin-bottom: 40px; border-radius: 10px;">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-md-7">
                        <div class="display-5 h1 text-white mb-3">Discover the World of Fashion</div>
                        <p class="text-white mb-0">
                            Step into the world of style and sophistication! Explore our exclusive fashion collection — 
                            from timeless classics to the latest runway trends. Whether it’s chic streetwear, 
                            elegant evening outfits, or everyday essentials, we’ve got everything to elevate your wardrobe. 
                            Experience fashion that defines your personality and makes every moment stylish.
                        </p>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <a class="btn btn-lg btn-secondary rounded-pill py-3 px-5"
                            href="{{ route('front.page', 'contact-us') }}">
                            Explore Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid bg-white py-6 mt-5 mb-5"> <!-- Main container top margin added -->
            <div class="container text-center mb-5">
                <div class="display-5 h1 mb-3">Customer Reviews</div>
                <p class="lead mb-5 f-3">
                    Peoples who enjoy the freshness and goodness of our organic products every day.
                </p>
            </div>
            <div class="container mb-5">
                <div class="blog__section--inner blog__swiper--activation swiper px-2">
                    <div class="swiper-wrapper">
                        @foreach (getTestimonials() as $index => $review)
                            <div class="swiper-slide mb-4"> <!-- Slide margin bottom -->
                                <div class="testimonial-item position-relative p-4 rounded-4 shadow-sm h-100"
                                    style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#FFC76C' }}; margin-bottom:20px;">
                                    <i class="fa fa-quote-left fa-2x text-primary position-absolute top-0 start-0 mt-3 ms-3"></i>
                                    <p class="mb-4 mt-4" style="min-height: 80px;">“{{ $review->comment }}”</p>
                                    <div class="d-flex align-items-center mt-auto">
                                        <img class="flex-shrink-0 rounded-circle" src="{{ asset(config('settings.logo')) }}"
                                            alt="{{ $review->username }}" style="width:60px; height:60px; object-fit:cover;">
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ $review->username }}</h6>
                                            <small class="text-muted">{{ $review->title ?? 'Customer' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Swiper navigation buttons -->
                    <div class="swiper__nav--btn swiper-button-next"></div>
                    <div class="swiper__nav--btn swiper-button-prev"></div>
                </div>
            </div>
        </div>
<script>
    var swiper = new Swiper('.blog__swiper--activation', {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 1, spaceBetween: 15 },
            576: { slidesPerView: 1, spaceBetween: 20 },
            768: { slidesPerView: 2, spaceBetween: 25 },
            992: { slidesPerView: 3, spaceBetween: 30 }
        }
    });
</script>
<!-- Swiper JS Initialization -->
<script>
    var swiper = new Swiper('.blog__swiper--activation', {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            0: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            992: { slidesPerView: 3 },
        }
    });
</script>
        <section class="shipping__section2 shipping__style3 section--padding pt-0">
            <div class="container">
                <div class="shipping__section2--inner shipping__style3--inner d-flex justify-content-between">
                    <div class="shipping__items2 d-flex align-items-center">
                        <div class="shipping__items2--icon">
                            <img src="assets/img/other/shipping1.png" alt="">
                        </div>
                        <div class="shipping__items2--content">
                            <h2 class="shipping__items2--content__title h3">Shipping</h2>
                            <p class="shipping__items2--content__desc">From handpicked sellers</p>
                        </div>
                    </div>
                    <div class="shipping__items2 d-flex align-items-center">
                        <div class="shipping__items2--icon">
                            <img src="assets/img/other/shipping2.png" alt="">
                        </div>
                        <div class="shipping__items2--content">
                            <h2 class="shipping__items2--content__title h3">Payment</h2>
                            <p class="shipping__items2--content__desc">From handpicked sellers</p>
                        </div>
                    </div>
                    <div class="shipping__items2 d-flex align-items-center">
                        <div class="shipping__items2--icon">
                            <img src="assets/img/other/shipping3.png" alt="">
                        </div>
                        <div class="shipping__items2--content">
                            <h2 class="shipping__items2--content__title h3">Return</h2>
                            <p class="shipping__items2--content__desc">From handpicked sellers</p>
                        </div>
                    </div>
                    <div class="shipping__items2 d-flex align-items-center">
                        <div class="shipping__items2--icon">
                            <img src="assets/img/other/shipping4.png" alt="">
                        </div>
                        <div class="shipping__items2--content">
                            <h2 class="shipping__items2--content__title h3">Support</h2>
                            <p class="shipping__items2--content__desc">From handpicked sellers</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End shipping section -->
    </main>
    <script>
        document.getElementById('price-filter-form').addEventListener('submit', function(event) {
            event.preventDefault();

            let min = document.getElementById('priceMin').value;
            let max = document.getElementById('priceMax').value;

            let params = new URLSearchParams(window.location.search);

            if (min) params.set('price_min', min);
            if (max) params.set('price_max', max);

            // Reload with query parameters
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        });
    </script>

   @endsection