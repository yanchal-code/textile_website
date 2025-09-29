@extends('frontend_new.layouts.main')
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
    <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <h1 class="breadcrumb__content--title text-white mb-25">Account Page</h1>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items"><a class="text-white" href="/">Home</a></li>
                                <li class="breadcrumb__content--menu__items"><span class="text-white">Account Page</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
     </section>
   <section class="account__section section--padding">
    <div class="container">
        <div class="row justify-content-center">
            <!-- ✅ Login Card -->
            <div class="col-lg-5 col-md-8">
                <div class="account__login">
                    <div id="login">
                        <div class="account__login--header mb-25">
                            <h2 class="account__login--header__title h3 mb-10">Login</h2>
                            <p class="account__login--header__desc">Login if you are a returning customer</p>
                        </div>

                        <div class="account__login--inner">
                            <form id="login-form" action="{{ route('account.login') }}" method="POST">
                                @csrf
                                <input class="account__login--input" type="email" id="loginEmail" name="email"
                                    value="{{ old('email') }}" placeholder="Email address" required>
                                <input class="account__login--input" type="password" id="loginPassword"
                                    name="password" placeholder="Password" required>

                                <div class="account__login--remember d-flex justify-content-between align-items-center mb-20">
                                    <a href="javascript:void(0)" onclick="showRecoverForm()" class="forgot__password">
                                        Forgot your password?
                                    </a>
                                </div>

                                <button class="account__login--btn primary__btn mb-10 w-100" type="submit">
                                    Log In
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- ✅ Forgot Password Form -->
                    <div id="recover" class="d-none">
                        <div class="account__login--header mb-25">
                            <h2 class="account__login--header__title h3 mb-10">Reset your password</h2>
                            <p class="account__login--header__desc">We will send you an email to reset your password</p>
                        </div>

                        <div class="account__login--inner">
                            <form class="forgotPassword" method="POST"
                                onsubmit="return handleFormSubmit('.forgotPassword', '{{ route('account.forgot') }}')">
                                @csrf

                                <input class="account__login--input" type="email" id="recoverEmail" name="email"
                                    placeholder="Enter your email" required>

                                <div class="d-flex justify-content-between mt-3">
                                    <a href="javascript:void(0)" onclick="showLoginForm()" class="account__login--btn primary__btn">
                                        Back to Login
                                    </a>
                                    <button class="account__login--btn primary__btn" type="submit">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ New User Info -->
            <div class="col-lg-5 col-md-8">
                <div class="account__login register mt-4 mt-lg-0">
                    <div class="account__login--header mb-25">
                        <h2 class="account__login--header__title h3 mb-10">I'm New Here</h2>
                        <p class="account__login--header__desc">
                            Sign up for early Sale access plus tailored new arrivals, trends and promotions.
                        </p>
                    </div>
                    <div class="account__login--inner">
                        <a href="{{ route('account.register') }}" class="account__login--btn primary__btn w-100">
                            Register Now <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
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
