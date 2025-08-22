{{-- @extends('admin.includes.layout')

@section('content') --}}

<section>

    <form id="subCategoryCreatForm" method="post" class="needs-validation custom-form" novalidate>
        <!-- Default box -->
        <div class="container-fluid">
            <p class="p-1 text-center" id="responseText"></p>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Sub Category <span class="text-danger">*</span></label>
                        <select required name="category_id" id="category_id" class="form-select">
                            <option value="">Select a category</option>

                            @if ($subCategories->isNotEmpty())
                                @foreach ($subCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @else
                                <option value="">No Data Found</option>
                            @endif

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input required type="text" name="name" id="name" class="form-control"
                            placeholder="Name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug <small>(System Generated)</small><span
                                class="text-danger">*</span><span id="slugLoader" class="d-none"><span
                                    class="loader"></span> Loading...</span>
                        </label>
                        <input required type="text" name="slug" id="slug" class="form-control"
                            placeholder="Slug">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select required type="text" name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="form-group">
                            <label for="updimage1" class="form-label">Category Image <span class="text-danger">*</span></label>
                            <input type="file" required
                                class="form-control img_input"
                                name="categoryImage" accept="image/jpeg, image/png, image/webp">
                            <div class="invalid-feedback">
                                Image is required.
                            </div>

                        </div>
                    </div>
                </div>

                <span class="alert alert-info alert-dismissible fade show">
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Note:</strong> This is a <strong>Leaf Category</strong>, which allows you to define
                    <strong>Specification Fields</strong> and optional <strong>Variation Attributes</strong> for
                    products under this category.<br><br>
                    Uses:
                    <ul class="mb-0">
                        <li>Add <strong>specification fields</strong> (e.g., Material, Weight, Dimensions) that will
                            apply to all products in this category.</li>
                        <li><span class="fw-bold">Only If the products will have variations</span> (e.g., Size, Color), enable variations and enter the
                            <strong>Variable Name For Variation</strong>. These will be used to generate different versions of the
                            products under that leaf category.</li>
                    </ul>
                    This setup ensures consistent structure for product details and makes it easier to manage product
                    variations and filters.
                </span>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="v1st" class="form-label">1st Variable For Variation</label>
                        <input type="text" name="v1st" id="v1st" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="v2nd" class="form-label">2nd Variable For Variation</label>
                        <input type="text" name="v2nd" id="v2nd" class="form-control" placeholder="">
                    </div>
                </div>

            </div>

            <div class="text-center h4 my-3">Product Specs Fields<span class="text-danger">*</span></div>
            <div id="specFieldsContainer"></div>
            <div class="text-center py-2">
                <button type="button" id="addField" class="btn btn-dark btn-sm"> <i class="fa fa-plus"></i> Add
                    Spec Field</button>
            </div>
            <div class="pb-5 pt-3 text-center">
                <span id="categoryCreatFormSpinner">
                    <button type="submit" class="btn btn-primary">Create</button>
                </span>
                <a href="{{ route('leafCategories.view') }}" class="btn btn-outline-dark ml-3">Cancel</a>

            </div>
        </div>
    </form>
    <!-- /.card -->
</section>

<script>
    $('#subCategoryCreatForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        if (form[0].checkValidity() === true) {
            var formData = new FormData(this);
            $.ajax({
                type: "post",
                url: "{{ route('leafCategories.create') }}",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                dataType: "json",
                beforeSend: function() {
                    $('#categoryCreatFormSpinner').html(
                        '<span id="slugLoader"><span class="loader"></span> Loading...</span>'
                    );
                },
                success: function(response) {
                    $('#categoryCreatFormSpinner').html(
                        '<button type="submit" class="btn btn-primary">Create</button>')
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


                        showNotification(errorsHtml, 'danger', 'html');
                    } else if (response.status === true) {


                        window.location.href = "{{ route('leafCategories.view') }}"

                    }
                },
                error: function(xhr, status, error) {
                    $('#categoryCreatFormSpinner').html(
                        '<button type="submit" class="btn btn-primary">Create</button>');

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
        } else {
            form.addClass('was-validated');
        }
    });

    var typingTimer;
    var doneTypingInterval = 700;

    $('#name').keyup(function() {
        clearTimeout(typingTimer);
        var currentTitle = $(this).val();
        var currentTitleLength = currentTitle.length;
        if (currentTitleLength >= 3) {
            typingTimer = setTimeout(function() {
                triggerAjaxRequest(currentTitle);
            }, doneTypingInterval);
        }
    });

    function triggerAjaxRequest(titleName) {
        $.ajax({
            type: "post",
            url: "{{ route('categories.slug') }}",
            data: {
                'title': titleName
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            beforeSend: function() {
                $('#slugLoader').toggleClass('d-none');
            },
            success: function(response) {
                $('#slugLoader').toggleClass('d-none');
                if (response.status === true) {
                    $('#slug').val(response.slug);
                }
            },
        });
    };
</script>

<script>
    $(document).ready(function() {
        let fieldIndex = 0;

        // Add new field
        $("#addField").click(function() {
            let fieldHTML = `
                                    <div class="spec-field card shadow-sm p-3 mb-3 position-relative">
                                        <div class="row g-3 align-items-end">
                                            <!-- Field Name -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Field Name</label>
                                                <input type="text" name="spec_fields[${fieldIndex}][name]" class="form-control" placeholder="Enter field name" required>
                                            </div>

                                            <!-- Field Type -->
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Field Type</label>
                                                <select name="spec_fields[${fieldIndex}][type]" class="form-select field-type" required>
                                                    <option value="text">Text</option>
                                                    <option value="number">Number</option>
                                                    <option value="select">Select</option>
                                                </select>
                                            </div>

                                            <!-- Options (Only for Select Fields) -->
                                            <div class="col-md-4 options-container d-none">
                                                <label class="form-label fw-bold">Options <span class="text-muted">(Comma Separated)</span></label>
                                                <input type="text" name="spec_fields[${fieldIndex}][options]" class="form-control" placeholder="e.g., Red, Blue, Green">
                                            </div>

                                            <!-- Remove Button -->
                                            <div class="col-md-12 text-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-field">
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                 `;

            $("#specFieldsContainer").append(fieldHTML);
            fieldIndex++;
        });

        // Toggle options input for select fields
        $(document).on("change", ".field-type", function() {
            let optionsContainer = $(this).closest(".spec-field").find(".options-container");
            if ($(this).val() === "select") {
                optionsContainer.removeClass("d-none");
            } else {
                optionsContainer.addClass("d-none");
            }
        });

        // Remove field
        $(document).on("click", ".remove-field", function() {
            $(this).closest(".spec-field").remove();
        });
    });
</script>

{{-- @endsection --}}
