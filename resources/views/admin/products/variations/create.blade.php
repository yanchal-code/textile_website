@extends('admin.includes.layout')
@section('headTag')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.css">
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.js"></script>
@endsection
@section('content')
    <script>
        $('#nav-item_inventory').addClass('active');
    </script>
    @php

        $secondVarField = $product->leafCategory->v2nd != '' ? true : false;

    @endphp
    <div class="container py-3">

        <div class="table-responsive my-3">
            <table class="table table-bordered table-striped align-middle">
                <tbody>
                    <tr>
                        <th>Product Name</th>
                        <td colspan="2">{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>Main SKU</th>
                        <td colspan="2">{{ $product->sku }}</td>
                    </tr>
                    <tr>
                        <th>{{ $product->leafCategory->v1st }}</th>
                        <td colspan="2">{{ $product->color }}</td>
                    </tr>
                    @if ($secondVarField)
                        <tr>
                            <th>{{ $product->leafCategory->v2nd }}</th>
                            <td colspan="2">{{ $product->size }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Price</th>
                        <td>{{ $product->price }}</td>
                        <td><strong>Compare Price:</strong> {{ $product->compare_price }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="h4 text-center mb-3">
            Product Variation Page

        </div>
        <form id="product-variation-form"
            onsubmit="return handleFormSubmit('#product-variation-form', '{{ route('variations.store', $product->id) }}')"
            class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid">

                <div id="variations">
                    <!-- Variation rows will be added here -->
                </div>

            </div>

            <div class="py-3 text-center">
                <button type="button" class="btn btn-dark" id="add-variation"> <i class="fa fa-plus"></i>
                    Add Variation</button>
                <button id="product-create-button" type="submit" class="btn btn-success">Submit Variation Data</button>
            </div>
        </form>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const addVariationButton = document.getElementById('add-variation');
            const variations = document.getElementById('variations');

            let index = 0;


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
                variationRow.style.backgroundColor = '#f5f7fa';
                variationRow.style.border = '0.8px solid #ddd';
                variationRow.style.borderRadius = '4px';
                variationRow.style.padding = '10px';
                variationRow.style.marginBottom = '10px';
                // variationRow.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';

                variationRow.innerHTML = `
                    <div class="col-md-2">

                        <input type="text" name="variations[${index}][color]" class="form-control" placeholder="{{ $product->leafCategory->v1st }}" required>
                    </div>
                    <div class="col-md-2 {{ $secondVarField ? '' : ' d-none' }}">

                        <input type="text" name="variations[${index}][size]" class="form-control {{ $secondVarField ? '' : ' d-none' }}" placeholder="{{ $product->leafCategory->v2nd }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="variations[${index}][sku]" class="form-control" value="{{ $product->sku }}-var-${index+1}" placeholder="SKU" required>
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

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Images for this variation</label>
                        <div id="dropzone-variation-images-${index}" class="dropzone dz-clickable">
                            <div class="dz-message needsclick">
                                Drop files here or click to upload.
                            </div>
                        </div>
                        <div class="variation-image-preview mt-3 d-flex flex-wrap"></div>
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

            addVariationButton.click();
        });
    </script>
@endsection
