 <meta charset="utf-8">

 @hasSection('metaTags')
     @yield('metaTags')
 @else
     <title>{{ config('settings.name') }}</title>
     <meta name="title" content="{{ config('settings.name') }}">
     <meta name="description" content="{{ config('settings.meta_description') }}">
     <meta name="keywords" content="{{ config('settings.meta_keywords') }}">
 @endif

 <meta name="author" content="{{ config('settings.name') }}">

 <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <link rel="shortcut icon" href="{{ asset(config('settings.faviconImage')) }}">
 <link rel="apple-touch-icon-precomposed" href="{{ asset(config('settings.faviconImage')) }}">


 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

 <!-- Font Awesome -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

 <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

 <!-- Google Web Fonts -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Lora:wght@600;700&display=swap"
     rel="stylesheet">

 <!-- Icon Font Stylesheet -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

 <!-- Libraries Stylesheet -->
 <link href="{{ asset('assets/lib/animate/animate.min.css') }}" rel="stylesheet">
 <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

 <!-- Customized Bootstrap Stylesheet -->
 <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

 <!-- Template Stylesheet -->
 <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
