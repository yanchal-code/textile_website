@extends('admin.includes.layout')

@section('content')
    <script>
        $('#nav-item_carousel').addClass('active');
    </script>

    <section class="content-header mt-3 mb-4">
        <div class="container-fluid my-2">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="h4">Carousel Items</div>
                </div>
                <div class="col-6 text-end">
                    <button onclick="loadModalContent('{{ route('carousel.create') }}', 'Create Carousel Item')"
                        class="btn btn-sm btn-primary">New Item</button>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <div class="container">

        <div class="table-responsive">

            <table class="table table-bordered text-nowrap text-dark text-center">
                <thead class="bg-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Content</th>
                        <th>Button</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($carousels->isNotEmpty())
                        @foreach ($carousels as $key => $carousel)
                            <tr>
                                <td>{{ $key + 1 + ($carousels->currentPage() - 1) * $carousels->perPage() }}
                                </td>
                                <td>{{ $carousel->title }}</td>
                                <td style="cursor: pointer;" class="tdhtml">
                                    <img src="{{ asset($carousel->image_path) }}" class="img-fluid"
                                        alt="{{ $carousel->title }}">
                                </td>
                                <td style="cursor: pointer; max-width:100px; overflow:hidden;" class="tdhtml tdwrap">{{ $carousel->description }}</td>
                                <td>
                                    <a href="{{ $carousel->link }}" target="_blank"
                                        class="btn btn-dark btn-sm">{{ $carousel->btn_text ?? 'Shop Now' }}</a>
                                </td>
                                <td>
                                    @if ($carousel->status == 1)
                                        <span class="badge bg-success">active</span>
                                    @else
                                        <span class="badge bg-danger">inactive</span>
                                    @endif
                                </td>
                                <td>

                                    <form action="{{ route('carousel.destroy', $carousel) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="button"
                                            onclick="loadModalContent('{{ route('carousel.edit', $carousel) }}', 'Edit Carousel Item')"
                                            class="btn btn-primary btn-sm m-1"><i class="fa fa-pen"></i></a>

                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm m-1"><i
                                                    class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">

                                No Data Found

                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

        </div>
        {{ $carousels->links('pagination::bootstrap-5') }}
    </div>
@endsection
