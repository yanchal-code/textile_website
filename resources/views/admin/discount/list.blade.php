@extends('admin.includes.layout')
@section('headTag')
@endsection
@section('content')
    <script>
        $('#nav-item_discount_code').addClass('active');
    </script>
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-6 mb-3">
                    <div class="h4">Coupon Codes</div>
                </div>
                <div class="col-6 text-end">
                    <button onclick="loadModalContent('{{ route('discount.create') }}', 'Create New Coupon Code')"
                        class="btn btn-sm btn-primary">New Coupon Code</button>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section>
        <!-- Default box -->
        <div class="container-fluid">

            <form method="get">
                <div class="row">
                    <div class="col-6 col-lg-4">
                        <select class="form-select" name="search_by">
                            <option value="">Search By</option>
                            <option {{ Request::get('search_by') == 'code' ? 'selected' : '' }} value="code">Code
                            </option>
                            <option {{ Request::get('search_by') == 'name' ? 'selected' : '' }} value="name">Name
                            </option>
                            <option {{ Request::get('search_by') == 'max_uses' ? 'selected' : '' }} value="max_uses">
                                Max
                                uses</option>
                            <option {{ Request::get('search_by') == 'max_uses_user' ? 'selected' : '' }}
                                value="max_uses_user">
                                Max user uses</option>
                            <option {{ Request::get('search_by') == 'discount_amount' ? 'selected' : '' }}
                                value="discount_amount">DIscount amount</option>
                            <option {{ Request::get('search_by') == 'min_amount' ? 'selected' : '' }} value="min_amount">
                                Min Price</option>
                            <option {{ Request::get('search_by') == 'starts_at' ? 'selected' : '' }} value="starts_at">
                                Start Date</option>
                            <option {{ Request::get('search_by') == 'expires_at' ? 'selected' : '' }} value="expires_at">
                                Expiry Date</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                class="form-control float-right" placeholder="Search" aria-describedby="button-search">
                            <button type="submit" id="button-search" class="btn btn-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-dark text-center text-nowrap">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="60">No.</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Discount</th>
                            <th class="text-success">Min Cart</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Max Uses</th>
                            <th>Max User Uses</th>
                            <th width="100">Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($categories->isNotEmpty())
                            @foreach ($categories as $key => $category)
                                <tr>
                                    <td>{{ $key + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}
                                    </td>
                                    <td>{{ $category->code }}</td>
                                    <td style="max-width: 100px; overflow:hidden; cursor: pointer;" class="td">
                                        {!! !empty($category->name) ? $category->name : '<b>Not Set</b>' !!}</td>
                                    <td>
                                        @if ($category->type == 'percent')
                                            {{ $category->discount_amount }}%
                                        @elseif ($category->type == 'fixed')
                                            {{ $settings->currency_symbol . $category->discount_amount }}
                                        @else
                                            {{ $category->type }}
                                        @endif



                                    </td>
                                    <td>
                                        @if (!empty($category->min_amount))
                                            {{ $category->min_amount }}
                                        @else
                                            <b>Not Set</b>
                                        @endif

                                    </td>
                                    <td style="max-width: 100px; overflow:hidden; cursor: pointer;" class="td">
                                        {!! !empty($category->description) ? $category->description : '<b>Not Set</b>' !!}
                                    </td>
                                    <td>{!! !empty($category->starts_at)
                                        ? carbon\Carbon::parse($category->starts_at)->format('d M Y | h:i A')
                                        : '<b>Not Set</b>' !!}
                                    </td>
                                    <td>{!! !empty($category->expires_at)
                                        ? carbon\Carbon::parse($category->expires_at)->format('d M Y | h:i A')
                                        : '<b>Not Set</b>' !!}
                                    </td>
                                    <td>
                                        @if (!empty($category->max_uses))
                                            {{ $category->max_uses }}
                                        @else
                                            <b>Not Set</b>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($category->max_uses_user))
                                            {{ $category->max_uses_user }}
                                        @else
                                            <b>Not Set</b>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($category->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="loadModalContent('{{ route('discount.edit', $category->id) }}', 'Edit Coupon Code')">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete_category_btn"
                                            onclick=" deleteCategoryFunction({{ $category->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="12"> Records Not Found.</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end py-3">
                {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    <script>
        function deleteCategoryFunction(id) {
            if (confirm('Are you sure you want to delete discount coupon.')) {
                $.ajax({
                    type: "post",
                    url: "{{ route('discount.delete') }}",
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

                        window.location.href = "{{ route('discount.view') }}";

                    },
                });
            }
        }

        $('.td').click(function(e) {
            var imgSrc = $(this).text();
            $('#viewTd').text(imgSrc);
            $('#myModal').modal('show');

        });
    </script>
@endsection
