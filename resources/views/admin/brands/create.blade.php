<section>
    <script>
        $('#nav-item_stone_stock').addClass('active');
    </script>
    <form id="categoryCreatForm" method="post" class="needs-validation custom-form" novalidate enctype="multipart/form-data">
        <!-- Default box -->
        <div class="container-fluid">


            <p class="p-1 text-center" id="responseText"></p>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input required type="text" name="name" id="name" class="form-control"
                            placeholder="Name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="slug">Slug <small>(System Generated)</small><span id="slugLoader"
                                class="d-none"><span class="loader"></span> Loading...</span>
                        </label>
                        <input required type="text" readonly name="slug" id="slug" class="form-control"
                            placeholder="Slug">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select type="text" name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="pb-5 pt-3 text-center">
            <span id="categoryCreatFormSpinner">
                <button type="submit" class="btn btn-primary">Create</button>
            </span>
            <a href="{{ route('brands.view') }}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
    </form>
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
                url: "{{ route('brands.create') }}",
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

                        window.location.href = "{{ route('brands.view') }}";

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
    var doneTypingInterval = 700; // The delay (in milliseconds)

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
