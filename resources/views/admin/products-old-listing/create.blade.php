@extends('admin.includes.layout')
@section('headTag')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.js"></script>
    <style>
        /* General form styling */
        form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Input field styling */
        input[type="text"],
        input[type="number"],
        textarea,
        select,
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
            width: 100%;
            font-size: 1rem;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #80bdff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }

        /* Label and Required fields */
        label {
            font-weight: 500;
            font-size: 1rem;
            color: #495057;
        }

        .text-danger {
            color: #dc3545;
        }

        /* Form button styling */
        button.btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button.btn-success {
            background-color: #28a745;
            border: none;
            color: #fff;
        }

        button.btn-success:hover {
            background-color: #218838;
        }

        /* Radio and Checkbox alignment */
        .form-check-label {
            margin-left: 5px;
        }

        .form-check-input {
            margin-top: 0.3rem;
        }

        /* Shipping section visibility */
        #shippingContainer {
            padding: 15px;
            background-color: #f1f3f5;
            border-radius: 5px;
        }

        /* Dropzone styling */
        .dropzone {
            border: 2px dashed #ced4da;
            border-radius: 5px;
            background-color: #e9ecef;
            padding: 20px;
            text-align: center;
        }

        .dropzone:hover {
            border-color: #80bdff;
            background-color: #f8f9fa;
        }

        .dz-message {
            font-size: 1rem;
            color: #6c757d;
        }

        /* Variation input section styling */
        #variations-container {
            background-color: #e2f0f5;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        #variations-container .variable1stMain,
        #variations-container .variable2ndMain {
            text-align: left;
        }

        #variations-container input {
            margin-top: 10px;
        }

        /* Specs fields container */
        #specsContainer {
            background-color: #fdf1f1;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-top: 20px;
            border-radius: 8px;
        }

        #specsContainer .text-center {
            font-weight: bold;
            color: #c82333;
        }

        /* Button for generating product details */
        button#generateProduct {
            margin-left: 10px;
            font-size: 0.85rem;
        }

        /* Media section */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .input-group .btn {
            background-color: #007bff;
            color: #fff;
        }

        .input-group .btn:hover {
            background-color: #0056b3;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .form-control {
                font-size: 0.9rem;
            }

            button#product-create-button {
                width: 100%;
            }
        }
    </style>
