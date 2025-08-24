@extends('front.includes.layout')
@section('content')
  <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="display-3 h1 mb-3 animated slideInDown">Create Your Account</div>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">Register</li>
                </ol>
                <p>Join us for early Sale access, new arrivals, and exclusive promotions.</p>
            </nav>
        </div>
    </div>
    <!-- Registration Form Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow p-4 rounded-4">
                        <h3 class="mb-4 text-center">Register</h3>

                        <form id="register-form" method="POST"
                            onsubmit="return handleFormSubmit('.registerForm', '{{ route('account.register') }}')"
                            class="registerForm">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter your first name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="you@example.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    placeholder="Enter your phone number" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Create a password" required>
                            </div>

                            <div class="mb-4">
                                <label for="cpassword" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="cpassword" name="cpassword"
                                    placeholder="Confirm your password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Create Account</button>

                            <div class="text-center mt-4">
                                <p class="mb-0">Already have an account?</p>
                                <a href="{{ route('account.login') }}" class="btn btn-outline-primary mt-2">
                                    Log In Here <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
