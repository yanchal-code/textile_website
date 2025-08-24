@extends('front.includes.layout')

@section('metaTags')
    <title>{{ $page->meta_title ?? $page->name }}</title>
    <meta name="title" content="{{ $page->meta_title ?? $page->name }}">
    <meta name="description" content="{{ $page->meta_description }}">
    <meta name="keywords" content="{{ $page->meta_keywords }}">
@endsection

@section('content')
    @if ($page->slug == 'contact-us')
        <!-- Page Header Start -->
        <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container">
                <div class="display-3 h1 mb-3 animated slideInDown">Contact Us</div>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                        <li class="breadcrumb-item">pages</li>
                        <li class="breadcrumb-item text-dark active" aria-current="page">contact-us</li>

                    </ol>
                </nav>
            </div>
        </div>

        <!-- Contact 2 Section -->
        <section id="contact-2" class="contact-2 section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-6">
                        <div class="info-item d-flex flex-column justify-content-center align-items-center"
                            data-aos="fade-up" data-aos-delay="200">
                            <i class="bi bi-geo-alt"></i>
                            <h3>Address</h3>
                            <p>{{ config('settings.address') }}</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="info-item d-flex flex-column justify-content-center align-items-center"
                            data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-telephone"></i>
                            <h3>Call Us</h3>
                            <p><a href="tel:{{ config('settings.phone') }}">{{ config('settings.phone') }}</a></p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="info-item d-flex flex-column justify-content-center align-items-center"
                            data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-envelope"></i>
                            <h3>Email Us</h3>
                            <p><a href="mailto:{{ config('settings.email') }}">{{ config('settings.email') }}</a></p>
                        </div>
                    </div><!-- End Info Item -->

                </div>

                <div class="row gy-4 mt-1">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus"
                            frameborder="0" style="border:0; width: 100%; height: 400px;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div><!-- End Google Maps -->

                    <div class="col-lg-6">
                        <form method="post" class="php-email-form form-contact needs-validation" novalidate
                            data-aos="fade-up" data-aos-delay="400"
                            onsubmit="return handleFormSubmit('.form-contact', '{{ route('front.sendContactEmail') }}')">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name"
                                        required="">
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">

                                    <button class="btn btn-primary" type="submit">Send Message</button>
                                </div>

                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact 2 Section -->
    @else
        <!-- Page Header Start -->
        <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container">
                <div class="display-3 h1 mb-3 animated slideInDown">{{ $page->name }}</div>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                        <li class="breadcrumb-item">pages</li>
                        <li class="breadcrumb-item text-dark active" aria-current="page">{{ $page->slug }}</li>

                    </ol>
                </nav>
            </div>
        </div>
        <!-- main-page -->
        <section class="flat-spacing-25">
            <div class="container">
                <div class="tf-main-area-page">
                    {!! $page->content !!}
                </div>
            </div>
        </section>
        <!-- /main-page -->
    @endif
@endsection

@section('scripts')
    <script>
        $('#contactFormFront').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            if (form[0].checkValidity() === true) {
                var formData = new FormData(this);
                $.ajax({
                    type: "post",
                    url: "{{ route('front.sendContactEmail') }}",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('#categoryCreatFormSpinner').html(
                            '<span id="slugLoader"><span class="loader"></span> Loading...</span>'
                        );
                    },
                    success: function(response) {
                        $('#categoryCreatFormSpinner').html(
                            '<button type="submit" class="btn ms-2 px-4 btnGradiant">Send Message</button>'
                        )
                        if (response.status === false) {
                            var errorsHtml = '';
                            var errors = response.errors;
                            var count = 1;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorsHtml += '<p>' + count + '. ' + errors[key][0] + '</p>';
                                }
                                count = count + 1;
                            }
                            showNotification(errorsHtml, 'danger', 'html');

                        } else if (response.status === true) {
                            form[0].reset();
                            form.removeClass('was-validated');
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#categoryCreatFormSpinner').html(
                            '<button type="submit" class="btn ms-2 px-4 btnGradiant">Send Message</button>'
                        );

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

            } else {
                form.addClass('was-validated');
            }
        });
    </script>
@endsection
