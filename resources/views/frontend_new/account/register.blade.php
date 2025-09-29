@extends('frontend_new.layouts.main')
@section('content')
  <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <h1 class="breadcrumb__content--title text-white mb-25">Account Page</h1>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items"><a class="text-white" href="/">Home</a></li>
                                <li class="breadcrumb__content--menu__items"><span class="text-white">Register</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
     </section>
    <!-- Registration Form Section -->
    <section class="my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="account__login register shadow p-4 rounded-4">
                    <div class="account__login--header mb-25 text-center">
                        <h2 class="account__login--header__title h3 mb-10">Create an Account</h2>
                        <p class="account__login--header__desc">Register here if you are a new customer</p>
                    </div>

                    <form id="register-form" method="POST"
                        onsubmit="return handleFormSubmit('.registerForm', '{{ route('account.register') }}')"
                        class="registerForm account__login--inner">
                        @csrf

                        <input class="account__login--input" id="name" name="name" type="text"
                            placeholder="Full Name" required>

                        <input class="account__login--input" id="email" name="email" type="email"
                            placeholder="Email Address" required>

                        <input class="account__login--input" id="phone" name="phone" type="tel"
                            placeholder="Phone Number" required>

                        <input class="account__login--input" id="password" name="password" type="password"
                            placeholder="Password" required>

                        <input class="account__login--input" id="cpassword" name="cpassword" type="password"
                            placeholder="Confirm Password" required>

                        <div class="account__login--remember position__relative mb-3">
                            <input class="checkout__checkbox--input" id="terms" type="checkbox" required>
                            <span class="checkout__checkbox--checkmark"></span>
                            <label class="checkout__checkbox--label login__remember--label" for="terms">
                                I have read and agree to the terms & conditions
                            </label>
                        </div>

                        <button type="submit" class="account__login--btn primary__btn mb-10 w-100">
                            Submit & Register
                        </button>

                        <div class="text-center mt-3">
                            <p class="mb-2">Already have an account?</p>
                            <a href="{{ route('account.login') }}" class="btn btn-outline-primary">
                                Log In Here
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
