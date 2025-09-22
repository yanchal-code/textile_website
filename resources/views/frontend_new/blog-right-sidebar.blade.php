@extends('frontend_new.layouts.main')
@section('content')
    <main class="main__content_wrapper">
        
        <!-- Start breadcrumb section -->
        <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <h1 class="breadcrumb__content--title text-white mb-25">Blog Right Sidebar</h1>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items"><a class="text-white" href="index.html">Home</a></li>
                                <li class="breadcrumb__content--menu__items"><span class="text-white">Blog Right Sidebar</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End breadcrumb section -->

        <!-- Start blog section -->
        <section class="blog__section section--padding">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xxl-9 col-xl-8 col-lg-8">
                        <div class="blog__wrapper blog__wrapper--sidebar">
                            <div class="row row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-sm-u-2 row-cols-1 mb--n30">
                                <div class="col mb-30">
                                    <div class="blog__items">
                                        <div class="blog__thumbnail">
                                            <a class="blog__thumbnail--link" href="blog-details.html"><img class="blog__thumbnail--img" src="{{asset('frontend/img/blog/blog1.png')}}" alt="blog-img"></a>
                                        </div>
                                        <div class="blog__content">
                                            <span class="blog__content--meta">February 03, 2022</span>
                                            <h3 class="blog__content--title"><a href="blog-details.html">Fashion Trends In 2022 Styles,
                                                Colors, Accessories</a></h3>
                                            <a class="blog__content--btn primary__btn" href="blog-details.html">Read more </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-30">
                                    <div class="blog__items">
                                        <div class="blog__thumbnail">
                                            <a class="blog__thumbnail--link" href="blog-details.html"><img class="blog__thumbnail--img" src="{{asset('frontend/img/blog/blog2.png')}}" alt="blog-img"></a>
                                        </div>
                                        <div class="blog__content">
                                            <span class="blog__content--meta">February 03, 2022</span>
                                            <h3 class="blog__content--title"><a href="blog-details.html">Meet the Woman Behind Cool
                                                Ethical Label Reformation </a></h3>
                                            <a class="blog__content--btn primary__btn" href="blog-details.html">Read more </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-30">
                                    <div class="blog__items">
                                        <div class="blog__thumbnail">
                                            <a class="blog__thumbnail--link" href="blog-details.html"><img class="blog__thumbnail--img" src="{{asset('frontend/img/blog/blog3.png')}}" alt="blog-img"></a>
                                        </div>
                                        <div class="blog__content">
                                            <span class="blog__content--meta">February 03, 2022</span>
                                            <h3 class="blog__content--title"><a href="blog-details.html">Lauryn Hill Could Make Tulle
                                                Skirt and Cowboy</a></h3>
                                            <a class="blog__content--btn primary__btn" href="blog-details.html">Read more </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-30">
                                    <div class="blog__items">
                                        <div class="blog__thumbnail">
                                            <a class="blog__thumbnail--link" href="blog-details.html"><img class="blog__thumbnail--img" src="{{asset('frontend/img/blog/blog4.png')}}" alt="blog-img"></a>
                                        </div>
                                        <div class="blog__content">
                                            <span class="blog__content--meta">February 03, 2022</span>
                                            <h3 class="blog__content--title"><a href="blog-details.html"> Consectetur adipisicing. magnam commodi doloribus.</a></h3>
                                            <a class="blog__content--btn primary__btn" href="blog-details.html">Read more </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-30">
                                    <div class="blog__items">
                                        <div class="blog__thumbnail">
                                            <a class="blog__thumbnail--link" href="blog-details.html"><img class="blog__thumbnail--img" src="{{asset('frontend/img/blog/blog1.png')}}" alt="blog-img"></a>
                                        </div>
                                        <div class="blog__content">
                                            <span class="blog__content--meta">February 03, 2022</span>
                                            <h3 class="blog__content--title"><a href="blog-details.html">Lorem ipsum, dolor sit amet consectetur are elit.</a></h3>
                                            <a class="blog__content--btn primary__btn" href="blog-details.html">Read more </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-30">
                                    <div class="blog__items">
                                        <div class="blog__thumbnail">
                                            <a class="blog__thumbnail--link" href="blog-details.html"><img class="blog__thumbnail--img" src="{{asset('frontend/img/blog/blog2.png')}}" alt="blog-img"></a>
                                        </div>
                                        <div class="blog__content">
                                            <span class="blog__content--meta">February 03, 2022</span>
                                            <h3 class="blog__content--title"><a href="blog-details.html">Meet the Woman Behind Cool
                                                Ethical Label Reformation </a></h3>
                                            <a class="blog__content--btn primary__btn" href="blog-details.html">Read more </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pagination__area bg__gray--color">
                                <nav class="pagination justify-content-center">
                                    <ul class="pagination__wrapper d-flex align-items-center justify-content-center">
                                        <li class="pagination__list">
                                            <a href="blog.html" class="pagination__item--arrow  link ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"></path></svg>
                                                <span class="visually-hidden">pagination arrow</span>
                                            </a>
                                        <li>
                                        <li class="pagination__list"><span class="pagination__item pagination__item--current">1</span></li>
                                        <li class="pagination__list"><a href="blog.html" class="pagination__item link">2</a></li>
                                        <li class="pagination__list"><a href="blog.html" class="pagination__item link">3</a></li>
                                        <li class="pagination__list"><a href="blog.html" class="pagination__item link">4</a></li>
                                        <li class="pagination__list">
                                            <a href="blog.html" class="pagination__item--arrow  link ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"></path></svg>
                                                <span class="visually-hidden">pagination arrow</span>
                                            </a>
                                        <li>
                                    </li></ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-lg-4">
                        <div class="blog__sidebar--widget left widget__area">
                            <div class="single__widget widget__search widget__bg">
                                <h2 class="widget__title h3">Search</h2>
                                <form class="widget__search--form" action="#">
                                    <label>
                                        <input class="widget__search--form__input" placeholder="Search..." type="text">
                                    </label>
                                    <button class="widget__search--form__btn" aria-label="search button" type="button">
                                        <svg class="product__items--action__btn--svg" xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewbox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></path><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"></path></svg>
                                    </button>
                                </form>
                            </div>
                            <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">Categories</h2>
                                <ul class="widget__categories--menu">
                                    <li class="widget__categories--menu__list">
                                        <label class="widget__categories--menu__label d-flex align-items-center">
                                            <img class="widget__categories--menu__img" src="{{asset('frontend/img/product/small-product1.png')}}" alt="categories-img">
                                            <span class="widget__categories--menu__text">Denim Jacket</span>
                                            <svg class="widget__categories--menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12.355" height="8.394">
                                                <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z" transform="translate(-6 -8.59)" fill="currentColor"></path>
                                            </svg>
                                        </label>
                                        <ul class="widget__categories--sub__menu">
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product2.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Jacket, Women</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product3.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Woolend Jacket</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product4.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Western denim</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product5.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Mini Dresss</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="widget__categories--menu__list">
                                        <label class="widget__categories--menu__label d-flex align-items-center">
                                            <img class="widget__categories--menu__img" src="{{asset('frontend/img/product/small-product2.png')}}" alt="categories-img">
                                            <span class="widget__categories--menu__text">Oversize Cotton</span>
                                            <svg class="widget__categories--menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12.355" height="8.394">
                                                <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z" transform="translate(-6 -8.59)" fill="currentColor"></path>
                                            </svg>
                                        </label>
                                        <ul class="widget__categories--sub__menu">
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product2.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Jacket, Women</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product3.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Woolend Jacket</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product4.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Western denim</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product5.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Mini Dresss</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="widget__categories--menu__list">
                                        <label class="widget__categories--menu__label d-flex align-items-center">
                                            <img class="widget__categories--menu__img" src="{{asset('frontend/img/product/small-product3.png')}}" alt="categories-img">
                                            <span class="widget__categories--menu__text">Dairy & chesse</span>
                                            <svg class="widget__categories--menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12.355" height="8.394">
                                                <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z" transform="translate(-6 -8.59)" fill="currentColor"></path>
                                            </svg>
                                        </label>
                                        <ul class="widget__categories--sub__menu">
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product2.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Jacket, Women</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product3.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Woolend Jacket</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product4.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Western denim</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product5.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Mini Dresss</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="widget__categories--menu__list">
                                        <label class="widget__categories--menu__label d-flex align-items-center">
                                            <img class="widget__categories--menu__img" src="{{asset('frontend/img/product/small-product4.png')}}" alt="categories-img">
                                            <span class="widget__categories--menu__text">Shoulder Bag</span>
                                            <svg class="widget__categories--menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12.355" height="8.394">
                                                <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z" transform="translate(-6 -8.59)" fill="currentColor"></path>
                                            </svg>
                                        </label>
                                        <ul class="widget__categories--sub__menu">
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product2.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Jacket, Women</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product3.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Woolend Jacket</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product4.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Western denim</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product5.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Mini Dresss</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="widget__categories--menu__list">
                                        <label class="widget__categories--menu__label d-flex align-items-center">
                                            <img class="widget__categories--menu__img" src="{{asset('frontend/img/product/small-product5.png')}}" alt="categories-img">
                                            <span class="widget__categories--menu__text">Denim Jacket</span>
                                            <svg class="widget__categories--menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12.355" height="8.394">
                                                <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z" transform="translate(-6 -8.59)" fill="currentColor"></path>
                                            </svg>
                                        </label>
                                        <ul class="widget__categories--sub__menu">
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product2.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Jacket, Women</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product3.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Woolend Jacket</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product4.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Western denim</span>
                                                </a>
                                            </li>
                                            <li class="widget__categories--sub__menu--list">
                                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="blog-details.html">
                                                    <img class="widget__categories--sub__menu--img" src="{{asset('frontend/img/product/small-product5.png')}}" alt="categories-img">
                                                    <span class="widget__categories--sub__menu--text">Mini Dresss</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="single__widget widget__bg">
                               <h2 class="widget__title h3">Post Article</h2>
                                <div class="product__grid--inner">
                                    <div class="product__items product__items--grid d-flex align-items-center">
                                        <div class="product__items--grid__thumbnail position__relative">
                                            <a class="product__items--link" href="blog-details.html">
                                                <img class="product__grid--items__img product__primary--img" src="{{asset('frontend/img/product/small-product2.png')}}" alt="product-img">
                                                <img class="product__grid--items__img product__secondary--img" src="{{asset('frontend/img/product/small-product3.png')}}" alt="product-img">
                                            </a>
                                        </div>
                                        <div class="product__items--grid__content">
                                            <h3 class="product__items--content__title h4"><a href="blog-details.html">Women Fish Cut</a></h3>
                                            <span class="meta__deta">February 03, 2022</span>
                                        </div>
                                    </div>
                                    <div class="product__items product__items--grid d-flex align-items-center">
                                        <div class="product__items--grid__thumbnail position__relative">
                                            <a class="product__items--link" href="blog-details.html">
                                                <img class="product__grid--items__img product__primary--img" src="{{asset('frontend/img/product/small-product1.png')}}" alt="product-img">
                                                <img class="product__grid--items__img product__secondary--img" src="{{asset('frontend/img/product/small-product2.png')}}" alt="product-img">
                                            </a>
                                        </div>
                                        <div class="product__items--grid__content">
                                            <h3 class="product__items--content__title h4"><a href="blog-details.html">Gorgeous Granite is</a></h3>
                                            <span class="meta__deta">February 03, 2022</span>
                                        </div>
                                    </div>
                                    <div class="product__items product__items--grid d-flex align-items-center">
                                        <div class="product__items--grid__thumbnail position__relative">
                                            <a class="product__items--link" href="blog-details.html">
                                                <img class="product__grid--items__img product__primary--img" src="{{asset('frontend/img/product/small-product5.png')}}" alt="product-img">
                                                <img class="product__grid--items__img product__secondary--img" src="{{asset('frontend/img/product/small-product4.png')}}" alt="product-img">
                                            </a>
                                        </div>
                                        <div class="product__items--grid__content">
                                            <h3 class="product__items--content__title h4"><a href="blog-details.html">Durable A Steel</a></h3>
                                            <span class="meta__deta">February 03, 2022</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">Brands</h2>
                                <ul class="widget__tagcloud">
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="blog-details.html">Jacket</a></li>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="blog-details.html">Women</a></li>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="blog-details.html">Oversize</a></li>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="blog-details.html">Cotton </a></li>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="blog-details.html">Shoulder </a></li>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="blog-details.html">Winter</a></li>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="blog-details.html">Accessories</a></li>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="blog-details.html">Dress </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End blog section -->

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