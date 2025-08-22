<section>
    <form id="categoryCreatForm" method="post" class="needs-validation custom-form" novalidate enctype="multipart/form-data">
        <!-- Default box -->
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">
                    <p class="p-1 text-center" id="responseText"></p>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code">Coupon Code <span class="text-danger">*</span></label>
                                <input autocomplete="off" required type="text" name="code" id="code"
                                    class="form-control" placeholder="coupon code">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Coupon Name</label>
                                <input autocomplete="off" type="text" name="name" id="name"
                                    class="form-control" placeholder="coupon name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses">Max Uses <small>(How many times the coupon can be used)</small>
                                </label>
                                <input type="text" name="max_uses" id="max_uses" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses_user">Max user uses <small>(how many times user can use a
                                        coupon)</small> </label>
                                <input type="number" name="max_uses_user" id="max_uses_user" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type">Coupon Type <span class="text-danger">*</span></label>
                                <select required name="type" id="type" class="form-select">
                                    <option value="percent">Percent</option>
                                    <option value="freeShipping">Free Shipping</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_amount">Discount amount <span id="discount_amount_label"
                                        class="text-danger">*</span></label>
                                <input required type="number" name="discount_amount" id="discount_amount"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_amount">Min Cart Value <small>(To Apply Coupon)</small><span
                                        id="min_amount_label"></span></label>
                                <input type="number" name="min_amount" id="min_amount" class="form-control"
                                    placeholder="Min amount">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select required type="text" name="status" id="status" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="starts_at">Start Date Time</label>
                                <input autocomplete="off" type="datetime-local" name="starts_at" id="starts_at"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expires_at">Expiry Date Time</label>
                                <input autocomplete="off" type="datetime-local" name="expires_at" id="expires_at"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description">Coupon Code Description</label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-5 pt-3 text-center">
            <span id="categoryCreatFormSpinner">
                <button type="submit" class="btn btn-primary">Create</button>
            </span>

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
                url: "{{ route('discount.create') }}",
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


                        window.location.href = "{{ route('discount.view') }}"

                    } else {

                        showNotification(response.message, 'danger', 'text');
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
</script>
<script>
    $('#type').change(function(e) {
        e.preventDefault();

        if ($('#type').val() == 'freeShipping') {
            $('#min_amount').prop('required', true);
            $('#min_amount_label').html('<span class="text-danger">*</span>');

            $('#discount_amount').prop('required', false);
            $('#discount_amount_label').html('');

        } else {
            $('#min_amount').prop('required', false);
            $('#min_amount_label').html('');

            $('#discount_amount').prop('required', true);
            $('#discount_amount_label').html('<span class="text-danger">*</span>');
        }

    });
</script>
