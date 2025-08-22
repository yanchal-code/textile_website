@extends('admin.includes.layout')
@section('headTag')
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="h5">Update Admin Details
                        <a href="{{ route('admin.home') }}" class="btn btn-primary btn-sm">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section>
        <form id="changePasswordForm" method="post" class="needs-validation" novalidate>
            <!-- Default box -->
            <div class="container-fluid">

                <div class="card">
                    <div class="card-body">
                        <p class="p-1text-center" id="responseText"></p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input required value="{{ Auth::guard('admin')->user()->name }}" type="text"
                                        name="name" id="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="name">User Name</label>
                                    <input required value="{{ Auth::guard('admin')->user()->email }}" type="text"
                                        name="email" id="email" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="phone">Phone Number</label>
                                    <input required value="{{ Auth::guard('admin')->user()->phone }}" type="number"
                                        name="phone" id="phone" class="form-control">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration">Current Password</label>
                                    <div class="input-group">
                                        <input required type="password" name="password" id="password"
                                            class="form-control password">

                                    </div>
                                </div>
                                <div class=" text-center text-primary">
                                    <div class="form-group form-check">
                                        <input type="checkbox" name="changePassword" id="passwordChangeBtn">
                                        <label class="form-check-label" for="passwordChangeBtn">Want to change Password
                                            ?</label>
                                    </div>
                                </div>

                                <div id="passwordChange" class="d-none">
                                    <div class="mb-3">
                                        <label for="newPassword">New Password</label>
                                        <div class="input-group">
                                            <input type="password" name="newPassword" id="newPassword"
                                                class="form-control password">

                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmNewPassword">Confirm Password</label>
                                        <input type="password" name="confirmNewPassword" id="confirmNewPassword"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="mt-3 text-center">
                                    <span id="categoryCreatFormSpinner">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <!-- /.card -->
    </section>
@endsection
@section('scripts')
    <script>
        $('#passwordChangeBtn').change(function(e) {
            $('#passwordChange').toggleClass('d-none');

            if ($('#passwordChange').hasClass('d-none')) {
                $('#newPassword').prop('required', false);
                $('#confirmNewPassword').prop('required', false);

            } else {
                $('#newPassword').prop('required', true);
                $('#confirmNewPassword').prop('required', true);
            }

        });
    </script>
    <script>
        $('#changePasswordForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            if (form[0].checkValidity() === true) {
                var formData = new $('#changePasswordForm').serialize();
                $.ajax({
                    type: "post",
                    url: "{{ route('admin.changePassword') }}",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#categoryCreatFormSpinner').html(
                            '<span id="slugLoader"><span class="loader"></span> Loading...</span>'
                        );
                    },
                    success: function(response) {
                        $('#categoryCreatFormSpinner').html(
                            '<button type="submit" class="btn btn-primary">Create</button>')
                        if (response.status == 'wrongPassword') {

                            showNotification(response.message, 'error', 'text');
                        }
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

                            showNotification(errorsHtml, 'error', 'html');

                        } else if (response.status === true) {
                            window.location.reload();
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
    </script>
@endsection
