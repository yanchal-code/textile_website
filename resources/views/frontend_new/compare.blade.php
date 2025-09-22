@extends('frontend_new.layouts.main')
@section('content')

    <main class="main__content_wrapper">
        
        <!-- Start breadcrumb section -->
        <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <h1 class="breadcrumb__content--title text-white mb-25">Compare</h1>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items"><a class="text-white" href="index.html">Home</a></li>
                                <li class="breadcrumb__content--menu__items"><span class="text-white">Compare</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End breadcrumb section -->

        <!-- Start Compare section -->
        <section class="compare__section section--padding">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="section__heading text-center mb-40">
                            <h2 class="section__heading--maintitle h3">COMPARE PRODUCT PAGE STYLE</h2>
                        </div>
                        <div class="compare__section--inner table-responsive">
                            <table class="compare__table">
                                <thead class="compare__table--header">
                                    <tr class="compare__table--items">
                                        <td class="compare__table--items__child text-center">
                                            <button type="button" class="compare__remove">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24.105" height="24.732" viewbox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"></path></svg>
                                            </button>
                                            <h3 class="compare__product--title h4">Cotton Dress</h3>
                                            <img class="compare__product--thumb" src="{{asset('frontend/img/product/product1.png')}}" alt="compare-image">
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <button type="button" class="compare__remove">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24.105" height="24.732" viewbox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"></path></svg>
                                            </button>
                                            <h3 class="compare__product--title h4">Edna Dress</h3>
                                            <img class="compare__product--thumb" src="{{asset('frontend/img/product/product2.png')}}" alt="compare-image">
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <button type="button" class="compare__remove">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24.105" height="24.732" viewbox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"></path></svg>
                                            </button>
                                            <h3 class="compare__product--title h4">Edna Dress</h3>
                                            <img class="compare__product--thumb" src="{{asset('frontend/img/product/product3.png')}}" alt="compare-image">
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <button type="button" class="compare__remove">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24.105" height="24.732" viewbox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"></path></svg>
                                            </button>
                                            <h3 class="compare__product--title h4">Edna Dress</h3>
                                            <img class="compare__product--thumb" src="{{asset('frontend/img/product/product4.png')}}" alt="compare-image">
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <button type="button" class="compare__remove">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24.105" height="24.732" viewbox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"></path></svg>
                                            </button>
                                            <h3 class="compare__product--title h4">Edna Dress</h3>
                                            <img class="compare__product--thumb" src="{{asset('frontend/img/product/product5.png')}}" alt="compare-image">
                                        </td>
                                    </tr>
                                </thead>
                                <tbody class="compare__table--body">
                                    <tr class="compare__table--items">
                                        <td class="compare__table--items__child text-center">
                                            <span class="compare__product--price">$89,00</span>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <span class="compare__product--price">$89,00</span>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <span class="compare__product--price">$89,00</span>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <span class="compare__product--price">$89,00</span>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <span class="compare__product--price">$89,00</span>
                                        </td>
                                    </tr>
                                    <tr class="compare__table--items">
                                        <th class="compare__table--items__child--header">Product Description</th>
                                        <th class="compare__table--items__child--header">Product Description</th>
                                        <th class="compare__table--items__child--header">Product Description</th>
                                        <th class="compare__table--items__child--header">Product Description</th>
                                        <th class="compare__table--items__child--header">Product Description</th>
                                    </tr>
                                    <tr class="compare__table--items">
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__description">Lorem ipsum dolor sit, amet  elit. Iusto excepturi fugiat vitae the are commodi nihil saepe itaque! name Corporis, quaerat layout.</p>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__description">Lorem ipsum dolor sit, amet  elit. Iusto excepturi fugiat vitae the are commodi nihil saepe itaque! name Corporis, quaerat layout.</p>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__description">Lorem ipsum dolor sit, amet  elit. Iusto excepturi fugiat vitae the are commodi nihil saepe itaque! name Corporis, quaerat layout.</p>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__description">Lorem ipsum dolor sit, amet  elit. Iusto excepturi fugiat vitae the are commodi nihil saepe itaque! name Corporis, quaerat layout.</p>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__description">Lorem ipsum dolor sit, amet  elit. Iusto excepturi fugiat vitae the are commodi nihil saepe itaque! name Corporis, quaerat layout.</p>
                                        </td>
                                    </tr>
                                    <tr class="compare__table--items">
                                        <th class="compare__table--items__child--header">Availability</th>
                                        <th class="compare__table--items__child--header">Availability</th>
                                        <th class="compare__table--items__child--header">Availability</th>
                                        <th class="compare__table--items__child--header">Availability</th>
                                        <th class="compare__table--items__child--header">Availability</th>
                                    </tr>
                                    <tr class="compare__table--items">
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__instock text__secondary">In stock</p>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__instock text__secondary">In stock</p>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__instock text__secondary">In stock</p>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__instock text__secondary">In stock</p>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <p class="compare__instock text__secondary">In stock</p>
                                        </td>
                                    </tr>
                                    <tr class="compare__table--items">
                                        <td class="compare__table--items__child text-center">
                                            <a class="compare__cart--btn primary__btn" href="cart.html">Add to Cart</a>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <a class="compare__cart--btn primary__btn" href="cart.html">Add to Cart</a>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <a class="compare__cart--btn primary__btn" href="cart.html">Add to Cart</a>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <a class="compare__cart--btn primary__btn" href="cart.html">Add to Cart</a>
                                        </td>
                                        <td class="compare__table--items__child text-center">
                                            <a class="compare__cart--btn primary__btn" href="cart.html">Add to Cart</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Compare section -->

        <!-- Start shipping section -->
        <section class="shipping__section2 shipping__style3 section--padding pt-0">
            <div class="container">
                <div class="shipping__section2--inner shipping__style3--inner d-flex justify-content-between">
                    <div class="shipping__items2 d-flex align-items-center">
                        <div class="shipping__items2--icon">
                            <img src="{{asset('frontend/img/other/shipping1.png')}}" alt="">
                        </div>
                        <div class="shipping__items2--content">
                            <h2 class="shipping__items2--content__title h3">Shipping</h2>
                            <p class="shipping__items2--content__desc">From handpicked sellers</p>
                        </div>
                    </div>
                    <div class="shipping__items2 d-flex align-items-center">
                        <div class="shipping__items2--icon">
                            <img src="{{asset('frontend/img/other/shipping2.png')}}" alt="">
                        </div>
                        <div class="shipping__items2--content">
                            <h2 class="shipping__items2--content__title h3">Payment</h2>
                            <p class="shipping__items2--content__desc">From handpicked sellers</p>
                        </div>
                    </div>
                    <div class="shipping__items2 d-flex align-items-center">
                        <div class="shipping__items2--icon">
                            <img src="{{asset('frontend/img/other/shipping3.png')}}" alt="">
                        </div>
                        <div class="shipping__items2--content">
                            <h2 class="shipping__items2--content__title h3">Return</h2>
                            <p class="shipping__items2--content__desc">From handpicked sellers</p>
                        </div>
                    </div>
                    <div class="shipping__items2 d-flex align-items-center">
                        <div class="shipping__items2--icon">
                            <img src="{{asset('frontend/img/other/shipping4.png')}}" alt="">
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

@endsection