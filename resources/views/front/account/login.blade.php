@extends('front.includes.layout')
@section('head')
    <style>
        .heading h1 {
            font-weight: 700;
            font-size: 2.5rem;
        }

        .card {
            border: none;
            background: #fff;
            border-radius: 1rem;
            transition: box-shadow 0.3s ease;
        }

        a.small {
            color: #6f42c1;
        }

        a.small:hover {
            text-decoration: underline;
            color: #563d7c;
        }
    </style>
@endsection
@section('content')
     <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="display-3 h1 mb-3 animated slideInDown">Account</div>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">login</li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center g-5">
                <div class="col-lg-5">
                    <div class="card animated slideInDown shadow p-4 rounded-1" data-aos="fade-up" data-aos-delay="100">
                        <div id="recover" class="d-none">
                            <h4 class="mb-4">Reset your password</h4>
                            <p class="mb-4">We will send you an email to reset your password</p>
                            <form class="forgotPassword" method="POST"
                                onsubmit="return handleFormSubmit('.forgotPassword', '{{ route('account.forgot') }}')">
                                @csrf
                                <div class="mb-3">
                                    <label for="recoverEmail" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="recoverEmail" name="email"
                                        placeholder="Enter your email">
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="javascript:void(0)" onclick="showLoginForm()" class="btn btn-secondary">Log
                                        In</a>

                                    <button type="submit" class="btn btn-primary">Reset Password</button>
                                </div>
                            </form>
                        </div>

                        <div id="login">
                            <h4 class="mb-4"></h4>
                            <form id="login-form" action="{{ route('account.login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="loginEmail" name="email"
                                        value="{{ old('email') }}" placeholder="Enter your email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="loginPassword" name="password"
                                        placeholder="Enter your password" required>
                                </div>
                                <div class="mb-3 text-end">
                                    <a href="javascript:void(0)" onclick="showRecoverForm()"
                                        class="small text-decoration-underline">Forgot your password?</a>

                                </div>
                                <button type="submit" class="btn btn-primary w-100">Log in</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-flex flex-column animated slideInDown justify-content-center align-items-start">
                    <h4 class="mb-3">I'm new here</h4>
                    <p class="mb-4">Sign up for early Sale access plus tailored new arrivals, trends and promotions. To
                        opt out, click unsubscribe in our emails.</p>
                    <a href="{{ route('account.register') }}" class="btn btn-outline-primary">
                        Register <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <script>
        function showRecoverForm() {
            document.getElementById('login').classList.add('d-none');
            document.getElementById('recover').classList.remove('d-none');
        }

        function showLoginForm() {
            document.getElementById('recover').classList.add('d-none');
            document.getElementById('login').classList.remove('d-none');
        }
    </script>
@endsection
