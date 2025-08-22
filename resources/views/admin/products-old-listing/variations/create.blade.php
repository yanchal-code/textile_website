@extends('admin.includes.layout')
@section('headTag')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.js"></script>
@endsection
@section('content')
    <script>
        $('#nav-item_inventory').addClass('active');
    </script>

    <div class="container py-3">
        <div class="h4 mb-2 text-center">Create Variation</div>
        <div class="my-3 d-flex">

            <p class=" col-4"><strong>{{ $product->name }}</strong></p>
            <p class="card-text col-4">
                <strong>SKU : </strong> {{ $product->sku }}<br>
            </p>
            <p class="text-center col-4"> <strong>Compare Price : </strong> {{ $product->compare_price }}</p>

        </div>
        <form id="product-form" action="{{ route('variations.store', $product->id) }}" class="needs-validation" novalidate
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="mb-3 col-md-6">
                    <label for="color" class="form-label">{{ $product->leafCategory->v1st }} <span
                            class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="color" name="color" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="size" class="form-label">{{ $product->leafCategory->v2nd }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="size" name="size" required>
                </div>


                <div class="mb-3 col-md-6">
                    <label for="price" class="form-label">C Price</label>
                    <input type="text" value="" class="form-control" min="0.00" id="price" name="c_price">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sku" name="sku" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" min="0.00" id="price" name="price" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="quantity" name="quantity" required>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select required type="text" name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">

                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>
                            <div id="sucessDropzoneLabel" class="d-none">
                                <small>(to add more files drop files here or click to upload)</small>
                            </div>
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>

                            <div class="align-item-center input-group mt-3">
                                <input type="text" id="imageLinkInput" class="form-control"
                                    placeholder="Paste image URL here">
                                <button type="button" id="addImageLinkButton" class="btn btn-primary">Add
                                    Image</button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row px-2 justify-content-start" id="productImageDivMain"></div>
                    </div>
                </div>
            </div>

            <button id="product-create-button" type="submit" class="btn btn-success my-3">Create Product</button>
        </form>
    </div>

    <script>
        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 9) {
                        alert('Max 10 files are allowed')
                    }
                });
            },
            url: "{{ route('tempImages.create') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(file, response) {
                var image_id_input =
                    `<input type="hidden" class="hiddenInputs" value="${response.image_id}" name="image_array[]">
                <div class="productImageDiv card px-0 col-md-2 col-6">
                                            <div class="border card-body border-dark"> <img
                                                    src="{{ asset('${response.img_path}') }}"
                                                    class="card-img-top img-fluid" alt="productImage"></div>
                                            <div class="card-footer text-center">


                                                 <div>
                                                    <label>
                                                        <input type="radio" checked name="index_image" checked value="${response.image_id}" class="indexImageRadio"> Set as Index
                                                    </label>
                                                 </div>
                                                <button type="button" class="btn btn-danger imgDeleteBtn"
                                                    value="${response.image_id}">Delete</button>
                                            </div>
                
                </div>`;
                file.previewElement.innerHTML = "";
                $('#sucessDropzoneLabel').removeClass('d-none');

                showNotification(response.message, 'success', 'text');
                $("#productImageDivMain").append(image_id_input);
            },
            error: function(xhr, status, error) {
                var errorMessage = "";
                try {
                    var responseJson = JSON.parse(xhr.responseText);
                    errorMessage = responseJson.message;
                } catch (e) {
                    errorMessage = "An error occurred: " + xhr.status + " " + xhr.statusText;
                }

                showNotification(errorMessage, 'danger', 'text');
            }
        });

        $(document).on("click", "#addImageLinkButton", function() {
            const imageUrl = $("#imageLinkInput").val().trim();

            // Validate the image URL
            if (!isValidImageUrl(imageUrl)) {
                showNotification("Invalid image URL. Please enter a valid URL.", 'danger', 'text');
                return;
            }

            // Generate the HTML for the image preview and hidden input
            const imageId = `link-${Date.now()}`; // Generate a unique ID for the image
            const imageHtml = `
            <div class="productImageDiv card px-0 col-md-2 col-6">
                <div class="border card-body border-dark">
                    <img src="${imageUrl}" class="card-img-top img-fluid" alt="productImage">
                </div>
                                                        <div class="card-footer text-center">
                                                            <div>
                                                            <label>
                                                                <input type="radio" name="index_image" checked value="${imageUrl}" class="indexImageRadio"> Set as Index
                                                            </label>
                                                        </div>
                    <button type="button" class="btn btn-danger imgDeleteBtn" value="${imageId}">Delete</button>
                </div>
            </div>`;

            $("#productImageDivMain").append(imageHtml);

            // Append the URL to the hidden input field for backend submission
            addToHiddenUrls(imageUrl);

            // Clear the input field
            $("#imageLinkInput").val("");
        });

        function isValidImageUrl(url) {
            return /\.(jpeg|jpg|gif|png)$/.test(url) && url.startsWith('http');
        }

        // Function to append URL to hidden input
        function addToHiddenUrls(url) {
            let hiddenInput = $("#imageUrlsInput");
            if (hiddenInput.length === 0) {
                // Create hidden input if it doesn't exist
                hiddenInput = $('<input>', {
                    type: 'hidden',
                    id: 'imageUrlsInput',
                    name: 'image_urls[]', // Use array syntax for name
                });
                $("form").append(hiddenInput);
            }

            // Append a new hidden input for each URL
            const newHiddenInput = $('<input>', {
                type: 'hidden',
                class: 'image-url-hidden',
                name: 'image_urls[]', // Maintain array syntax
                value: url
            });
            $("form").append(newHiddenInput);
        }

        // Function to remove URL from hidden inputs
        function removeFromHiddenUrls(url) {
            $(".image-url-hidden").each(function() {
                if ($(this).val() === url) {
                    $(this).remove();
                }
            });
        }


        $(document).on("click", ".imgDeleteBtn", function(e) {
            e.preventDefault();
            const btn = $(this);
            const imgId = btn.val();

            // Check if it's a pasted link
            const imageUrl = btn.closest(".productImageDiv").find("img").attr("src");
            if (imgId.startsWith('link-')) {
                // Remove the link image and update hidden input
                removeFromHiddenUrls(imageUrl);

                btn.closest(".productImageDiv").fadeOut(function() {
                    $(this).remove();
                });
                return;
            }

            // Handle uploaded images (existing functionality)
            btn.prop('disabled', true);
            $.ajax({
                type: "post",
                url: "{{ route('tempImages.delete') }}",
                data: {
                    imgId: imgId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(response) {
                    btn.prop('disabled', false);
                    if (response.status) {
                        btn.closest(".productImageDiv").fadeOut(function() {
                            $(this).remove();
                        });
                        removeHiddenInput(imgId);
                        showNotification(response.message, 'success', 'text');
                    } else {
                        showNotification(response.message, 'danger', 'text');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false);
                    const errorMessage = xhr.responseJSON?.message || "An error occurred.";
                    showNotification(errorMessage, 'danger', 'text');
                }
            });
        });


        function removeHiddenInput(valId) {
            $(".hiddenInputs").each(function() {
                if ($(this).val() == valId) {
                    $(this).remove();
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#product-form').on('submit', function(e) {
                e.preventDefault();

                $('#product-create-button').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                );
                $('#product-create-button').prop('disabled', true);
                var form = $(this);
                let formData = new FormData(this);


                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                if (form[0].checkValidity() === true) {
                    $.ajax({
                        url: $(this).attr('action'), // Form action URL
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            $('#product-create-button').html(
                                'Create Product'
                            );
                            $('#product-create-button').prop('disabled', false);

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

                                showNotification(errorsHtml, 'danger', 'html');
                            } else if (response.status === true) {

                                window.location.href =
                                    "{{ route('products.variations', $product->id) }}"

                            }
                        },
                        error: function(xhr, status, error) {

                            $('#product-create-button').html(
                                'Create Product'
                            );
                            $('#product-create-button').prop('disabled', false);

                            var errorMessage = "";
                            try {
                                var responseJson = JSON.parse(xhr.responseText);
                                errorMessage = responseJson.message;
                            } catch (e) {
                                errorMessage = "An error occurred: " + xhr.status + " " + xhr
                                    .statusText;
                            }

                            showNotification(errorMessage, 'danger', 'text');
                        }
                    });
                } else {
                    form.addClass('was-validated');
                    $('#product-create-button').html(
                        'Create Product'
                    );
                    $('#product-create-button').prop('disabled', false);
                }
            });
        });
    </script>
@endsection
