@extends('admin.includes.layout')

@section('content')
    <script>
        $('#nav-item_users').addClass('active');
    </script>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-2 mb-0">
                    <div class="h4 mb-3 text-dark fw-bold">{{ $type == 'admins' ? 'Admins' : 'Users' }}</div>
                </div>

                <div class="col-10 text-end">


                    @if ($type == 'admins')
                        <a class="btn btn-dark btn-sm" href="{{ route('users.view', 'users') }}">Normal Users</a>
                        <a href="{{ route('users.view', 'admins') }}" class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </a>
                    @else
                        <a class="btn btn-dark btn-sm" href="{{ route('users.view', 'admins') }}">Admins Users</a>
                        <a href="{{ route('users.view') }}" class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </a>
                    @endif

                    <button class="btn btn-primary btn-sm" id="createUserBtn">
                        <i class="fas fa-user-plus"></i> Create New User
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center py-2 text-center my-4">
                <div class="me-3">
                    <span class="fs-6">Total Users</span>
                    <span class="badge bg-primary fs-6">{{ $totalUsers }}</span>
                </div>
                <div class="me-3">
                    <span class="fs-6">Active Users</span>
                    <span class="badge bg-success fs-6">{{ $totalUsersActive }}</span>
                </div>
                <div>
                    <span class="fs-6">Blocked Users</span>
                    <span class="badge bg-danger fs-6">{{ $totalUsersBlocked }}</span>
                </div>
            </div>
        </div>

        <!-- /.container-fluid -->
    </section>
    <section>
        <div class="container-fluid mt-4">
            <div class="modal fade" id="userCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <div>Create New User</div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-bs-label="Close"><span
                                    aria-hidden="true"></span></button>
                        </div>
                        <div class="modal-body">
                            <div id="body">
                                <form id="createUser" method="post" class="needs-validation custom-form" novalidate>
                                    <!-- Default box -->
                                    <div class="container-fluid">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name">Name</label>
                                                    <input required type="text" name="name" id="name"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="email">Email</label>
                                                    <input required type="email" name="email" id="email"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="phone">Phone</label>
                                                    <input required type="number" name="phone" id="phone"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="ms-3 ms-3">
                                                            <input required class="form-check-input" type="radio"
                                                                name="status" id="activeCreateUser" value="0">
                                                            <label class="form-check-label" for="activeCreateUser">
                                                                Active
                                                            </label>
                                                        </div>

                                                        <div class="ms-3 me-3">
                                                            <input required class="form-check-input" type="radio"
                                                                name="status" id="blockedCreateUser" value="1">
                                                            <label class="form-check-label" for="blockedCreateUser">
                                                                Blocked
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="password">Password</label>
                                                    <div class="input-group">
                                                        <input required type="password" name="password" id="password"
                                                            class="form-control">
                                                        <i style="cursor: pointer;"
                                                            class="showPassword btn-dark d-flex align-items-center px-2 fs-3 fas fa-grin-beam"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="cpassword">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input required type="password" name="cpassword" id="cpassword"
                                                            class="form-control">
                                                        <i style="cursor: pointer;"
                                                            class="showPassword btn-dark d-flex align-items-center px-2 fs-3 fas fa-grin-beam"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="user_type"
                                                        id="adminRadio" value="admin">
                                                    <label class="form-check-label" for="adminRadio">Admin User</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input checked class="form-check-input" type="radio"
                                                        name="user_type" id="studentRadio" value="student">
                                                    <label class="form-check-label" for="studentRadio">Normal User</label>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3 text-center">
                                                <div class="row align-items-center justify-content-end me-md-5 me-3">
                                                    <span id="categoryCreatFormSpinner">
                                                        <button type="submit" class="btn btn-primary">Create</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>

                            </div>
                            <div id="modalDiscription" class="p-2 text-center d-none"></div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="userCreateModal2" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <div>Edit User</div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-bs-label="Close"><span
                                    aria-hidden="true"></span></button>
                        </div>
                        <div class="modal-body">
                            <div id="body">
                                <form id="updateUser" method="post" class="needs-validation custom-form" novalidate>
                                    <!-- Default box -->
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nameUpd">Name</label>
                                                    <input required type="text" name="name" id="nameUpd"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="emailUpd">Email</label>
                                                    <input required type="email" name="email" id="emailUpd"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="phoneUpd">Phone</label>
                                                    <input required type="number" name="phone" id="phoneUpd"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="passwordUpd"><small class="text-warning">(if want
                                                            to update password
                                                            then fill this
                                                            field otherwise remain blank)</small></label>
                                                    <div class="input-group">
                                                        <input type="password" placeholder="New Password" name="password"
                                                            id="passwordUpd" class="form-control password">
                                                        <i style="cursor: pointer;"
                                                            class="showPassword btn-dark d-flex align-items-center px-2 fs-3 fas fa-grin-beam"></i>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="id" value="" id="userId">
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="ms-3 ms-3">
                                                            <input required class="form-check-input" type="radio"
                                                                name="status" id="blocked" value="0">
                                                            <label class="form-check-label" for="blocked">
                                                                Active
                                                            </label>
                                                        </div>

                                                        <div class="ms-3 me-3">
                                                            <input required class="form-check-input" type="radio"
                                                                name="status" id="active" value="1">
                                                            <label class="form-check-label" for="active">
                                                                Blocked
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-center">
                                                <div class="row align-items-center justify-content-end me-md-5 me-3">
                                                    <span id="categoryCreatFormSpinner1">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div id="modalDiscription" class="p-2 text-center d-none"></div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <form method="get">
                    <div class="row mb-2 row justify-content-center">
                        <div class="col-md-3 col-5">

                            <select name="status" id="status_inputs" class="form-select d-table filterInputs">
                                <option value="">User Status</option>
                                <option {{ $selectedStatus == 'active' ? 'selected' : '' }} value="active">Active
                                </option>
                                <option {{ $selectedStatus === 'blocked' ? 'selected' : '' }} value="blocked">Blocked
                                </option>
                            </select>

                        </div>
                        <div class="col-md-5 col-7">
                            <div class="input-group mb-3">
                                <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                    class="form-control float-right" placeholder="Search"
                                    aria-describedby="button-search">
                                <button type="submit" id="button-search" class="btn btn-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive p-0">
                <table class="table table-hover text-center text-dark text-nowrap">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>

                            <th>Is blocked</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->isNotEmpty())
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td>{{ $key + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>

                                    <td>
                                        @if ($user->is_blocked == 1)
                                            <span class="badge bg-danger">Blocked</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>{{ carbon\Carbon::parse($user->created_at)->format('d M Y | h:i A') }}</td>

                                    <td>
                                        @if ($user->is_superadmin)
                                            <b>SuperAdmin</b>
                                        @else
                                            @if ($type == 'admins')
                                                <a class="btn btn-sm btn-dark"
                                                    href="{{ route('admin.permissions.edit', $user) }}">Permissions</a>
                                            @endif
                                            <button value="{{ $user->id }}"
                                                class="btn btn-success btn-sm editUserBtn"><i
                                                    class="fa fa-pen"></i></button>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="deleteCategoryFunction({{ $user->id }})"><i
                                                    class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">Records Not Found.</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $('#createUser').submit(function(e) {
            event.preventDefault();
            event.stopPropagation();
            var form = $(this);
            if (form[0].checkValidity() === true) {
                var formData = new FormData(this);
                $.ajax({
                    type: "post",
                    url: "{{ route('users.create') }}",
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
                            '<button type="submit" class="btn btn-primary">Create</button>'
                        );
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
                            form[0].reset();
                            form.removeClass('was-validated');
                            window.location.reload();

                        } else if (response.exists === true) {
                            showNotification(response.message, 'danger', 'text');
                            form[0].reset();
                            form.removeClass('was-validated');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#categoryCreatFormSpinner').html(
                            '<button type="submit" class="btn btn-primary">Create</button>'
                        );

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
        $('#updateUser').submit(function(e) {
            event.preventDefault();
            event.stopPropagation();
            var form = $(this);
            if (form[0].checkValidity() === true) {
                var formData = new FormData(this);
                $.ajax({
                    type: "post",
                    url: "{{ route('users.edit') }}",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('#categoryCreatFormSpinner1').html(
                            '<span id="slugLoader"><span class="loader"></span> Loading...</span>'
                        );
                    },
                    success: function(response) {
                        $('#categoryCreatFormSpinner1').html(
                            '<button type="submit" class="btn btn-primary">Update</button>'
                        );
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
                            form[0].reset();
                            form.removeClass('was-validated');
                            window.location.reload();
                        } else if (response.exists === true) {
                            showNotification(response.message, 'danger', 'text');
                            form[0].reset();
                            form.removeClass('was-validated');

                        }
                    },
                    error: function(xhr, status, error) {
                        $('#categoryCreatFormSpinner1').html(
                            '<button type="submit" class="btn btn-primary">Update</button>'
                        );

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

        function deleteCategoryFunction(id) {
            if (confirm('Are you sure you want to delete this user record.')) {
                $.ajax({
                    type: "post",
                    url: "{{ route('users.delete') }}",
                    data: {
                        'id': id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $(this).prop('disabled', true);
                    },
                    success: function(response) {
                        $(this).prop('disabled', false);
                        window.location.reload();
                    },
                });
            }
        }

        $('#createUserBtn').click(function() {
            $('#userCreateModal').modal('show');
        });

        $('.editUserBtn').click(function() {
            var userId = $(this).val();
            var $btn = $(this); // Get the button element

            // Disable the button and show the spinner
            $btn.prop('disabled', true);
            $btn.html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
            );

            $.ajax({
                type: "get",
                url: "{{ route('users.edit') }}",
                data: {
                    'id': userId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",

                success: function(response) {
                    if (response.status == true) {
                        $('#nameUpd').val(response.name);
                        $('#emailUpd').val(response.email);
                        $('#phoneUpd').val(response.phone);
                        $('#userId').val(response.userId);

                        $('input[name="status"][value="' + response.is_blocked + '"]').prop('checked',
                            true);
                        $('#userCreateModal2').modal('show');
                    }
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
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $btn.html(`<i class="fa fa-pen"></i>`);
                }
            });
        });



        $('.filterInputs').change(function(e) {
            e.preventDefault();
            apply_filters();
        });

        function apply_filters() {
            var keyword = $('#keyword').val();
            if (keyword != '') {
                url = "?keyword=" + keyword;
            } else {
                url = "?blank";
            }

            if ($('#status_inputs').val() != '') {
                url += '&status=' + $('#status_inputs').val();
            }

            window.location.href = url;
        }
    </script>
@endsection
