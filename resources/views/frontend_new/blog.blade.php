﻿@extends('frontend_new.layouts.main')
@section('content')

    <main class="main__content_wrapper">
        
        <!-- Start breadcrumb section -->
        <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <h1 class="breadcrumb__content--title text-white mb-25">Blog Grid</h1>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items"><a class="text-white" href="/">Home</a></li>
                                <li class="breadcrumb__content--menu__items"><span class="text-white">Blog Grid</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End breadcrumb section -->

        <!-- Start blog section -->
        <section class="blog__section section--padding">
            <div class="container">
                <div class="section__heading text-center mb-50">
                    <h2 class="section__heading--maintitle">From The Blog</h2>
                </div>
                <div class="blog__section--inner">
                    <div class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-sm-u-2 row-cols-1 mb--n30">
                        @foreach ($blogs as $blog)
                        <div class="col mb-30">
                            <div class="blog__items">
                                <div class="blog__thumbnail">
                                    <a class="blog__thumbnail--link" href="{{ route('front.blog.show', $blog->slug) }}"><img class="blog__thumbnail--img" src="{{ $blog->image ? asset($blog->image) : asset('frontend/img/default-blog.png') }}"style="width: 370px; height: 250px; object-fit: cover;" alt="blog-img"></a>
                                </div>
                                <div class="blog__content">
                                <span class="blog__content--meta">{{ \Carbon\Carbon::parse($blog->created_at)->format('F d, Y') }}</span>
                                    <h3 class="blog__content--title"><a href="{{ route('front.blog.show', $blog->slug) }}">{{ $blog->title ?? 'Title Here' }}
                                        </a></h3>
                                        <a class="blog__content--btn style2 primary__btn" href="{{ route('front.blog.show', $blog->slug) }}">
                                    Read more
                                </a>
                                </div>
                            </div>
                        </div>
                        @endforeach 
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
                                <li class="pagination__list"><a href="/blog" class="pagination__item link">2</a></li>
                                <li class="pagination__list"><a href="/blog" class="pagination__item link">3</a></li>
                                <li class="pagination__list"><a href="/blog" class="pagination__item link">4</a></li>
                                <li class="pagination__list">
                                    <a href="/blog" class="pagination__item--arrow  link ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewbox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"></path></svg>
                                        <span class="visually-hidden">pagination arrow</span>
                                    </a>
                                <li>
                            </li></ul>
                        </nav>
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

  