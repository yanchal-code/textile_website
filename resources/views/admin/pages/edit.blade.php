<section>
    <!-- Default box -->
    <div class="container-fluid">
        <form id="pageEditForm" method="post" class="needs-validation custom-form" novalidate enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" value="{{ $page->name }}" name="name" required id="name"
                            class="form-control" placeholder="Name">

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="slug">Slug <small>(System Generated)</small><span id="slugLoader"
                                class="d-none"><span class="loader"></span> Loading...</span>
                        </label>
                        <input value="{{ $page->slug }}" type="text" required name="slug" id="slug"
                            class="form-control" placeholder="Slug">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="content">Page Contet</label>
                        <textarea name="content" id="content" cols="30" rows="10" class="summernote" placeholder="Description">{!! $page->content !!}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select type="text" name="status" id="status" class="form-select">
                            <option @if ($page->status == 1) selected @endif value="1">Active
                            </option>
                            <option @if ($page->status == 0) selected @endif value="0">Inactive
                            </option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" value="{{ $page->meta_title }}"
                        name="meta_title">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="alt_image_text" class="form-label">Alt Image Text</label>
                    <input type="text" class="form-control" id="alt_image_text" value="{{ $page->alt_image_text }}"
                        name="alt_image_text">
                </div>

                <div class="mb-3 col-md-6">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ $page->meta_description }}</textarea>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="meta_keywords" class="form-label">Meta Keywords (comma saparated)</label>
                    <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="3">{{ $page->meta_keywords }}</textarea>
                </div>


            </div>

            <div class="pb-5 pt-3 text-center">
                <span id="categoryCreatFormSpinner">
                    <button type="submit" class="btn btn-primary">Update</button>
                </span>
                <a href="{{ route('pages.view') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 250
        });

    });
    $('#pageEditForm').submit(function(e) {
        event.preventDefault();
        event.stopPropagation();
        var form = $(this);
        if (form[0].checkValidity() === true) {
            var formData = new FormData(this);
            $.ajax({
                type: "post",
                url: "{{ route('pages.edit', $page->id) }}",
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

                        window.location.href = "{{ route('pages.view') }}"

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
