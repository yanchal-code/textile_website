@extends('admin.includes.layout')

@section('content')
    <div class="container-fluid mt-3">

        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-3 mb-0">
                        <div class="h4 mb-3 text-dark fw-bold">Product Variations</div>
                    </div>

                    <div class="col-9 text-end">
                        {{ $product->name }}
                        <a href="{{ route('variations.create', $product->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Variation
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-dark">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>{{ $product->leafCategory->v1st }}</th>
                        <th>{{ $product->leafCategory->v2nd }}</th>
                        <th>Price</th>
                        <th>C Price</th>
                        <th>SKU</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="variations-table-body">
                    <!-- Dynamic Content -->
                    @foreach ($product->variations as $key => $variation)
                        <tr data-id="{{ $variation->id }}">
                            <td>{{ $key + 1 }}</td>
                            <td style="cursor: pointer;" class="tdhtml">
                                @if (!empty($variation->defaultImage))
                                    <img class="img-fluid" src="{{ asset($variation->defaultImage->image) }}"
                                        class="img-thumbnail">
                                @else
                                    <img class="img-fluid" src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                        class="img-thumbnail">
                                @endif

                            </td>
                            <td>{{ $variation->color ?? 'N/A' }}</td>
                            <td>{{ $variation->size ?? 'N/A' }}</td>
                            <td>{{ $variation->price ?? 'N/A' }}</td>
                            <td>{{ $variation->c_price ?? 'N/A' }}</td>
                            <td>{{ $variation->sku ?? 'N/A' }}</td>
                            <td>{{ $variation->quantity ?? 'N/A' }}</td>

                            <td>
                                @if ($variation->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('variations.edit', $variation->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-pen"></i>
                                </a>

                                <button class="btn btn-danger btn-sm delete-variation-btn" data-id="{{ $variation->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    @if ($product->variations->isEmpty())
                        <tr>
                            <td colspan="10">No variations found for this product.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-variation-btn', function() {
                const variationId = $(this).data('id');
                const row = $(this).closest('tr');

                if (confirm('Are you sure you want to delete this variation?')) {
                    $.ajax({
                        url: "{{ route('products.variations.delete') }}",
                        method: 'POST',
                        data: {
                            'id': variationId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status) {
                                showNotification(response.message, 'success', 'text');
                                window.location.reload();
                            } else {
                                showNotification(response.message, 'danger', 'text');
                            }
                        },
                        error: function(xhr, status, error) {

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
            });
        });
    </script>
@endsection
