@extends('admin.includes.layout')
@section('headTag')
@endsection
@section('content')
    <script>
        $('#nav-item_static_pages').addClass('active');
    </script>
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="h4">Static Pages</div>
                </div>
                <div class="col-6 text-end">
                    <button onclick="loadModalContent('{{ route('pages.create') }}', 'Create Static Page')"
                        class="btn btn-sm btn-primary">New Static Page</button>
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
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Content</th>
                            <th width="100">Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($pages->isNotEmpty())
                            @foreach ($pages as $key => $page)
                                <tr>
                                    <td>{{ $key + 1 + ($pages->currentPage() - 1) * $pages->perPage() }}
                                    </td>
                                    <td>{{ $page->name }}</td>
                                    <td>{{ $page->slug }}</td>
                                    <td style="cursor: pointer;">
                                        <div class="tdhtml"
                                            style="
                                           max-height: 50px;
                                           max-width:100px;
                                           overflow: hidden;
                                           white-space: nowrap;
                                           text-overflow: ellipsis;">
                                            {!! $page->content !!}

                                        </div>
                                    </td>
                                    <td>
                                        @if ($page->status == 1)
                                            <span class="bg-success badge">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="loadModalContent('{{ route('pages.edit', $page->id) }}', 'Edit Static Page')">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        @if ($page->id > 6)
                                            <button class="btn btn-danger btn-sm delete_category_btn"
                                                onclick=" deleteCategoryFunction({{ $page->id }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
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
                {{ $pages->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>
    <!-- /.content -->


@endsection
@section('scripts')
    <script>
        function deleteCategoryFunction(id) {
            if (confirm('Are you sure you want to delete this static page.')) {
                $.ajax({
                    type: "post",
                    url: "{{ route('pages.delete') }}",
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
                        window.location.href = "{{ route('pages.view') }}";
                    },
                });
            }
        }
    </script>
@endsection
