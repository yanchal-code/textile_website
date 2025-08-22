@extends('admin.includes.layout')
@section('headTag')
@endsection
@section('content')
    <script>
        $('#nav-item_stone_stock').addClass('active');
    </script>
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="h4">Product Brand</div>
                </div>
                <div class="col-6 text-end">
                    <button onclick="loadModalContent('{{ route('brands.create') }}', 'Create Product Brand')"
                        class="btn btn-sm btn-primary">New Product Brand</button>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section>
        <div class="container-fluid mt-4">
            <form method="get">
                <div class="row">
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


            <div class="table-responsive p-0">
                <table class="table table-bordered text-nowrap text-dark text-center">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="60">No.</th>
                            <th>Group Name</th>
                            <th>Slug</th>

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
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>

                                    <td>
                                        @if ($category->status == 1)
                                            <span class="bg-success badge">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="loadModalContent('{{ route('brands.edit', $category->id) }}', 'Edit Product Brand')">
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
                            <tr>
                                <td colspan="6">Records Not Found.</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>



            <div class="d-flex justify-content-center clearfix">
                {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>
    <!-- /.content -->


@endsection
@section('scripts')
    <script>
        function deleteCategoryFunction(id) {
            if (confirm('Are you sure you want to delete this product Brand.')) {
                $.ajax({
                    type: "post",
                    url: "{{ route('brands.delete') }}",
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
                        window.location.href = "{{ route('brands.view') }}";
                    },
                });
            }
        }
    </script>
@endsection
