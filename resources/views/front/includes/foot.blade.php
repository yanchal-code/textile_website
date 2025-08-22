 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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
                        if (response.status === false) {
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
                        }
                        if (response.reload == true) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 4000);
                        } else {
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

        function handleBidFormSubmit(formSelector, url) {
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
                        if (response.status === false) {
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
                        } else if (response.status == 'message') {

                            showNotification(response.message, 'warning', 'text');
                        }
                        $('.currentBid').text(response.currentBid);
                        if (response.reload == true) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 4000);
                        } else {
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

        function addToWishlist(id) {
            var btn = $(this);
            $.ajax({
                type: "post",
                url: "{{ route('front.addToWishlist') }}",
                data: {
                    'id': id
                },
                beforeSend: function() {
                    btn.prop('disabled', true);
                },
                dataType: "json",
                success: function(response) {
                    btn.prop('disabled', false);
                    if (response.status == true) {
                        showNotification(response.message, 'success', 'html')
                        $('.totalItemsInWishlist').html(response.totalItemsInWishlist);
                    } else if (response.status == false) {
                        window.location.href = "{{ route('account.login') }}";
                    }

                },
                error: function(xhr, status, error) {
                    btn.prop('disabled', false);
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
        };

        function removeFromWishlist(id) {
            var btn = $(this);
            $.ajax({
                type: "post",
                url: "{{ route('wishlist.deleteItem') }}",
                data: {
                    'id': id
                },
                beforeSend: function() {
                    btn.prop('disabled', true);
                },
                dataType: "json",
                success: function(response) {
                    btn.prop('disabled', false);
                    if (response.status == true) {
                        showNotification(response.message, 'success')
                        $('.totalItemsInWishlist').html(response.totalItemsInWishlist);
                        window.location.reload();
                    } else if (response.status == false) {
                        window.location.href = "{{ route('account.login') }}";
                    }

                },
                error: function(xhr, status, error) {
                    btn.prop('disabled', false);
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
        };

        $('.newsletter').submit(function(e) {
            e.preventDefault();
            var btn = $('.newsletterSubmit');
            var form = $(this);
            if (form[0].checkValidity() === true) {
                var formData = new FormData(this);
                $.ajax({
                    type: "post",
                    url: "{{ route('front.newsletter') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function() {
                        btn.prop('disabled', true);
                    },
                    dataType: "json",
                    success: function(response) {
                        btn.prop('disabled', false);

                        if (response.status == true) {
                            form[0].reset();
                            showNotification(response.message, 'success', 'html')
                        } else if (response.status == false) {
                            window.location.reload();
                        }

                    },
                    error: function(xhr, status, error) {
                        btn.prop('disabled', false);
                        var errorMessage = "";
                        try {
                            var responseJson = JSON.parse(xhr.responseText);
                            errorMessage = responseJson.message;
                        } catch (e) {
                            errorMessage = "An error occurred: " + xhr.status + " " + xhr
                                .statusText;
                        }

                        showNotification(errorMessage, 'danger', 'html');

                    }
                });
            }
        });

        $(document).on('click', '.quick-add', function(e) {

            var product_id = $(this).data('bs-id');

            var addToCartBtn = $(this);

            var originalButtonHtml = addToCartBtn.html();

            e.preventDefault();
            $.ajax({
                type: "post",
                url: "{{ route('front.addTocart') }}",
                data: {
                    'id': product_id,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    addToCartBtn.prop('disabled', true);
                    addToCartBtn.html(
                        '<span class="spinner-border spinner-border-sm" role="status"></span>'
                    );
                },
                dataType: "json",

                success: function(response) {

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

                        showNotification(response.message, 'warining', 'html');
                    } else {
                        showNotification(response.message, 'success', 'text');
                        $('.totalItemsInCart').text(response.totalItemsInCart);
                    }
                },
                error: function(xhr, status, error) {

                    var errorMessage = "";
                    try {
                        var responseJson = JSON.parse(xhr.responseText);
                        errorMessage = responseJson.message;
                    } catch (e) {
                        errorMessage = "An error occurred: " + xhr.status + " " + xhr.statusText;
                    }

                    showNotification(errorMessage, 'danger', 'html');

                },
                complete: function() {
                    addToCartBtn.html(originalButtonHtml);
                }
            });
        });
        $(document).on('click', '.quick-add-qty', function(e) {

            var product_id = $(this).data('bs-id');
            var qty = $('#product-quantity').val();

            var addToCartBtn = $(this);

            var originalButtonHtml = addToCartBtn.html();

            var selectedOptions = {};

            $('.options_select_input').each(function() {
                var optionName = $(this).attr('name');
                var optionValue = $(this).val();

                if (optionName && optionValue) {
                    selectedOptions[optionName] = optionValue;
                }

            });


            e.preventDefault();
            $.ajax({
                type: "post",
                url: "{{ route('front.addTocart') }}",
                data: {
                    'id': product_id,
                    'qty': qty,
                    'options': selectedOptions,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {

                    addToCartBtn.html(
                        '<span class="spinner-border spinner-border-sm" role="status"></span>'
                    );
                },
                dataType: "json",

                success: function(response) {

                    if (response.status === false) {
                        var errorsHtml = '';
                        var errors = response.errors;
                        var count = 1;
                        for (var key in errors) {

                            if (errors.hasOwnProperty(key)) {
                                errorsHtml += '<p>' + count + '. ' + errors[key][0] +
                                    '</p>';
                            }
                            count = count + 1;
                        }

                        showNotification(response.message, 'warining', 'html');
                    } else {
                        showNotification(response.message, 'success', 'text');
                        $('.totalItemsInCart').text(response.totalItemsInCart);
                    }
                },
                error: function(xhr, status, error) {

                    var errorMessage = "";
                    try {
                        var responseJson = JSON.parse(xhr.responseText);
                        errorMessage = responseJson.message;
                    } catch (e) {
                        errorMessage = "An error occurred: " + xhr.status + " " + xhr
                            .statusText;
                    }

                    showNotification(errorMessage, 'danger', 'html');

                },
                complete: function() {
                    addToCartBtn.html(originalButtonHtml);
                }
            });
        });


        function handleQuantityChange(button, isIncrement) {
            var maxAllowedQuantity = 100;

            var qtyElement = button.closest('.wg-quantity').find('input');

            var qtyValue = parseInt(qtyElement.val());
            var rowId = button.data('id');
            var element = button.data('element-id');
            var item_total = $('#' + element + '_item_total');

            if (isIncrement) {
                if (qtyValue < maxAllowedQuantity) {
                    qtyElement.val(qtyValue + 1);
                    updateCart(rowId, qtyElement.val(), item_total, button);
                } else {
                    qtyElement.val(maxAllowedQuantity);
                    alert('Max allowed quantity is ' + maxAllowedQuantity);
                }
            } else {
                if (qtyValue > 1) {
                    qtyElement.val(qtyValue - 1);
                    updateCart(rowId, qtyElement.val(), item_total, button);
                }
            }
        }

        function updateCart(rowId, qty, element, button) {

            var originalButtonHtml = button.html();

            $.ajax({
                type: "post",
                url: "{{ route('cart.update') }}",
                data: {
                    'rowId': rowId,
                    'qty': qty
                },
                dataType: "json",
                beforeSend: function() {
                    button.prop('disabled', true);
                    button.html(
                        '<span class="spinner-border spinner-border-sm" role="status"></span>'
                    );
                },
                success: function(response) {

                    $('.discount').remove();
                    $('#discountForm').val('');

                    if (response.status === true) {
                        showNotification(response.message, 'success', 'text');
                    } else {
                        showNotification(response.message, 'danger', 'text');
                    }

                    button.closest('.remove_item_row').find('.total_price').text(response.item_total);

                    $('.card_subtotal').text(response.cart_subtotal);

                    $('.totalItemsInCart').text(response.totalItemsInCart);
                },
                error: function(xhr) {
                    handleAjaxError(xhr, button);
                },
                complete: function() {
                    button.prop('disabled', false);
                    button.html(originalButtonHtml);
                }
            });
        }

        function deleteItem(rowId, button) {
            $.ajax({
                type: "post",
                url: "{{ route('cart.deleteItem') }}",
                data: {
                    'rowId': rowId
                },
                dataType: "json",
                beforeSend: function() {
                    button.prop('disabled', true);
                },
                success: function(response) {
                    button.prop('disabled', false);
                    $('.discount').remove();
                    $('#discountForm').val('');

                    if (response.status === true) {
                        $('.card_subtotal').text(response.card_subtotal);
                        if (response.card_subtotal === 0) {
                            window.location.reload();
                        } else {
                            button.closest('.remove_item_row').remove();
                            $('.totalItemsInCart').text(response.totalItemsInCart);
                        }
                    } else {
                        showNotification(response.message, 'danger', 'text');
                        $('.card_subtotal').text(response.card_subtotal);
                        $('.totalItemsInCart').text(response.totalItemsInCart);
                    }
                },
                error: function(xhr) {
                    handleAjaxError(xhr, button);
                }
            });
        }

        function handleAjaxError(xhr, button) {
            $('.discount').remove();
            $('#discountForm').val('');
            var errorMessage = "";
            try {
                var responseJson = JSON.parse(xhr.responseText);
                errorMessage = responseJson.message;
            } catch (e) {
                errorMessage = "An error occurred: " + xhr.status + " " + xhr.statusText;
            }
            showNotification(errorMessage, 'danger', 'html');
        }

        $(document).on('click', '.cart-plus-btn', function() {
            handleQuantityChange($(this), true);
        });

        $(document).on('click', '.cart-minus-btn', function() {
            handleQuantityChange($(this), false);
        });

        $(document).on('click', '.cart-remove-item', function() {
            deleteItem($(this).data('id'), $(this));
        });
    </script>

    <script>
        function loadQuickView(sku) {
            let modal = $('#quick_view');
            modal.modal('show');
            let url = "{{ route('product.quickView') }}";

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    'sku': sku,
                },

                beforesend: function() {
                    $('#product-quick-view-main-div').html(` <div class="spinner-border m-auto text-center" style="width: 3rem; height: 3rem;" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>`);
                },

                success: function(response) {
                    $('#product-quick-view-main-div').html(response);

                },
                error: function() {
                    $('#product-quick-view-main-div').html(
                        '<div class="text-danger">Failed to load product details. Please try again later.</div>'
                    );

                },
                complete: function() {
                    setTimeout(() => {
                        initSwiper();
                    }, 300);
                }

            });
        }

        function initSwiper() {
            new Swiper('.tf-single-slide', {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 10,
                navigation: {
                    nextEl: '.single-slide-next',
                    prevEl: '.single-slide-prev',
                },
            });
        }

        $(document).on('click', '.quickview', function() {
            let sku = $(this).data('sku');
            loadQuickView(sku);
        });
    </script>
