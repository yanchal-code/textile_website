<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title>{{ config('settings.name') }} - Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="{{ asset(config('settings.faviconImage')) }}" rel="icon">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('admin-assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link href="{{ asset('admin-assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @yield('headTag')

    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
        }

        a {
            text-decoration: none;
        }

        .profile_icon {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }

        @media (max-width: 576px) {
            .profile_icon {
                width: 30px;
                height: 30px;
            }
        }

        .img_input_preview {
            height: 100px;
        }

        .tdhtml img {
            max-width: 55px;
        }

        table,
        td,
        tr {
            text-align: center;
        }

        select,
        option,
        label {
            cursor: pointer;
        }

 /* General form styling */
        .custom-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


    </style>

</head>

<body>

    @include('admin.includes.notification')

    <div class="container-fluid px-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show position-fixed translate-middle bg-light w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <div class="sidebar pe-3 pt-lg-2">
            <h5 class="text-center mt-md-0 mt-5 pt-5 pt-md-0"><a href="/">
                    <img src="{{ asset(config('settings.logo')) }}" class=" d-lg-inline d-none" alt=""
                        style="max-width: 150px; padding-bottom:5px;"></a></h5>

            <nav class="navbar navbar-light">
                <div class="navbar-nav w-100">

                    @include('admin.includes.sidebar')

                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav style="box-shadow: 2px 1px 2px 0 rgba(0, 0, 0, 0.1);"
                class="navbar px-1 px-lg-2 navbar-light bg-light-custom navbar-expand sticky-top py-0">
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>

                <div class="text-center ms-2">
                    {{ config('settings.name') }}

                </div>

                <div class="navbar-nav align-items-center ms-auto">

                    @include('admin.includes.nav')

                </div>

            </nav>
            <!-- Navbar End -->

            <!-- Content Start -->
            <div style="min-height: 100vh;" class="container-fluid bg-light px-0 pt-4 px-lg-1">
                @yield('content')
            </div>

        </div>

    </div>

    {{-- modal for tdhtml view --}}
    <div class="modal fade" id="myModal" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="body">
                        <div id="viewTd">

                        </div>
                    </div>
                    <div id="modalDiscription" class="p-2 text-center d-none"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dynamic Modal --}}
    <div class="modal fade" id="dynamicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="dynamicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="dynamicModalLabel">Dynamic Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="dynamicModalBody">
                    <!-- Content will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.min.js"></script>

    <script src="{{ asset('admin-assets/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('admin-assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('admin-assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('admin-assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('admin-assets/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin-assets/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('admin-assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/main.js') }}"></script>

    <script>
        document.querySelectorAll('.dropdown-menu').forEach(function(dropdown) {
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

    @yield('scripts')


    <script>
        $(document).ready(function() {
            $('body').on('change', '.img_input', function() {
                var fileInput = $(this);
                var file = fileInput[0].files[0];
                var previewContainer = fileInput.closest('div');
                var previewImg = previewContainer.find('.img_input_preview');

                if (previewImg.length === 0) {
                    previewImg = $('<img>').addClass('img_input_preview img-fluid mt-2');
                    previewContainer.append(previewImg);
                }
                if (file && file.type.startsWith('image/')) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        previewImg.attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.attr('src', '').hide();
                }
            });

            $(document).on('click', '.showPassword', function() {
                const input = $(this).closest('div').find('input');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    $(this).addClass('fa-grin-alt');
                    $(this).removeClass('fa-grin-beam');
                } else {
                    input.attr('type', 'password');
                    $(this).removeClass('fa-grin-alt');
                    $(this).addClass('fa-grin-beam');
                }
            });

            $(document).on('click', '.tdhtml', function() {

                var html = $(this).html();
                $('#viewTd').html(html);
                $('#myModal').modal('show');

            });
        });

        function loadModalContent(route, modalTitle = 'Dynamic Modal') {

            $('#dynamicModal').modal('show');

            $('#dynamicModalLabel').text(modalTitle);

            $('#dynamicModalBody').html(
                '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );
            $.ajax({
                type: "GET",
                url: route,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#dynamicModalBody').html(response);
                },
                error: function(xhr, status, error) {
                    const errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                        "An error occurred while loading the content.";
                    $('#dynamicModalBody').html(
                        `<div class="alert alert-danger">${errorMessage}</div>`);
                }
            });
        }

        function handleFormSubmit(formSelector, url) {
            var form = $(formSelector);
            var submitButton = form.find('button[type="submit"]');
            var originalButtonHtml = submitButton.html();

            event.preventDefault();

            if (form[0].checkValidity() === true) {
                var formData = new FormData(form[0]);

                $.ajax({
                    type: form.attr('method'),
                    url: url,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        submitButton.html(
                            '<span class="spinner-border spinner-border-sm" role="status"></span>'
                        );
                    },

                    success: function(response) {
                        if (response.status === 'validation') {
                            var errorsHtml = '';
                            var errors = response.errors;
                            var count = 1;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorsHtml += `<p>${count}. ${errors[key][0]}</p>`;
                                }
                                count++;
                            }
                            showNotification(errorsHtml, 'danger', 'html');
                        } else if (response.status === true) {
                            showNotification(response.message, 'success', 'text');
                            if (response.redirect_url) {
                                setTimeout(function() {
                                    window.location.href = response.redirect_url;
                                }, 2000);
                            }
                            if (response.reload) {
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            }

                        } else if (response.status === false) {
                            showNotification(response.message, 'danger', 'text');
                        }


                        if (response.reload == true) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        }

                        if (response.reset) {

                            form[0].reset();
                            form.removeClass('was-validated');
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = "An error occurred.";
                        try {
                            var responseJson = JSON.parse(xhr.responseText);
                            errorMessage = responseJson.message || errorMessage;
                        } catch (e) {
                            errorMessage = `Error: ${xhr.status} ${xhr.statusText}`;
                        }
                        showNotification(errorMessage, 'danger', 'text');
                    },
                    complete: function() {
                        submitButton.html(originalButtonHtml);
                    }
                });
            } else {
                form.addClass('was-validated');
            }

            return false;
        }
    </script>
</body>

</html>
