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
        <div class="h4 mb-4">Create Product</div>
        <form id="product-form" onsubmit="return handleFormSubmit('#product-form', '{{ route('products.store') }}')"
            class="needs-validation custom-form" novalidate method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                {{-- name --}}
                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                {{-- design_number --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="design_number" class="form-label">Design Number</label>
                        <input type="text" class="form-control" id="design_number" name="design_number">
                    </div>
                </div>

                {{-- category --}}
                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <select required id="category" name="category_id" class="form-control form-select">
                        <option value="">Select</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- sub category --}}
                <div class="mb-3 col-md-6">
                    <label for="sub_category" class="form-label">Sub Category <span class="text-danger">*</span></label>
                    <select id="sub_category" name="sub_category_id" class="form-control form-select" required disabled>
                        <option value="">Select</option>
                    </select>
                </div>

                {{-- leaf category --}}
                <div class="mb-3 col-md-6">
                    <label for="leaf_category" class="form-label">Leaf Category <span class="text-danger">*</span></label>
                    <select id="leaf_category" name="leaf_category_id" class="form-control form-select" required disabled>
                        <option value="">Select</option>
                    </select>
                </div>

                {{-- ajax to load sub categories and leaf categories --}}
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

                {{-- brand id --}}
                <div class="mb-3 col-md-6">
                    <div class="mb-3">
                        <label for="brand" class="form-label">Brand </label>
                        <select type="text" name="brand_id" id="status" class="form-control form-select">
                            <option value="">Select</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>


                {{-- status --}}
                <div class="mb-3 col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select required type="text" name="status" id="status" class="form-control form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                {{-- is featured --}}
                <div class="mb-3 col-md-6">
                    <div class="mb-3">
                        <label for="featured" class="form-label">Is Featured <span class="text-danger">*</span></label>
                        <select required type="text" name="is_featured" id="featured" class="form-control form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>


                {{-- sku --}}
                <div class="mb-3 col-md-6">
                    <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sku" name="sku" required>
                </div>

                {{-- price --}}
                <div class="mb-3 col-md-6">
                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" min="0.00" id="price" name="price" required>
                </div>

                {{-- quantity --}}
                <div class="mb-3 col-md-6">
                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="quantity" name="quantity" required>
                </div>

                {{-- compare price --}}
                <div class="mb-3 col-md-6">
                    <label for="c_price" class="form-label">Compare Price <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="c_price" name="c_price" required>
                </div>

                {{-- media --}}
                <div class="col-md-12 mb-3">

                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media <span class="text-danger">*</span></h2>
                            <div id="sucessDropzoneLabel" class="d-none">
                                <small>(to add more files drop files here or click to upload)</small>
                            </div>
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>


                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <div class="mt-3">
                                        <!--<label for="video" class="form-label">Video</label>-->
                                        <input placeholder="Video Url" type="text" class="form-control" id="video"
                                            name="video url">
                                    </div>
                                </div> --}}

                                {{-- image_url --}}
                                <div class="col-md-12">
                                    <div class="align-item-center input-group mt-3">
                                        <input type="text" id="imageLinkInput" class="form-control"
                                            placeholder="Paste image URL here">
                                        <button type="button" id="addImageLinkButton" class="btn btn-dark">Add
                                            Image</button>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                    <div>
                        <div class="row px-2 justify-content-start" id="productImageDivMain"></div>
                    </div>
                </div>

                <div class="col-12 text-center">
                    <button type="button" class="btn btn-primary btn-sm" id="generateProduct" data-bs-toggle="modal"
                        data-bs-target="#geminiModal">
                        Generate Product Details
                    </button>
                    <div class="row">
                        {{-- short description --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description (140MAX)</label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="5"></textarea>
                            </div>
                        </div>

                        {{-- full description --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" rows="5" name="description"></textarea>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $("#sendPrompt").click(function() {
                                        let prompt = $("#prompt").val();
                                        if (prompt.trim() === "") {
                                            showNotification("Please enter a prompt", "warning", "text");
                                            return;
                                        }

                                        let button = $("#sendPrompt");
                                        let originalHTML = button.html(); // Store the original button HTML
                                        button.html(
                                            '<span class="spinner-border spinner-border-sm"></span> Generating...'); // Add Spinner
                                        button.prop("disabled", true);

                                        $.ajax({
                                            url: "{{ route('generate.gemini') }}",
                                            type: "POST",
                                            data: {
                                                _token: "{{ csrf_token() }}",
                                                prompt: prompt
                                            },
                                            success: function(response) {
                                                if (response.status === "success") {
                                                    $("#name").val(response.title);
                                                    $("#short_description").val(response.title);
                                                    $("#description").val(response.description);
                                                    showNotification("Product details generated successfully!",
                                                        "success", "text");
                                                    $("#geminiModal").modal('hide');
                                                } else {
                                                    showNotification("Failed to generate product details", "error",
                                                        "text");
                                                }
                                            },
                                            error: function(xhr) {
                                                console.log(xhr.responseText);
                                                showNotification("An error occurred while generating details.", "error",
                                                    "text");
                                            },
                                            complete: function() {
                                                button.html(originalHTML);
                                                button.prop("disabled", false);
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>

                    </div>
                </div>

                {{-- handeling time --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="h_time" class="form-label">Handeling Time</label>
                        <input type="text" class="form-control" id="h_time" name="h_time">
                    </div>
                </div>

                {{-- delivery time --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="d_time" class="form-label">Delivery Time</label>
                        <input type="text" class="form-control" id="d_time" name="d_time">
                    </div>
                </div>

                {{-- shipping container --}}
                <div id="shippingContainer" class="col-12" style="display: none;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="shipping" class="form-label">Shipping Cost ({{config('settings.currency_symbol')}})</label>
                                <input type="text" class="form-control" id="shipping" name="shipping">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="shippingAddons" class="form-label"> Shipping Addons ({{config('settings.currency_symbol')}})</label>
                                <input type="text" class="form-control" id="shippingAddons" name="shippingAddons">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="s_services" class="form-label">Shipping Services ( comma saparated)</label>
                                <input type="text" class="form-control" id="s_services" name="s_services">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- shipping opts --}}
                <div class="my-3 col-12 my-3">
                    <div class="d-flex justify-content-center text-center">
                        <div class="form-check me-2">
                            <input class="form-check-input" type="radio" name="shipping_type" id="freeShipping"
                                value="free" checked>
                            <label class="form-check-label" for="freeShipping">
                                Free Shipping
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shipping_type" id="hasShipping"
                                value="paid">
                            <label class="form-check-label" for="hasShipping">
                                Has Shipping
                            </label>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $("input[name='shipping_type']").change(function() {
                                if ($("#hasShipping").is(":checked")) {
                                    $("#shippingContainer").slideDown();
                                } else {
                                    $("#shippingContainer").slideUp();
                                    $("#shipping").val("");
                                }
                            });
                        });
                    </script>

                </div>

            </div>

            {{-- Seo fields --}}
            <div class="row mt-3">
                <div class="mb-3 col-md-6">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" name="meta_title">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="alt_image_text" class="form-label">Alt Image Text</label>
                    <input type="text" class="form-control" id="alt_image_text" name="alt_image_text">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="meta_keywords" class="form-label">Meta Keywords (comma saparated)</label>
                    <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="3"></textarea>
                </div>
            </div>

            <div id="specsContainer" class="row p-2 mt-4 rounded-2 d-none" style="background-color: #fff1f1cc;">
                <div class="text-center">Specs Fields </div>

            </div>

            <script>
                function loadSpecsFields(leafCategoryId) {
                    if (leafCategoryId) {
                        $.ajax({
                            url: "{{ route('leafCategories.specs') }}",
                            type: "POST",
                            data: {
                                id: leafCategoryId
                            },
                            success: function(response) {
                                $("#specsContainer").removeClass('d-none');
                                $("#specsContainer").html("");

                                $('#specsContainer').html(`
                                                                <div class="mb-3">
                                                                    <div>
                                                                        <span class="h5 me-1">Product Specifications</span>
                                                                        <button type="button" class="btn btn-light btn-sm"
                                                                            onclick="loadModalContent('{{ url('/') }}/admin/leaf-category/${leafCategoryId}/edit', 'Edit Leaf Category')">
                                                                            <i class="fa fa-pen"></i>
                                                                        </button>
                                                                        <button type="button" class="btn btn-light btn-sm"
                                                                            onclick="loadSpecsFields(${leafCategoryId})">
                                                                            <i class="fas fa-sync-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>`);

                                $.each(response.specFields, function(index, field) {
                                    if (!field || !field.name || !field.type) {
                                        console.warn(
                                            `Skipping invalid field at index ${index}`,
                                            field);
                                        return; // Skip if undefined or incorrect fields
                                    }

                                    var fieldHtml = '';

                                    if (field.type === 'text' || field.type === 'number') {
                                        fieldHtml = `
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">${field.name}</label>
                                            <input type="${field.type}" name="specs[${field.name}]" class="form-control">
                                        </div>`;
                                    } else if (field.type === 'select' && field.options) {
                                        var optionsHtml = field.options.split(",").map(
                                            option =>
                                            `<option value="${option.trim()}">${option.trim()}</option>`
                                        ).join("");

                                        fieldHtml = `
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">${field.name}</label>
                                                        <select name="specs[${field.name}]" class="form-control form-select">
                                                            ${optionsHtml}
                                                        </select>
                                                    </div>`;
                                    } else {
                                        console.warn(
                                            `Skipping field due to missing options: ${field.name}`,
                                            field);
                                    }

                                    $("#specsContainer").append(fieldHtml);
                                });

                                if (response.v1st) {

                                    $('.variationInput').removeClass('d-none');

                                    $('.variable1st').text(response.v1st);

                                } else {
                                    $('.variable1stMain').addClass('d-none');
                                }

                                if (response.v2nd) {
                                    $('.variable2nd').text(response.v2nd);
                                } else {
                                    $('.variable2ndMain').addClass('d-none');
                                }

                            }

                        });
                    } else {
                        $("#specsContainer").html("");
                        $("#specsContainer").addClass('d-none');
                    }
                }

                $(document).ready(function() {

                    var variable1st = $('.variable1st');
                    var variable2nd = $('.variable2nd');

                    variable1st.text('variable 1 Remains Null');
                    variable2nd.text('variable 2 Remains Null');

                    $("#leaf_category").change(function() {
                        var leafCategoryId = $(this).val();

                        loadSpecsFields(leafCategoryId);
                    });

                });
            </script>


            <div class="col-12  py-3 variationInput d-none">
                <div id="variations-container-hidden">
                    <div class="col-12 row">
                        <div class="mb-3 col-md-6 variable1stMain">
                            <label for="color" class="form-label"><span class="variable1st"></span>
                            </label>
                            <input type="text" class="form-control" id="color" name="color">
                        </div>

                        <div class="mb-3 col-md-6 variable2ndMain">
                            <label for="size" class="form-label"><span class="variable2nd"></span>
                            </label>
                            <input type="text" class="form-control" id="size" name="size">
                        </div>

                    </div>
                </div>

                <div class="form-check mt-2 d-table m-auto">
                    <input class="form-check-input" type="checkbox" id="has_variations" name="has_variations"
                        value="1">
                    <label class="form-check-label" for="has_variations">
                        Want To Add Variations For This Product
                    </label>
                    <br>

                    <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                        <strong>Note:</strong> If you want to add variations for this product, check this field and submit product data. You will be
                        redirected to the variations page further.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="text-center py-3">

                <button type="submit" class="btn btn-success">Submit Product Data</button>

            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="geminiModal" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="geminiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="geminiModalLabel">Generate Product Description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="prompt" class="form-label">Enter Prompt</label>
                    <textarea class="form-control" id="prompt" rows="3" placeholder="Describe the product..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="sendPrompt">Generate</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const hasVariationsCheckbox = document.getElementById('has_variations');

            // Toggle variations container
            hasVariationsCheckbox.addEventListener('change', function() {
                return true;
            });

        });
    </script>

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
                    `<div class="productImageDiv card px-0 col-md-2 col-6">
                        <input type="hidden" class="hiddenInputs" value="${response.image_id}" name="image_array[]">
                                                <div class="border card-body border-dark"> <img
                                                        src="{{ asset('${response.img_path}') }}"
                                                        class="card-img-top img-fluid" alt="productImage">
                                                </div>
                                                <div class="card-footer text-center">

                                                    <div>
                                                        <label class="form-label">
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

        $(document).on("click", ".imgDeleteBtn", function(e) {
            e.preventDefault();
            const btn = $(this);
            const imgId = btn.val();

            // Check if it's a pasted link
            const imageUrl = btn.closest(".productImageDiv").find("img").attr("src");
            if (imgId.startsWith('link-')) {

                removeFromHiddenUrls(imageUrl);

                btn.closest(".productImageDiv").fadeOut(function() {
                    $(this).remove();
                });
                return;
            }

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

                    const errorMessage = xhr.responseJSON?.message || "An error occurred.";
                    showNotification(errorMessage, 'danger', 'text');
                },
                complete: function() {
                    btn.prop('disabled', false);
                }
            });
        });

        $(document).on("click", ".imgDeleteBtnAddedByUrl", function(e) {
            e.preventDefault();
            const btn = $(this);
            const imgId = btn.val();

            btn.prop('disabled', true);


            btn.closest(".productImageDiv").fadeOut(function() {
                $(this).remove();
            });

            removeHiddenInput(imgId);

            showNotification('Deleted Successfully', 'success', 'text');

            btn.prop('disabled', false);

        });

        $('#addImageLinkButton').on('click', function() {
            var imageUrl = $('#imageLinkInput').val().trim();
            $original_html = $(this).html();
            $(this).html('<span class="spinner-border spinner-border-sm" role="status"></span>');
            if (!imageUrl) {
                alert('Please enter an image URL.');
                $(this).html($original_html);
                return;
            }

            var img = new Image();
            img.onload = function() {
                var imageCard = `
                <div class="productImageDiv card px-0 col-md-2 col-6">
                    <input type="hidden" class="hiddenInputs" value="${imageUrl}" name="image_urls[]">
                    <div class="border card-body border-dark">
                        <img src="${imageUrl}" class="card-img-top img-fluid" alt="productImage">
                    </div>
                    <div class="card-footer text-center">
                        <div>
                            <label class="form-label">
                                <input type="radio" name="index_image" value="${imageUrl}" class="indexImageRadio"> Set as Index
                            </label>
                        </div>
                        <button type="button" class="btn btn-danger imgDeleteBtnAddedByUrl" value="${imageUrl}">Delete</button>
                    </div>
                </div>
            `;

                $('#productImageDivMain').append(imageCard);
                $('#imageLinkInput').val('');
            };

            img.onerror = function() {
                alert('Invalid image URL. Please check the link.');
                $(this).html($original_html);
            };

            img.src = imageUrl;
            $(this).html($original_html);
        });
    </script>
@endsection