@endsection
@section('content')
    <script>
        $('#nav-item_inventory').addClass('active');
    </script>

    <div class="container py-3">
        <div class="h4 mb-2">Create Product</div>
        <form id="product-form" action="{{ route('products.store') }}" class="needs-validation" novalidate method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="design_number" class="form-label">Design Number</label>
                        <input type="text" class="form-control" id="design_number" name="design_number">
                    </div>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <select required id="category" name="category_id" class="form-control form-select">
                        <option value="">Select</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3 col-md-6">
                    <label for="sub_category" class="form-label">Sub Category</label>
                    <select id="sub_category" name="sub_category_id" class="form-control form-select" disabled>
                        <option value="">Select</option>
                    </select>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="leaf_category" class="form-label">Leaf Category</label>
                    <select id="leaf_category" name="leaf_category_id" class="form-control form-select" disabled>
                        <option value="">Select</option>
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
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
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

                <div class="mb-3 col-md-6">
                    <label for="c_price" class="form-label">Compare Price <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="c_price" name="c_price" required>
                </div>

                <div class="col-md-12 mb-3">

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


                            <div class="row">

                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <!--<label for="shipping" class="form-label">Video</label>-->
                                        <input placeholder="video Id" type="text" class="form-control" id="video"
                                            name="video">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="align-item-center input-group mt-3">
                                        <input type="text" id="imageLinkInput" class="form-control"
                                            placeholder="Paste image URL here">
                                        <button type="button" id="addImageLinkButton" class="btn btn-primary">Add
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

                <div class="col-md-6">
                    <div class="mb-3 mt-3">
                        <label for="short_description" class="form-label">Title (140MAX)</label>
                        <textarea class="form-control" id="short_description" name="short_description" rows="2"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description

                            <button type="button" class="btn btn-primary btn-sm" id="generateProduct"
                                data-bs-toggle="modal" data-bs-target="#geminiModal">
                                Generate Product Details
                            </button>


                        </label>
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
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="is_featured">Featured</label>
                        <select required type="text" name="is_featured" id="is_featured" class="form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select required type="text" name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="h_time" class="form-label">Handeling Time</label>
                        <input type="text" class="form-control" id="h_time" name="h_time">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="d_time" class="form-label">Delivery Time</label>
                        <input type="text" class="form-control" id="d_time" name="d_time">
                    </div>
                </div>


                <div id="shippingContainer" class="col-12 row" style="display: none;">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="shipping" class="form-label">International Shipping Cost ($)</label>
                            <input type="text" class="form-control" id="shipping" name="shipping">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inrShipping" class="form-label">India Shipping Cost (₹)</label>
                            <input type="text" class="form-control" id="inrShipping" name="inrShipping">
                        </div>
                    </div>

                </div>



                <div class="my-3 col-6">
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
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="s_services" class="form-label">Shipping Services</label>
                        <input type="text" class="form-control" id="s_services" name="s_services">
                    </div>
                </div>



            </div>

            <div id="specsContainer" class="row p-2 mt-4 rounded-2 d-none" style="background-color: #fff1f1cc;">
                <div class="text-center">Specs Fields </div>



            </div>

            <script>
                function reloadElements(leafCategoryId) {
                    if (leafCategoryId) {
                        $.ajax({
                            url: "{{ route('leafCategories.specs') }}",
                            type: "POST",
                            data: {
                                id: leafCategoryId
                            },
                            success: function(response) {
                                $("#specsContainer").removeClass('d-none');
                                $("#specsContainer").html(""); // Clear previous fields

                                $('#specsContainer').html(`<div class="mb-3">
                                    <button type="button" class="btn btn-light btn-sm"
                                        onclick="loadModalContent('{{ url('/') }}/admin/leaf-category/${leafCategoryId}/edit', 'Edit Leaf Category')">
                                        <i class="fa fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm"
                                        onclick="reloadElements(${leafCategoryId})">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>`);

                                $.each(response.specFields, function(index, field) {
                                    if (!field || !field.name || !field.type) {
                                        console.warn(
                                            `Skipping invalid field at index ${index}`,
                                            field);
                                        return; // Skip undefined or incorrect fields
                                    }

                                    var fieldHtml = '';

                                    if (field.type === 'text' || field.type === 'number') {
                                        fieldHtml = `
                                        <div class="mb-3 col-md-6">
                                            <label>${field.name}</label>
                                            <input type="${field.type}" name="specs[${field.name}]" class="form-control">
                                        </div>`;
                                    } else if (field.type === 'select' && field.options) {
                                        var optionsHtml = field.options.split(",").map(
                                            option =>
                                            `<option value="${option.trim()}">${option.trim()}</option>`
                                        ).join("");

                                        fieldHtml = `
                                        <div class="mb-3 col-md-6">
                                            <label>${field.name}</label>
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
                    }
                }

                $(document).ready(function() {

                    var variable1st = 'variable 1 Remains Null';
                    var variable2nd = 'variable 2 Remains Null';

                    $('.variable1st').text(variable1st);
                    $('.variable2nd').text(variable2nd);


                    $("#leaf_category").change(function() {
                        var leafCategoryId = $(this).val();

                        if (leafCategoryId) {
                            $.ajax({
                                url: "{{ route('leafCategories.specs') }}",
                                type: "POST",
                                data: {
                                    id: leafCategoryId
                                },
                                success: function(response) {
                                    $("#specsContainer").removeClass('d-none');
                                    $("#specsContainer").html(""); // Clear previous fields

                                    $('#specsContainer').html(`<div class="mb-3">
                                    <button type="button" class="btn btn-light btn-sm"
                                        onclick="loadModalContent('{{ url('/') }}/admin/leaf-category/${leafCategoryId}/edit', 'Edit Leaf Category')">
                                        <i class="fa fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm"
                                        onclick="reloadElements(${leafCategoryId})">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>`);

                                    $.each(response.specFields, function(index, field) {
                                        if (!field || !field.name || !field.type) {
                                            console.warn(
                                                `Skipping invalid field at index ${index}`,
                                                field);
                                            return; // Skip undefined or incorrect fields
                                        }

                                        var fieldHtml = '';

                                        if (field.type === 'text' || field.type === 'number') {
                                            fieldHtml = `
                                        <div class="mb-3 col-md-6">
                                            <label>${field.name}</label>
                                            <input type="${field.type}" name="specs[${field.name}]" class="form-control">
                                        </div>`;
                                        } else if (field.type === 'select' && field.options) {
                                            var optionsHtml = field.options.split(",").map(
                                                option =>
                                                `<option value="${option.trim()}">${option.trim()}</option>`
                                            ).join("");

                                            fieldHtml = `
                                        <div class="mb-3 col-md-6">
                                            <label>${field.name}</label>
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
                    });

                });
            </script>

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

            <div class="col-12  py-3 variationInput d-none">
                <div class="form-check d-table m-auto">
                    <input class="form-check-input" type="checkbox" id="has_variations" name="has_variations"
                        value="1">
                    <label class="form-check-label" for="has_variations">
                        Product Has Variations
                    </label>
                </div>
            </div>

            <div id="variations-container" class="py-2" style="display: none;">

                <div class="row text-center fw-bold py-3">
                    <div class="mb-3 col-md-6 variable1stMain">
                        Variable 1st
                        (<span class="variable1st"></span>)

                    </div>

                    <div class="mb-3 col-md-6">
                        Variable 2nd
                        (<span class="variable2nd">Remains NULL</span>)
                    </div>

                </div>

                <div id="variations">
                    <!-- Variation rows will be added here -->
                </div>
                <button type="button" class="btn d-table m-auto btn-primary" id="add-variation">Add Variation</button>
            </div>

            <div class="row">
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
            <button id="product-create-button" type="submit" class="btn btn-success mb-3">Create Product</button>
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
            const variationsContainer = document.getElementById('variations-container');
            const variationsContainerHidden = document.getElementById('variations-container-hidden');
            const addVariationButton = document.getElementById('add-variation');
            const variations = document.getElementById('variations');
            const hasVariationsCheckbox = document.getElementById('has_variations');
            let index = 0;

            // Toggle variations container
            hasVariationsCheckbox.addEventListener('change', function() {
                variationsContainer.style.display = this.checked ? 'block' : 'none';
                variationsContainerHidden.style.display = this.checked ? 'none' : 'block';
            });

            // Add variation button handler
            addVariationButton.addEventListener('click', function() {
                const variationRow = createVariationRow(index);
                variations.appendChild(variationRow);
                initializeDropzone(`#dropzone-variation-images-${index}`, index);
                index++;
            });

            // Function to create a variation row
            function createVariationRow(index) {
                const variationRow = document.createElement('div');
                variationRow.classList.add('row', 'mb-3', 'justify-content-center', 'pt-3', 'position-relative');
                variationRow.style.backgroundColor = '#f9f9f9';
                variationRow.style.border = '1px solid #ddd';
                variationRow.style.borderRadius = '4px';
                variationRow.style.padding = '10px';
                variationRow.style.marginBottom = '10px';
                variationRow.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';

                variationRow.innerHTML = `
                
                    <div class="col-md-2">
                       
                        <input type="text" name="variations[${index}][color]" class="form-control" placeholder="variable 1st">
                    </div>
                    <div class="col-md-2">
                 
                        <input type="text" name="variations[${index}][size]" class="form-control" placeholder="variable 2nd">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variations[${index}][sku]" class="form-control" placeholder="SKU" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variations[${index}][price]" class="form-control" placeholder="Price" required>
                    </div>
                      <div class="col-md-2">
                        <input type="text" name="variations[${index}][c_price]" class="form-control" placeholder="c price" required>
                    </div>
                    <div class="col-md-1">
                        <input type="text" name="variations[${index}][quantity]" class="form-control" placeholder="Qty" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-variation">X</button>
                    </div>
                    
                    <div class="col-md-12 mt-2">
                        <label>Images for this variation:</label>
                        <div id="dropzone-variation-images-${index}" class="dropzone dz-clickable">
                            <div class="dz-message needsclick">
                                Drop files here or click to upload.
                            </div>
                        </div>
                        <div class="variation-image-preview mt-3 d-flex flex-wrap"></div>
                    </div>
                    
                      <div class="col-md-12">
                                    <div class="mt-3">
                                        <!--<label for="shipping" class="form-label">Video</label>-->
                                        <input placeholder="video" type="text" class="form-control" name="variations[${index}][video]">
                                    </div>
                    </div>
                `;

                // Remove variation handler
                variationRow.querySelector('.remove-variation').addEventListener('click', function() {
                    variationRow.remove();
                });

                return variationRow;
            }

            // Initialize Dropzone
            function initializeDropzone(selector, index) {
                new Dropzone(selector, {
                    url: "{{ route('tempImages.create') }}",
                    maxFiles: 10,
                    paramName: 'image',
                    addRemoveLinks: true,
                    acceptedFiles: "image/jpeg,image/png,image/gif",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(file, response) {

                        const previewDiv = document.querySelector(
                            `${selector} ~ .variation-image-preview`);
                        const imageHtml = `
                            <div class="productImageDiv card px-0 col-md-2 col-6">
                                <div class="border card-body border-dark">
                                    
                        <input type="hidden" class="hiddenInputs" value="${response.image_id}" name="variations[${index}][image_array][]">
                                    <img src="{{ asset('${response.img_path}') }}" class="card-img-top img-fluid" alt="productImage">
                                </div>
                                <div class="card-footer text-center">
                                    <label>
                                        <input type="radio" name="variations[${index}][index_image]" checked value="${response.image_id}" class="indexImageRadio"> Set as Index
                                    </label>
                                    <button type="button" class="btn btn-danger imgDeleteBtn" value="${response.image_id}">Delete</button>
                                </div>
                            </div>`;
                        previewDiv.insertAdjacentHTML('beforeend', imageHtml);

                        file.previewElement.innerHTML = "";
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message || "An error occurred.";
                        showNotification(errorMessage, 'danger', 'text');
                    }
                });
            }
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

                                window.location.href = "{{ route('products.index') }}"

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
