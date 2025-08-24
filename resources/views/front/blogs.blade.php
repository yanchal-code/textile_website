@extends('front.includes.layout')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="display-3 h1 mb-3 animated slideInDown">Blogs</div>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">Blogs</li>

                </ol>
            </nav>
        </div>
    </div>


    <!-- Blog Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s"
                style="max-width: 500px;">
                <div class="display-5 h1 mb-3">Latest Blog</div>
                <p>Explore tips, insights, and stories about organic farming, sustainability, and healthy living
                    directly from our experts.</p>
            </div>
            <div class="row g-4">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="blog-img-wrapper">
                            <a href="{{ route('front.blog.show', $blog->slug) }}">
                                <img class="img-fluid blog-img" src="{{ asset($blog->image) }}"
                                    alt="{{ $blog->alt_image_text }}">
                            </a>

                        </div>
                        <div class="bg-light p-4">
                            <a class="d-block h5 lh-base mb-4" href="{{ route('front.blog.show', $blog->slug) }}">
                                {{ $blog->title }}
                            </a>
                            <p class="mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 100) }}
                            </p>
                            <div class="text-muted border-top pt-4">
                                <small class="me-3"><i
                                        class="fa fa-user text-primary me-2"></i>{{ $blog->author?->name }}</small>
                                <small class="me-3"><i
                                        class="fa fa-calendar text-primary me-2"></i>{{ $blog->created_at->format('d M, Y') }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Blog End -->
@endsection
