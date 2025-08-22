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
                    <div class="h4">Sub Categories</div>
                </div>
                <div class="col-6 text-end">
                    <button onclick="loadModalContent('{{ route('subCategories.create') }}', 'Create Sub Categories')"
                        class="btn btn-sm btn-primary">New Sub Categories</button>
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
                            <input type="text" name="keyword" value="{{ Request::get('keyword') }}" class="form-control"
                                placeholder="Search" aria-label="Search" aria-describedby="button-search">
                            <button class="btn btn-secondary" type="submit" id="button-search">
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
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Image</th>
                            <th>category</th>
                            <th width="100">Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($subCategories->isNotEmpty())
                            @foreach ($subCategories as $key => $category)
                                <tr>
                                    <td>{{ $key + 1 + ($subCategories->currentPage() - 1) * $subCategories->perPage() }}
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>

                                    <td style="cursor: pointer;" class="tdhtml"><img width="80" class="img-fluid"
                                            src="{{ asset($category->image) }}" alt="image">
                                    </td>

                                    <td style="cursor: pointer;" class="tdhtml">
                                        {{ $category->category->name }}
                                    </td>
                                    <td>
                                        @if ($category->status == 1)
                                            <span class="bg-success badge">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="loadModalContent('{{ route('subCategories.edit', $category->id) }}', 'Edit Sub Category')">
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
                                <td colspan="7">Records Not Found.</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center clearfix">
                {{ $subCategories->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>
    <!-- /.content -->


@endsection
@section('scripts')
    <script>
        function deleteCategoryFunction(id) {
            if (confirm(
                    'Are you sure you want to delete sub category.Remember All the related leaf category and Products will be deleted. '
                )) {
                $.ajax({
                    type: "post",
                    url: "{{ route('subCategories.delete') }}",
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
                        window.location.href = "{{ route('subCategories.view') }}";
                    },
                });
            }
        }
    </script>
@endsection
