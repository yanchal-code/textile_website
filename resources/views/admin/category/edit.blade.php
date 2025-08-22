<section>
    <!-- Default box -->
    <div class="container-fluid">
        <form id="categoryCreatForm" method="post" class="needs-validation custom-form" novalidate enctype="multipart/form-data">

            <p class="p-1 text-center" id="responseText"></p>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" value="{{ $category->name }}" name="name" required id="name"
                            class="form-control" placeholder="Name">

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug <small>(System Generated)</small><span id="slugLoader"
                                class="d-none"><span class="loader"></span> Loading...</span>
                        </label>
                        <input value="{{ $category->slug }}" type="text" required name="slug" id="slug"
                            class="form-control" placeholder="Slug">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-group">
                            <label for="updimage1" class="form-label">Category Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control img_input" name="categoryImage" accept="image/jpeg, image/png, image/webp">
                            <div class="invalid-feedback">
                                Image is required.
                            </div>
                        </div>
                    </div>

                    <span>Current</span>

                    <img src="{{ asset($category->image) }}" alt="img" class="img-fluid" style="max-height:100px;">

                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select type="text" name="status" id="status" class="form-select">
                            <option @if ($category->status == 1) selected @endif value="1">Active
                            </option>
                            <option @if ($category->status == 0) selected @endif value="0">Inactive
                            </option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="pb-5 pt-3 text-center">
                <span id="categoryCreatFormSpinner">
                    <button type="submit" class="btn btn-primary">Update</button>
                </span>
                <a href="{{ route('categories.view') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>

<script>
    $('#categoryCreatForm').submit(function(e) {
        event.preventDefault();
        event.stopPropagation();
        var form = $(this);
        if (form[0].checkValidity() === true) {
            var formData = new FormData(this);
            $.ajax({
                type: "post",
                url: "{{ route('categories.edit', $category->id) }}",
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
                        '<button type="submit" class="btn btn-primary">Update</button>')
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
                        $('#notification').css('background-color', '#00a65a')

                        window.location.href = "{{ route('categories.view') }}"

                    }
                },
                error: function(xhr, status, error) {
                    $('#categoryCreatFormSpinner').html(
                        '<button type="submit" class="btn btn-primary">Update</button>');

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
    var doneTypingInterval = 700; // The delay as needed (in milliseconds)

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
