@extends('front.includes.layout')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="display-3 h1 mb-3 animated slideInDown">{{ Auth::user()->name }}</div>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="/">Home</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">My Account</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- page-cart -->
    <section class="flat-spacing-11">
        <div class="container-fluid px-lg-5">
            <div class="row justify-center text-center">
                <div class="col-lg-3 mb-4 mb-lg-0 sticky-top top-100">
                    <div class="card shadow-sm border-0 sticky-top">
                        <div class="card-body p-0">
                            <nav class="nav flex-column nav-pill text-start">
                                <a href="{{ route('account.profile') }}"
                                    class=" nav-link {{ request()->routeIs('account.profile') ? ' bg-primary text-white ' : '' }}">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                                <a href="{{ route('account.wishlist') }}"
                                    class="nav-link {{ request()->routeIs('account.wishlist') ? ' bg-primary text-white ' : '' }}">
                                    <i class="bi bi-heart me-2"></i> Wishlist
                                </a>
                                <a href="{{ route('account.logout') }}" class="nav-link text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>


                <div class="col-lg-9">
                    <div class="my-account-content account-order py-4">

                        <div class="my-3">

                            <button id="ProfileUpdateBtn" data-bs-toggle="modal" data-bs-target="#addressUpdate"
                                class="btn btn-primary m-2">Update Default Address</button>
                            <button id="addressUpdateBtn" data-bs-toggle="modal" data-bs-target="#ProfileUpdate"
                                class="btn btn-success ms-2">Update Profile</button>
                        </div>

                        <div class="wrap-account-order table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="bg-secondary text-white">
                                    <tr>
                                        <th scope="col" class="">Order</th>
                                        <th scope="col" class="">Date</th>
                                        <th scope="col" class="">Method</th>
                                        <th scope="col" class="">Status</th>
                                        <th scope="col" class="">Total</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($orders) > 0)
                                        @foreach ($orders as $order)
                                            <tr class="tf-order-item">
                                                <td>#{{ $order->orderId }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y') }}</td>
                                                <td>{{ $order->payment_gateway }}</td>
                                                <td>{{ $order->status }}</td>
                                                <td>{{ $order->currency }} {{ number_format($order->grand_total, 2) }} for
                                                    {{ $order->items->count() }} items</td>
                                                <td class="text-center">
                                                    <a href="{{ route('account.orderDetail', $order->id) }}"
                                                        class="btn btn-primary btn-sm rounded-0 px-3 animate-hover-btn">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                No Data Found
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <!-- page-cart -->
    <div>

        <!-- Profile Update Modal -->
        <div>
            <div class="modal fade" id="ProfileUpdate" tabindex="-1" aria-labelledby="ProfileUpdateLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                    <div class="modal-content bg-light">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST"
                                onsubmit="return handleFormSubmit('.userInfoUpdate', '{{ route('account.updateUserInfo') }}')"
                                class="needs-validation userInfoUpdate" novalidate>
                                <div>
                                    <div class="border-bottom ps-2 mb-2">
                                        <h5 class="mb-0 pt-2 pb-2">User Information</h5>
                                    </div>
                                    <div class="">
                                        <div class="p-4">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold" for="name">Name</label>
                                                    <input required name="name" type="text" class="form-control"
                                                        id="name" value="{{ Auth::user()->name }}" placeholder="Name">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold" for="email">Email</label>
                                                    <input required name="email" type="email" class="form-control"
                                                        id="email" value="{{ Auth::user()->email }}"
                                                        placeholder="Email">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold" for="phone">Phone</label>
                                                    <input required name="phone" type="number" class="form-control"
                                                        id="phone" value="{{ Auth::user()->phone }}"
                                                        placeholder="Phone">
                                                </div>
                                                <div class="col-md-12 text-center text-primary mb-3">
                                                    <div class="form-check d-table m-auto text-center">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="changePassword" id="passwordChangeBtn">
                                                        <label class="form-check-label" for="passwordChangeBtn">Want to
                                                            change Password?</label>
                                                    </div>
                                                </div>
                                                <div id="passwordChange" class="d-none col-md-12">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label fw-bold" for="password">Password</label>
                                                        <input name="password" id="password" type="password"
                                                            class="form-control" placeholder="Password">
                                                        <div class="invalid-feedback">Field is required.</div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label fw-bold" for="newPassword">New
                                                            Password</label>
                                                        <input name="newPassword" id="newPassword" type="password"
                                                            class="form-control" placeholder="New Password">
                                                        <div class="invalid-feedback">Field is required.</div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label fw-bold" for="confirmNewPassword">Confirm
                                                            Password</label>
                                                        <input name="confirmNewPassword" id="confirmNewPassword"
                                                            type="password" class="form-control"
                                                            placeholder="Confirm Password">
                                                        <div class="invalid-feedback">Field is required.</div>
                                                    </div>
                                                </div>
                                                <div class="mt-4 col-md-12 text-center">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer border-0"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Address Update Modal -->
        <div>
            <div class="modal fade" id="addressUpdate" tabindex="-1" role="dialog"
                aria-labelledby="addressUpdateLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <form method="POST"
                                onsubmit="return handleFormSubmit('.addressUpdateForm', '{{ route('account.updateAddress') }}')"
                                class="needs-validation addressUpdateForm" novalidate>
                                @csrf
                                <div class="mt-3">
                                    <div class="border-bottom ps-2 mb-2">
                                        <div class="h5 mb-0 pt-2 pb-2">
                                            {{ !empty($address) ? 'User Default Shipping Address' : 'Set Your Default Shipping Address' }}
                                        </div>
                                    </div>
                                    <form class="needs-validation" novalidate>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="first_name" class="form-label">First Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" required
                                                    value="{{ !empty($address) ? $address->first_name : Auth::user()->name }}"
                                                    placeholder="First Name">
                                                <div class="invalid-feedback">First name is required.</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="last_name" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name"
                                                    value="{{ !empty($address) ? $address->last_name : '' }}"
                                                    placeholder="Last Name">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                required
                                                value="{{ !empty($address) ? $address->email : Auth::user()->email }}"
                                                placeholder="Email">
                                            <div class="invalid-feedback">Valid email is required.</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                required
                                                value="{{ !empty($address) ? $address->mobile : Auth::user()->phone }}"
                                                placeholder="Phone number">
                                            <div class="invalid-feedback">Phone number is required.</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="country" class="form-label">Country <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="country" name="country"
                                                    required value="{{ !empty($address) ? $address->country : '' }}"
                                                    placeholder="Country">
                                                <div class="invalid-feedback">Country is required.</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="state" class="form-label">State <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="state" name="state"
                                                    required value="{{ !empty($address) ? $address->state : '' }}"
                                                    placeholder="State">
                                                <div class="invalid-feedback">State is required.</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="city" class="form-label">City <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="city" name="city"
                                                    required value="{{ !empty($address) ? $address->city : '' }}"
                                                    placeholder="City">
                                                <div class="invalid-feedback">City is required.</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="zip" class="form-label">Zip Code <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="zip" name="zip"
                                                    required value="{{ !empty($address) ? $address->zip : '' }}"
                                                    placeholder="Zip Code">
                                                <div class="invalid-feedback">Zip code is required.</div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="address" name="address" required placeholder="Street Address">{{ !empty($address) ? $address->address : '' }}</textarea>
                                            <div class="invalid-feedback">Address is required.</div>
                                        </div>
                                        <hr>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                {{ !empty($address) ? 'Update' : 'Submit' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer" style="border: none;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-overlay"></div>
        </div>

    </div>

    <script>
        $('#passwordChangeBtn').change(function(e) {
            $('#passwordChange').toggleClass('d-none');
            if ($('#passwordChange').hasClass('d-none')) {
                $('#newPassword').prop('required', false);
                $('#password').prop('required', false);
                $('#confirmNewPassword').prop('required', false);
            } else {
                $('#password').prop('required', true);
                $('#newPassword').prop('required', true);
                $('#confirmNewPassword').prop('required', true);
            }
        });
    </script>



    @if (!is_gift(Auth::user()->id))
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script>
            const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

            const scratchCardCover = document.querySelector('.scratch-card-cover');
            const scratchCardCanvasRender = document.querySelector('.scratch-card-canvas-render');
            const scratchCardCoverContainer = document.querySelector('.scratch-card-cover-container');
            const scratchCardText = document.querySelector('.scratch-card-text');
            const scratchCardImage = document.querySelector('.scratch-card-image');

            const canvas = document.querySelector('canvas');
            const context = canvas.getContext('2d');
            let isPointerDown = false;
            let positionX;
            let positionY;
            let clearDetectionTimeout = null;

            const devicePixelRatio = window.devicePixelRatio || 1;

            const canvasWidth = canvas.offsetWidth * devicePixelRatio;
            const canvasHeight = canvas.offsetHeight * devicePixelRatio;

            canvas.width = canvasWidth;
            canvas.height = canvasHeight;

            context.scale(devicePixelRatio, devicePixelRatio);

            if (isSafari) {
                canvas.classList.add('hidden');
            }

            canvas.addEventListener('pointerdown', (e) => {
                scratchCardCover.classList.remove('shine');
                ({
                    x: positionX,
                    y: positionY
                } = getPosition(e));
                clearTimeout(clearDetectionTimeout);

                canvas.addEventListener('pointermove', plot);

                window.addEventListener('pointerup', (e) => {
                    canvas.removeEventListener('pointermove', plot);
                    clearDetectionTimeout = setTimeout(() => {
                        checkBlackFillPercentage();
                    }, 500);
                }, {
                    once: true
                });
            });

            const checkBlackFillPercentage = () => {
                const imageData = context.getImageData(0, 0, canvasWidth, canvasHeight);
                const pixelData = imageData.data;

                let blackPixelCount = 0;

                for (let i = 0; i < pixelData.length; i += 4) {
                    const red = pixelData[i];
                    const green = pixelData[i + 1];
                    const blue = pixelData[i + 2];
                    const alpha = pixelData[i + 3];

                    if (red === 0 && green === 0 && blue === 0 && alpha === 255) {
                        blackPixelCount++;
                    }
                }

                const blackFillPercentage = blackPixelCount * 100 / (canvasWidth * canvasHeight);

                if (blackFillPercentage >= 45) {
                    fetchGiftInfo();
                    scratchCardCoverContainer.classList.add('clear');
                    confetti({
                        particleCount: 100,
                        spread: 90,
                        origin: {
                            y: (scratchCardText.getBoundingClientRect().bottom + 60) / window.innerHeight,
                        },
                    });

                    scratchCardText.textContent = '🎉 Congratulations !';
                    scratchCardImage.classList.add('animate');
                    scratchCardCoverContainer.addEventListener('transitionend', () => {
                        scratchCardCoverContainer.classList.add('hidden');
                    }, {
                        once: true
                    });
                }
            }

            const getPosition = ({
                clientX,
                clientY
            }) => {
                const {
                    left,
                    top
                } = canvas.getBoundingClientRect();
                return {
                    x: clientX - left,
                    y: clientY - top,
                };
            }

            const plotLine = (context, x1, y1, x2, y2) => {
                var diffX = Math.abs(x2 - x1);
                var diffY = Math.abs(y2 - y1);
                var dist = Math.sqrt(diffX * diffX + diffY * diffY);
                var step = dist / 50;
                var i = 0;
                var t;
                var x;
                var y;

                while (i < dist) {
                    t = Math.min(1, i / dist);

                    x = x1 + (x2 - x1) * t;
                    y = y1 + (y2 - y1) * t;

                    context.beginPath();
                    context.arc(x, y, 16, 0, Math.PI * 2);
                    context.fill();

                    i += step;
                }
            }

            const setImageFromCanvas = () => {
                canvas.toBlob((blob) => {
                    const url = URL.createObjectURL(blob);
                    previousUrl = scratchCardCanvasRender.src;
                    scratchCardCanvasRender.src = url;
                    if (!previousUrl) {
                        scratchCardCanvasRender.classList.remove('hidden');
                    } else {
                        URL.revokeObjectURL(previousUrl);
                    }
                    previousUrl = url;
                });
            }

            let setImageTimeout = null;

            const plot = (e) => {
                const {
                    x,
                    y
                } = getPosition(e);
                plotLine(context, positionX, positionY, x, y);
                positionX = x;
                positionY = y;
                if (isSafari) {
                    clearTimeout(setImageTimeout);

                    setImageTimeout = setTimeout(() => {
                        setImageFromCanvas();
                    }, 5);
                }
            };
        </script>
        <script>
            function fetchGiftInfo() {
                $.ajax({
                    url: "{{ route('account.gift_info') }}", // Replace with your actual endpoint
                    method: 'POST',
                    data: {
                        // Add any necessary data here (e.g., CSRF token)
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('.scratch-card-image').html(response.html);
                        $('#couponCode').text(`Copy this Code : ${response.code}`);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching gift info:', error);
                        $('.scratch-card-image').html(
                            '<p>There was an error retrieving your gift information.</p>');
                    }
                });
            }
        </script>
    @endif
@endsection
