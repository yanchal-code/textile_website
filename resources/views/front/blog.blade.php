@extends('front.includes.layout')

@section('metaTags')
    <title>{{ $blog->meta_title ?? $blog->title }}</title>
    <meta name="title" content="{{ $blog->meta_title ?? $blog->name }}">
    <meta name="description" content="{{ $blog->meta_description }}">
    <meta name="keywords" content="{{ $blog->meta_keywords }}">
@endsection

<meta name="twitter:card" content="{{ asset($blog->image) }}">
<meta name="twitter:title" content="{{ $blog->title }}">
<meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($blog->content)) }}">
<meta name="twitter:image" content="{{ asset($blog->image) }}">
<meta name="twitter:url" content="{{ url()->current() }}">

<meta property="og:title" content="{{ $blog->title }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($blog->content)) }}">
<meta property="og:image" content="{{ asset($blog->image) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="article">
<meta property="og:site_name" content="{{ url('/') }}">


@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="display-3 h1 mb-3 animated slideInDown">{{ config('settings.name') }} - blog</div>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                    <li class="breadcrumb-item">Blogs</li>
                    <li class="breadcrumb-item text-dark active" aria-current="page"> {{ Str::limit($blog->title, 30) }}
                    </li>

                </ol>
            </nav>
        </div>
    </div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <!-- Blog Header -->
                <h1 class="fw-bold mb-3">{{ $blog->title }}</h1>
                <p class="text-muted small mb-4">
                    <i class="bi bi-calendar-event me-1"></i> {{ $blog->created_at->format('M d, Y') }}
                    | <i class="bi bi-person-circle me-1"></i> {{ $blog->author?->name ?? 'Admin' }}
                </p>

                <!-- Featured Image -->
                @if ($blog->image)
                    <img src="{{ asset($blog->image) }}" class="img-fluid rounded mb-4 shadow-sm"
                        alt="{{ $blog->alt_image_text ?? $blog->title }}">
                @endif

                <!-- Blog Content -->
                <div class="blog-content mb-5">
                    {!! $blog->content !!}
                </div>

                <!-- Social Share -->
                <div class="d-flex align-items-center mb-5">
                    <span class="fw-bold me-3">Share:</span>
                    <a href="https://facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                       style="background-color: #4267B2;" class="btn btn-primary rounded-circle p-2 text-white fs-5 btn-sm me-2">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}"
                        target="_blank" class="btn btn-info rounded-circle p-2 text-white fs-5 btn-sm me-2">
                        <i class="bi bi-twitter"></i>
                    </a>

                    <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . request()->url()) }}"
                        target="_blank" class="btn btn-success rounded-circle p-2 text-white fs-5 btn-sm">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>

                <!-- Related Blogs -->
                <div class="mt-5">
                    <h4 class="fw-bold mb-4">Related Blogs</h4>
                    <div class="row g-4">
                        @if ($relatedBlogs)
                            @foreach ($relatedBlogs as $related)
                                <div class="col-md-6">
                                    <div class="card h-100 shadow-sm border-0 rounded-3 blog-card">
                                        @if ($related->image)
                                            <a href="{{ route('front.blog.show', $related->slug) }}">
                                                <img src="{{ asset($related->image) }}" class="card-img-top blog-img"
                                                    alt="{{ $related->alt_image_text ?? $related->title }}">
                                            </a>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="fw-bold">
                                                <a href="{{ route('front.blog.show', $related->slug) }}"
                                                    class="text-dark text-decoration-none">
                                                    {{ Str::limit($related->title, 50) }}
                                                </a>
                                            </h6>
                                            <p class="text-muted small mb-2">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ $related->created_at->format('M d, Y') }}
                                            </p>
                                            <p class="card-text small">{!! Str::limit(strip_tags($related->content), 100) !!}</p>
                                            <a href="{{ route('front.blog.show', $related->slug) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill">
                                                Read More →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="p-4 bg-light sticky-top rounded shadow-sm mb-4">
                    <h5 class="fw-bold mb-3">Recent Blogs</h5>
                    <ul class="list-unstyled">
                        @if ($recentBlogs)
                            @foreach ($recentBlogs as $recent)
                                <li class="mb-3">
                                    <a href="{{ route('front.blog.show', $recent->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ Str::limit($recent->title, 60) }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $recent->created_at->format('M d, Y') }}</small>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 15px 0;
        }

        .blog-card {
            transition: all 0.3s ease-in-out;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        }

        .blog-img {
            height: 180px;
            object-fit: cover;
        }
    </style>
@endsection
