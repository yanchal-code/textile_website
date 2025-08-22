@extends('admin.includes.layout')

@section('content')
    <script>
        $('#nav-item_inventory').addClass('active');
    </script>
    <section class="content-header mb-5">
        <div class="container-fluid my-2">
            <div class="row align-items-center">
                <div class="col-3">
                    <div class="h4">Products</div>
                </div>
                <div class="col-9 text-end">


                    <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">New Product</a>

                    <button class="btn btn-success btn-sm my-lg-0 my-2" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-upload"></i> Import Data from Excel
                    </button>

                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#downloadModal">
                        <i class="fas fa-download"></i> Download Example Excel
                    </button>



                </div>
            </div>
        </div>
        <!-- /.container-fluid -->


        <!-- Download Example Excel Modal -->
        <div class="modal fade" id="downloadModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="downloadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-white" id="downloadModalLabel">Download Example Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('example.export') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="leaf_category" class="form-label fw-bold">Select Leaf Category:</label>
                                <select name="export_leaf_category" id="leaf_category" class="form-select">
                                    <option value="">-- Select --</option>
                                    @foreach ($leafCategories as $leafCategory)
                                        <option value="{{ $leafCategory->id }}">{{ $leafCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="downloadExample">
                                <i class="fas fa-download"></i> Download Example Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Data from Excel Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white" id="importModalLabel">Import Data from Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="uploadForm" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="leaf_category_file" class="form-label fw-bold">Select Leaf Category:</label>
                                <select name="leafCategoryId" id="leaf_category_file" class="form-select">
                                    <option value="">-- Select --</option>
                                    @foreach ($leafCategories as $leafCategory)
                                        <option value="{{ $leafCategory->id }}">{{ $leafCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="excelFileInput" class="form-label fw-bold">Upload Excel File:</label>
                                <div class="input-group">
                                    <input type="file" class="form-control p-2" id="excelFileInput" name="file"
                                        accept=".xls,.xlsx,.xlsm,.xlsb,.csv,.tsv,.cltv,.xltm">
                                    <button type="submit" class="btn btn-success" id="submitImportBtn">
                                        <i class="fas fa-upload"></i> Import
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <form>

                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="skuFilter"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    SKU
                                </a>
                                <div class="dropdown-menu p-3" aria-labelledby="skuFilter">
                                    <textarea name="sku" class="form-control" rows="3" placeholder="Enter SKUs separated by commas">{{ request('sku') }}</textarea>
                                    <button class="btn btn-primary btn-sm mt-2 w-100 apply-filter">Apply</button>
                                </div>
                            </div>
                        </th>

                        <th>
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="nameFilter"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Name
                                </a>
                                <div class="dropdown-menu p-3" aria-labelledby="nameFilter">
                                    <textarea name="name" class="form-control" rows="3" placeholder="Enter Product Names separated by commas">{{ request('name') }}</textarea>
                                    <button class="btn btn-primary btn-sm mt-2 w-100 apply-filter">Apply</button>
                                </div>
                            </div>
                        </th>

                        <th>
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="designGroupFilter"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Design Group
                                </a>
                                <div class="dropdown-menu p-3" aria-labelledby="designGroupFilter">
                                    <textarea name="designgroup" class="form-control" rows="3"
                                        placeholder="Enter design Group separated by commas">{{ request('designgroup') }}</textarea>
                                    <button class="btn btn-primary btn-sm mt-2 w-100 apply-filter">Apply</button>
                                </div>
                            </div>
                        </th>



                        <th>
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="categoryFilter"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Category
                                </a>
                                <div class="dropdown-menu p-3" aria-labelledby="categoryFilter">
                                    @foreach ($categories as $category)
                                        <div class="form-check">
                                            <input type="checkbox" name="category[]" class="form-check-input"
                                                value="{{ $category->id }}"
                                                {{ in_array($category->id, request('category', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $category->name }}</label>
                                        </div>
                                    @endforeach
                                    <button class="btn btn-primary btn-sm mt-2 w-100 apply-filter">Apply</button>
                                </div>
                            </div>
                        </th>

                        <th>
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="subCategoryFilter"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Subcategory
                                </a>
                                <div class="dropdown-menu p-3" aria-labelledby="subCategoryFilter">
                                    @foreach ($subCategories as $subCategory)
                                        <div class="form-check">
                                            <input type="checkbox" name="sub_category[]" class="form-check-input"
                                                value="{{ $subCategory->id }}"
                                                {{ in_array($subCategory->id, request('sub_category', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $subCategory->name }}</label>
                                        </div>
                                    @endforeach
                                    <button class="btn btn-primary btn-sm mt-2 w-100 apply-filter">Apply</button>
                                </div>
                            </div>
                        </th>

                        <th>
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="leafCategoryFilter"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Leaf Category
                                </a>
                                <div class="dropdown-menu p-3" aria-labelledby="leafCategoryFilter">
                                    @foreach ($leafCategories as $leafCategory)
                                        <div class="form-check">
                                            <input type="checkbox" name="leaf_category[]" class="form-check-input"
                                                value="{{ $leafCategory->id }}"
                                                {{ in_array($leafCategory->id, request('leaf_category', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $leafCategory->name }}</label>
                                        </div>
                                    @endforeach
                                    <button class="btn btn-primary btn-sm mt-2 w-100 apply-filter">Apply</button>
                                </div>
                            </div>
                        </th>

                        <th>
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="brandFilter"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Brand
                                </a>
                                <div class="dropdown-menu p-3" aria-labelledby="brandFilter">
                                    @foreach ($brands as $brand)
                                        <div class="form-check">
                                            <input type="checkbox" name="brand[]" class="form-check-input"
                                                value="{{ $brand->id }}"
                                                {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $brand->name }}</label>
                                        </div>
                                    @endforeach
                                    <button class="btn btn-primary btn-sm mt-2 w-100 apply-filter">Apply</button>
                                </div>
                            </div>
                        </th>

                        <th class="">Price</th>
                        <th class="">Compare</th>
                        <th class="">Variations</th>
                        <th class="">status</th>
                        <th class="">Featured</th>
                        <th class="">Actions</th>

                    </tr>
                </form>
            </thead>
            <tbody>
                @if ($products->isNotEmpty())
                    @foreach ($products as $key => $product)
                        <tr>
                            <td>{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}
                            </td>
                            <td style="cursor: pointer;" class="tdhtml">
                                @if (!empty($product->defaultImage))
                                    <img class="img-fluid" src="{{ asset($product->defaultImage->image) }}"
                                        class="img-thumbnail">
                                @else
                                    <img class="img-fluid" src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                        class="img-thumbnail">
                                @endif

                            </td>

                            <td>{{ $product->sku }}</td>

                            <td>{{ $product->name }}</td>

                            <td>{{ $product->design_number ?? 'N/A' }}</td>


                            <td>{{ $product->category->name ?? 'N/A' }}</td>

                            <td>{{ $product->subCategory->name ?? 'N/A' }}</td>

                            <td>{{ $product->leafCategory->name ?? 'N/A' }}</td>

                            <td>{{ $product->brand->name ?? 'N/A' }}</td>

                            <td>{{ config('settings.currency_symbol') }} {{ $product->price }}</td>

                            <td>{{ config('settings.currency_symbol') }} {{ $product->compare_price }}</td>

                            <td>{{ $product->variations->count() }}</td>

                            <td>
                                @if ($product->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>

                            <td>
                                @if ($product->is_featured == 1)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-warning">No</span>
                                @endif
                            </td>

                            <td>
                                <a class="btn btn-sm btn-success m-1"
                                    href="{{ route('products.variations', $product->id) }}">Variations</a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary"><i
                                        class="fa fa-pen"></i></a>
                                <button class="btn btn-sm btn-danger"
                                    onclick=" deleteCategoryFunction({{ $product->id }})"><i
                                        class="fa fa-trash"></i></button>
                            </td>

                        </tr>
                    @endforeach
                @else
                    <tr id="no-data-row">
                        <td class="text-center" colspan="1">No records found</td>
                    </tr>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const headerCells = document.querySelectorAll('table thead tr th');
                            const noDataCell = document.querySelector('#no-data-row td');
                            if (noDataCell && headerCells.length) {
                                noDataCell.setAttribute('colspan', headerCells.length);
                            }
                        });
                    </script>
                @endif
            </tbody>
        </table>
    </div>
    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

    <script>
        document.querySelectorAll('.apply-filter').forEach((button) => {
            button.addEventListener('click', () => {
                button.closest('form').submit();
            });
        });



        function deleteCategoryFunction(id) {
            if (confirm('Are you sure you want to delete product.')) {
                $.ajax({
                    type: "post",
                    url: "{{ route('products.delete') }}",
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
    </script>


    <script>
        $(document).ready(function() {
            $('#uploadForm').on('submit', function(event) {
                event.preventDefault();
                var btn = $('#submitImportBtn');
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: " {{ route('products.import') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#importLoading').html(
                            `  <span id="slugLoader" class="p-2"><span class="loader"></span>Loading...</span>`
                        );
                    },
                    success: function(response) {
                        if (response.status === false) {
                            var errorsHtml = '';
                            var errors = response.errors;
                            var count = 1;
                            for (var key in errors) {

                                if (errors.hasOwnProperty(key)) {
                                    errorsHtml += '<p>' + count + '. ' + errors[key][0] +
                                        '</p>';
                                }
                                count = count + 1;
                            }

                            showNotification(errorsHtml, 'danger', 'html');
                        } else if (response.status === true) {
                            window.location.href = "{{ route('products.index') }}"
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
                    },

                    complete: function() {
                        $('#importLoading').html(`<button type="submit" class="btn btn-primary"
                                        id="submitImportBtn">Import</button>`);
                    }
                });
            });
        });
    </script>
@endsection
