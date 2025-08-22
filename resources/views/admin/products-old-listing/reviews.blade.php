@extends('admin.includes.layout')

@section('content')

    <script>
        $('#nav-item_inventory').addClass('active');
    </script>

    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="h3">Products</div>
                </div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section>
        <!-- Default box -->
        <div class="container-fluid">
            <span class="badge badge-dark">FIlters</span>
            <div class="border-0">
                <div class="card-header mb-2 row justify-content-start">
                    <div class=" card-title col-lg-2 mb-lg-0 mb-2 col-6">
                        <a href="{{ route('products.reviews') }}" class="btn btn-default">Reset</a>
                    </div>
                    <div class="col-lg-10 col-12">
                        <form method="get" class="row justify-content-center">

                            <div class="d-table mr-2 col mb-2">
                                <select name="status" id="status_inputs" class="form-control d-table filterInputs">
                                    <option value="">Status Filter</option>
                                    <option @if ($selectedStatus == 'active') Selected @endif value="active">Active</option>
                                    <option @if ($selectedStatus == 'hidden') Selected @endif value="hidden">Hidden</option>


                                </select>
                            </div>
                            <div class="card-tools col">
                                <div style="min-width: 230px;" class="input-group input-group">
                                    <input id="keyword" value="{{ Request::get('keyword') }}" type="text"
                                        name="keyword" class="form-control float-right" placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-bordered text-center text-nowrap">
                            <thead class="bg-dark">
                                <tr>
                                    <th width="60">Status</th>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Rated By</th>
                                    <th>Email</th>
                                    <th>Rating</th>
                                    <th>Time</th>
                                    <th>ApprovedOn</th>
                                    <th>Title</th>
                                    <th>Comment</th>

                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($ratings->isNotEmpty())
                                    @foreach ($ratings as $rating)
                                        @php
                                            $productImage =
                                                $rating->product->defaultImage ?? $rating->product->images->first();
                                        @endphp
                                        <tr>
                                            <td><span id="statusLabel{{ $rating->id }}"
                                                    class="badge {{ $rating->status == 1 ? 'bg-success' : 'bg-danger' }}">{{ $rating->status == 1 ? 'Active' : 'Hidden' }}</span>
                                            </td>
                                            <td>
                                                @if (!empty($productImage->image))
                                                    <img class="img-fluid" style="max-height: 50px"
                                                        src="{{ asset($productImage->image) }}" class="img-thumbnail">
                                                @else
                                                    <img class="img-fluid" style="max-height: 50px"
                                                        src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                        class="img-thumbnail">
                                                @endif

                                            </td>
                                            <td><a
                                                    href="{{ route('front.product', $rating->product->sku) }}">{{ $rating->product->sku }}</a>
                                            </td>

                                            <td>{{ $rating->username }}</td>
                                            <td>{{ $rating->email }}</td>
                                            <td>{{ $rating->rating }}</td>
                                            <td> {{ \Carbon\Carbon::parse($rating->created_at)->diffForHumans() }}</td>
                                            <td id="approved_on{{ $rating->id }}">
                                                {{ $rating->updated_at == null ? '0' : \Carbon\Carbon::parse($rating->updated_at)->format('d M Y') }}
                                            </td>

                                            <td style="max-width: 100px; overflow:hidden; cursor: pointer;" class="tdHtml">
                                                {!! !empty($rating->title) ? $rating->title : '<b>Not Set</b>' !!}
                                            </td>

                                            <td style="max-width: 100px; overflow:hidden; cursor: pointer;" class="tdHtml">
                                                {!! !empty($rating->comment) ? $rating->comment : '<b>Not Set</b>' !!}
                                            </td>
                                            <td>
                                                <button data-id="{{ $rating->id }}"
                                                    value="{{ $rating->status == 1 ? '0' : '1' }}"
                                                    class=" changeStatus btn-sm btn {{ $rating->status == 1 ? 'btn-warning' : 'btn-success' }}">
                                                    {{ $rating->status == 1 ? 'Hide' : 'Approve' }} </button>
                                                <button onclick="deleteCategoryFunction({{ $rating->id }})"
                                                    class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="11">Records Not Found.</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="col-12"> {{ $ratings->withQueryString()->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->

        <div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="body">

                            <div class="container-fluid">
                                <div id="viewTd" class="row text-center justify-content-center">
                                </div>
                            </div>

                            <div id="modalDiscription" class="p-2 text-center d-none"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    <script>
        function deleteCategoryFunction(id) {
            if (confirm('Are you sure you want to delete this review.')) {
                $.ajax({
                    type: "post",
                    url: "{{ route('reviews.delete') }}",
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
                    error: function(xhr, status, error) {
                        $(this).prop('disabled', false);

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
            }
        }

        $('.changeStatus').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var elementId = '#approved_on' + id;
            var statusLabel = '#statusLabel' + id;
            var status = $(this).val();
            var btn = $(this);
            $.ajax({
                type: "post",
                url: "{{ route('products.reviews') }}",
                data: {
                    'id': id,
                    'status': status
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                beforeSend: function() {
                    btn.prop('disabled', true);
                },
                success: function(response) {
                    btn.prop('disabled', false);

                    if (response.status == false) {
                        showNotification(response.message, 'danger', 'text');
                    } else if (response.status == true) {
                        btn.val(response.value);
                        if (response.value == 1) {
                            btn.text('Approve');
                            $(elementId).text(response.updated_At);

                            $(statusLabel).removeClass(' bg-success');
                            $(statusLabel).addClass(' bg-danger');
                            $(statusLabel).text('Hidden');
                            btn.removeClass('btn-warning');
                            btn.addClass('btn-success');
                        } else {
                            btn.text('Hide');
                            $(elementId).text(response.updated_At);

                            $(statusLabel).addClass(' bg-success');
                            $(statusLabel).removeClass(' bg-danger');
                            $(statusLabel).text('Active');
                            btn.removeClass('btn-success');
                            btn.addClass('btn-warning');

                        }

                        showNotification(response.message, 'success', 'text');
                    }
                },
                error: function(xhr, status, error) {
                    btn.prop('disabled', false);

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

        });


        function viewHtmlORtext(data, type) {
            $('#viewTd').html('');
            if (type == 'html') {
                $('#viewTd').html(data);

            } else {
                $('#viewTd').text(data);
            }

            $('#myModal').modal('show');
        }

        $('.tdHtml').click(function(e) {
            var tdHtml = $(this).html();
            viewHtmlORtext(tdHtml, 'html');
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
