@extends('admin.includes.layout')
@section('headTag')
@endsection
@section('content')
    <script>
        $('#nav-item_inventory').addClass('active');
    </script>
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="h4">Leaf Categories</div>
                </div>
                <div class="col-6 text-end">
                    <button onclick="loadModalContent('{{ route('leafCategories.create') }}', 'Create Leaf Categories')"
                        class="btn btn-sm btn-primary">New Leaf Categories</button>

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
                            <th>Sub category</th>
                            <th width="100">Status</th>
                            <th>Created At</th>
                            <th width="100">Fields</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    @if ($leafCategories->isNotEmpty())
                        <tbody>
                            @foreach ($leafCategories as $key => $category)
                                <tr>
                                    <td>{{ $key + 1 + ($leafCategories->currentPage() - 1) * $leafCategories->perPage() }}
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>

                                    <td style="cursor: pointer;" class="tdhtml"><img width="80" class="img-fluid"
                                            src="{{ asset($category->image) }}" alt="image">
                                    </td>

                                    <td style="cursor: pointer;" class="tdhtml">
                                        {{ $category->subCategory->name }}
                                    </td>
                                    <td>
                                        @if ($category->status == 1)
                                            <span class="bg-success badge">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $category->created_at->format('d M Y') }}
                                    </td>

                                    <td>
                                        <button class="btn btn-primary btn-sm view-specs btn-sm"
                                            data-id="{{ $category->id }}">
                                            <i class="fas fa-eye"></i> Specs
                                        </button>
                                    </td>

                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="loadModalContent('{{ route('leafCategories.edit', $category->id) }}', 'Edit Leaf Category')">
                                            <i class="fa fa-pen"></i>
                                        </button>

                                        {{-- <a class="btn btn-primary btn-sm"
                                            href="{{ route('leafCategories.edit', $category->id) }}">
                                            <i class="fa fa-pen"></i>
                                        </a> --}}

                                        <button class="btn btn-danger btn-sm delete_category_btn"
                                            onclick=" deleteCategoryFunction({{ $category->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
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
                </table>
            </div>

            <div class="d-flex justify-content-center clearfix">
                {{ $leafCategories->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="specFieldsModal" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="specFieldsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="specFieldsModalLabel">Specification Fields</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul id="specFieldsList" class="list-group">
                            <!-- Specs Fields will be added here dynamically -->
                        </ul>
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
            if (confirm(
                    'Are you sure you want to delete this leaf category. Remember All the related Products will be deleted.'
                )) {
                $.ajax({
                    type: "post",
                    url: "{{ route('leafCategories.delete') }}",
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
                        window.location.href = "{{ route('leafCategories.view') }}";
                    },
                });
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $(".view-specs").click(function() {
                var categoryId = $(this).data("id");

                // Clear previous specs
                $("#specFieldsList").html("<li class='list-group-item text-muted'>Loading...</li>");

                // Fetch specs fields via AJAX
                $.ajax({
                    url: "{{ route('leafCategories.specs') }}",
                    type: "POST",
                    data: {
                        id: categoryId,
                    },
                    success: function(response) {
                        $("#specFieldsList").empty(); // Clear loading text

                        if (response.specFields.length === 0) {
                            $("#specFieldsList").append(
                                `<li class='list-group-item text-danger'>No specifications available.</li>
                                <li class='list-group-item'>Variable 1st = ${response.v1st}</li>
                                <li class='list-group-item'>Variable 2nd = ${response.v2nd}</li>`
                            );
                        } else {
                            $.each(response.specFields, function(index, field) {
                                let fieldType =
                                    `<span class="badge bg-secondary">${field.type}</span>`;
                                let options = field.type === 'select' ?
                                    `Options: <strong>${field.options}</strong>` : '';
                                $("#specFieldsList").append(`<li class='list-group-item'>
                                <strong>${field.name}</strong> ${fieldType} <br> ${options}
                            </li>`);

                            });

                            $("#specFieldsList").append(
                                `<li class='list-group-item'>Variable 1st = ${response.v1st}</li>
                                <li class='list-group-item'>Variable 2nd = ${response.v2nd}</li>`
                            );
                        }

                        // Show the modal
                        $("#specFieldsModal").modal("show");
                    },
                    error: function() {
                        $("#specFieldsList").html(
                            "<li class='list-group-item text-danger'>Error fetching data.</li>"
                        );
                    }
                });
            });
        });
    </script>
@endsection
