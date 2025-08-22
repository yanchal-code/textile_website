<div class="container">
    <form id="carouselCreatForm" method="POST" class="needs-validation custom-form" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="heighlight" class="form-label">Heighlight</label>
            <input type="text" class="form-control" id="heighlight" name="heighlight" required>
        </div>

        <div class="mb-3">
            <label for="image_path" class="form-label">Image</label>
            <input required type="file" class="form-control img_input" id="image_path" name="image_path" required>
        </div>
        <div class="mb-3">
            <label for="btn_text" class="form-label">Btn Text</label>
            <input required type="btn_text" class="form-control" id="btn_text" name="btn_text">
        </div>
        <div class="mb-3">
            <label for="link" class="form-label">Link</label>
            <input required type="url" class="form-control" id="link" name="link">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="mb-3">
            <label for="status">Status <span class="text-danger">*</span></label>
            <select type="text" name="status" id="status" class="form-select">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <span id="carouselCreatFormSpinner">
            <button type="submit" class="btn btn-success">Create</button>
        </span>
    </form>
</div>

<script>
    $('#carouselCreatForm').submit(function(e) {
        event.preventDefault();
        event.stopPropagation();
        var form = $(this);
        if (form[0].checkValidity() === true) {
            var formData = new FormData(this);
            $.ajax({
                type: "post",
                url: "{{ route('carousel.store') }}",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                dataType: "json",
                beforeSend: function() {
                    $('#carouselCreatFormSpinner').html(
                        '<span id="slugLoader"><span class="loader"></span> Loading...</span>'
                    );
                },
                success: function(response) {
                    $('#carouselCreatFormSpinner').html(
                        '<button type="submit" class="btn btn-success">Create</button>')
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


                        window.location.href = "{{ route('carousel.index') }}";

                    }
                },
                error: function(xhr, status, error) {
                    $('#carouselCreatFormSpinner').html(
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
</script>
