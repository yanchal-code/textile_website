@extends('admin.includes.layout')

@section('content')
    <div class="container">
        <script>
            $('#nav-item_blogs').addClass('active');
        </script>
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="h4">Blogs</div>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('blogs.create') }}" class="btn btn-sm btn-primary">New Blog</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">#</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blogs as $key => $blog)
                            <tr>
                                 <td>{{ $key + 1 + ($blogs->currentPage() - 1) * $blogs->perPage() }}
                            </td>
                                <td>{{ $blog->title }}</td>
                                <td style="cursor: pointer;" class="tdhtml">
                                    @if ($blog->image)
                                        <img src="{{ asset($blog->image) }}"
                                            alt="{{ $blog->alt_image_text ?? $blog->title }}" width="80" height="60"
                                            class="rounded">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($blog->status == 1)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $blog->created_at->format('d M, Y') }}</td>

                                <td>
                                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm delete_category_btn"
                                        onclick=" deleteCategoryFunction('{{ $blog->id }}')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No blogs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                {{ $blogs->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function deleteCategoryFunction(id) {
            if (confirm(
                    'Are you sure you want to delete this blog.'
                )) {
                $.ajax({
                    type: "post",
                    url: "{{ route('blogs.destroy') }}",
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
    </script>
@endsection
