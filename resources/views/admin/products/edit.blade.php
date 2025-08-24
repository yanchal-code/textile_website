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
        <div class="h4 mb-2">Edit Product</div>
        <form id="product-form" action="{{ route('products.edit.store', $product->id) }}" class="needs-validation custom-form"
            novalidate method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span> </label>
                    <input type="text" value="{{ $product->name }}" class="form-control" id="name" name="name"
                        required>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="design_number" class="form-label">Design Number</label>
                        <input value="{{ $product->design_number }}" type="text" class="form-control" id="design_number"
                            name="design_number">
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <select required id="category" name="category_id" class="form-control form-select">
                        <option value="">Select</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3 col-md-6">
                    <label for="sub_category" class="form-label">Sub Category</label>
                    <select id="sub_category" name="sub_category_id" class="form-control form-select">
                        <option value="">Select</option>
                        @foreach ($subCategories as $category)
                            <option value="{{ $category->id }}"
                                {{ $product->sub_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="leaf_category" class="form-label">Leaf Category</label>
                    <select id="leaf_category" name="leaf_category_id" class="form-control form-select">
                        <option value="">Select</option>
                        @foreach ($leafCategories as $category)
                            <option value="{{ $category->id }}"
                                {{ $product->leaf_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <script>
                    $(document).ready(function() {
                        // Load sub-categories when category is selected
                        $('#category').on('change', function() {
                            const categoryId = $(this).val();
                            $('#sub_category').prop('disabled', true).html('<option value="">Loading...</option>');
                            $('#leaf_category').prop('disabled', true).html(
                                '<option value="">Select Leaf Category</option>');

                            if (categoryId) {
                                $.ajax({
                                    url: `/admin/get-sub-categories/${categoryId}`,
                                    method: 'GET',
                                    success: function(data) {
                                        $('#sub_category').prop('disabled', false).html(
                                            '<option value="">Select Sub Category</option>');
                                        $.each(data, function(key, value) {
                                            $('#sub_category').append(
                                                `<option value="${key}">${value}</option>`);
                                        });
                                    },
                                    error: function() {
                                        alert('Failed to load sub-categories.');
                                    }
                                });
                            } else {
                                $('#sub_category').html('<option value="">Select Sub Category</option>');
                                $('#leaf_category').html('<option value="">Select Leaf Category</option>');
                            }
                        });

                        // Load leaf-categories when sub-category is selected
                        $('#sub_category').on('change', function() {
                            const subCategoryId = $(this).val();
                            $('#leaf_category').prop('disabled', true).html('<option value="">Loading...</option>');

                            if (subCategoryId) {
                                $.ajax({
                                    url: `/admin/get-leaf-categories/${subCategoryId}`,
                                    method: 'GET',
                                    success: function(data) {
                                        $('#leaf_category').prop('disabled', false).html(
                                            '<option value="">Select Leaf Category</option>');
                                        $.each(data, function(key, value) {
                                            $('#leaf_category').append(
                                                `<option value="${key}">${value}</option>`);
                                        });
                                    },
                                    error: function() {
                                        alert('Failed to load leaf-categories.');
                                    }
                                });
                            } else {
                                $('#leaf_category').html('<option value="">Select Leaf Category</option>');
                            }
                        });
                    });
                </script>


                <div class="mb-3 col-md-6">
                    <label for="product_group" class="form-label">Brand</label>
                    <select id="product_group" name="brand" class="form-control form-select">
                        <option value="">Select</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                    <input value="{{ $product->sku }}" type="text" class="form-control" id="sku" name="sku"
                        required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                    <input value="{{ $product->price }}" type="text" class="form-control" min="0.00" id="price"
                        name="price" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input value="{{ $product->quantity }}" type="text" class="form-control" id="quantity"
                        name="quantity" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="c_price" class="form-label">Compare Price <span class="text-danger">*</span></label>
                    <input value="{{ $product->compare_price }}" type="text" class="form-control" id="c_price"
                        name="c_price" required>
                </div>


                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Title (140MAX)</label>
                        <textarea class="form-control" id="short_description" name="short_description">{!! $product->short_description !!}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description">{!! $product->description !!}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="is_featured">Featured</label>
                        <select required type="text" name="is_featured" id="is_featured" class="form-select">
                            <option {{ $product->is_featured == 1 ? 'selected' : '' }} value="1">Yes</option>
                            <option {{ $product->is_featured == 0 ? 'selected' : '' }} value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select required type="text" name="status" id="status" class="form-select">
                            <option {{ $product->status == 1 ? 'selected' : '' }} value="1">Active</option>
                            <option {{ $product->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                    <input value="{{ $product->color }}" type="text" class="form-control" id="color"
                        name="color">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="size" class="form-label">Size <span class="text-danger">*</span></label>
                    <input value="{{ $product->size }}" type="text" class="form-control" id="size"
                        name="size">
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="h_time" class="form-label">Handeling Time</label>
                        <input type="text" value="{{ $product->h_time }}" class="form-control" id="h_time"
                            name="h_time">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="d_time" class="form-label">Delivery Time</label>
                        <input type="text" value="{{ $product->d_time }}" class="form-control" id="d_time"
                            name="d_time">
                    </div>
                </div>


                <div id="shippingContainer" class="col-12 row">

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="shipping" class="form-label">Shipping Cost
                                ({{ config('settings.currency_symbol') }})</label>
                            <input value="{{ $product->shipping }}" type="text" class="form-control" id="shipping"
                                name="shipping">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="shippingAddons" class="form-label"> Shipping Addons
                                ({{ config('settings.currency_symbol') }})</label>
                            <input type="text" value="{{ $product->shippingAddons }}" class="form-control"
                                id="shippingAddons" name="shippingAddons">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="s_services" class="form-label">Shipping Services</label>
                            <input type="text" value="{{ $product->s_services }}" class="form-control"
                                id="s_services" name="s_services">
                        </div>
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
                        <div class="row px-2 justify-content-start" id="productImageDivMain">
                            @php
                                $totalImages = $product->images->whereNull('variation_id')->count();
                            @endphp
                            @if ($totalImages > 0)
                                @foreach ($product->images->whereNull('variation_id') as $productImage)
                                    <div class="productImageDiv card px-0 col-md-2 col-6">
                                        <div class="border card-body border-dark"> <img
                                                src="{{ asset($productImage->image) }}" class="card-img-top img-fluid"
                                                alt="productImage"></div>
                                        <div class="card-footer text-center">

                                            <div>
                                                <label>
                                                    <input @if ($productImage->is_default == 1) checked @endif type="radio"
                                                        name="index_image" value="{{ $productImage->id }}"
                                                        class="indexImageRadio"> Set as
                                                    Index
                                                </label>
                                            </div>
                                            <button class="btn btn-danger imgDeleteBtn"
                                                value="{{ $productImage->id }}">Delete</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="my-2 col-12 text-center font-weight-bold">No Images Found</div>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="text-center mt-4 py-2 fw-bold" style="background-color: #fff1f1cc;">Specs Fields </div>
                <div id="specsContainer" class="row p-2 rounded-2" style="background-color: #fff1f1cc;">
                    @php
                        $specFields = is_string($product->specs) ? json_decode($product->specs, true) : $product->specs;
                    @endphp
                    @if ($specFields)
                        @foreach ($specFields as $key => $value)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ ucfirst($key) }}</label>
                                    <input type="text" name="specs[{{ $key }}]" class="form-control"
                                        value="{{ $value }}">
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>

            <div class="row mt-3">
                <div class="mb-3 col-md-6">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text" value="{{ $product->meta_title }}" class="form-control" id="meta_title"
                        name="meta_title">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="alt_image_text" class="form-label">Alt Image Text</label>
                    <input type="text" value="{{ $product->alt_image_text }}" class="form-control"
                        id="alt_image_text" name="alt_image_text">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ $product->meta_description }}</textarea>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="meta_keywords" class="form-label">Meta Keywords (comma saparated)</label>
                    <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="3">{{ $product->meta_keywords }}</textarea>
                </div>


            </div>

            <button id="product-create-button" type="submit" class="btn btn-success my-3">Edit Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary my-3">Cancel</a>

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
            url: "{{ route('tempImages.create', $product->id) }}",
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
                                                        <input type="radio" name="index_image" value="${response.image_id}" class="indexImageRadio"> Set as Index
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
                                'Edit Product'
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

                                window.location.href = "{{ route('products.index') }}"

                            }
                        },
                        error: function(xhr, status, error) {

                            $('#product-create-button').html(
                                'Edit Product'
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
                        'Edit Product'
                    );
                    $('#product-create-button').prop('disabled', false);
                }
            });
        });
    </script>
@endsection
