@extends('admin.includes.layout')

@section('content')

    <script>
        $('#nav-iteml_bidding').addClass('active');
    </script>

    <div class="container">
        <h3 class="mb-5">Add Bidding Product</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('bidding_products.store') }}" id="biding_list_product_form" class="needs-validation" novalidate method="POST">
            @csrf

<div class="form-group mb-3 px-2">

   <div class="row justify-content-center align-items-center border rounded p-3 bg-light">
    <!-- Product Image -->
    <div class="col-lg-8 col-11 text-center d-flex align-items-center justify-content-center">
        <img id="product_image" width="100" src="{{ asset('default.jpg') }}" alt="Product Image" class="rounded">

         <div class="col-lg-4 col-6 text-center">
        <p class="fw-bold mb-1" id="product_title">Product Title</p>
        <p class="small text-muted" id="product_description">Product Description</p>
    </div>
    </div>


    <!-- SKU Search & Selection -->
    <div class="col-lg-4 col-12 text-center mt-2">
            <input type="hidden" class="form-control" readonly required name="product_id" id="product_id" placeholder="Select Product ID">

            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#searchProductModal">
                <i class="fa fa-search"></i> Search by SKU
            </button>

    </div>
</div>


</div>



<!-- SKU Search Modal -->
<div class="modal fade" id="searchProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="searchProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Search Product by SKU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="sku_input" class="form-control" placeholder="Enter SKU" required>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="searchProductBtn">
                    <span class="spinner-border spinner-border-sm d-none" id="searchSpinner"></span>
                    Search
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productDetailsModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="product_details"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="selectProductBtn">
                    <span class="spinner-border spinner-border-sm d-none" id="selectSpinner"></span>
                    Select This Product
                </button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    $("#searchProductBtn").click(function () {
        let sku = $("#sku_input").val().trim();
        if (!sku) {
            showNotification("Please enter a SKU.","danger");
            return;
        }

        $("#searchSpinner").removeClass("d-none"); // Show spinner
        $("#searchProductBtn").prop("disabled", true); // Disable button

        $.ajax({
            url: "{{route('product.search.sku')}}",
            type: "GET",
            data: { sku: sku },
            success: function (response) {
                $("#searchSpinner").addClass("d-none"); // Hide spinner
                $("#searchProductBtn").prop("disabled", false); // Enable button

                if (response.is_variation) {
                    showNotification("This SKU belongs to a variation. Only main SKU allowed.","danger");
                    return;
                }

                if (response.error) {
                    showNotification(response.error,"danger");
                    return;
                }

                let product = response.product;
                let productDetailsHtml = `
                    <img src="${product.image}" alt="${product.name}" class="img-fluid mb-2">
                    <h5>${product.name}</h5>

                    <p><strong>Title:</strong> ${product.title}</p>
                    <p><strong>Price:</strong> ${product.price} USD</p>
                    <p><strong>Description:</strong> ${product.description}</p>
                    <p><strong>Stock:</strong> ${product.quantity}</p>
                `;

                $("#product_details").html(productDetailsHtml);

                $("#selectProductBtn").attr("data-product-id", product.id);
                $("#selectProductBtn").attr("data-product-title", product.title);
                $("#selectProductBtn").attr("data-product-description", product.description);
                $("#selectProductBtn").attr("data-product-image", product.image);

                $("#searchProductModal").modal("hide");
                $("#productDetailsModal").modal("show");
            },
            error: function () {
                $("#searchSpinner").addClass("d-none"); // Hide spinner
                $("#searchProductBtn").prop("disabled", false); // Enable button
                showNotification("Something went wrong. Please try again.","danger");
            }
        });
    });

    $("#selectProductBtn").click(function () {
        let productId = $(this).attr("data-product-id");
        if (!productId) {
            showNotification("No product selected.","danger");
            return;
        }

         let productTitle = $(this).attr("data-product-title");
         let productDescription = $(this).attr("data-product-description");
         let productImage = $(this).attr("data-product-image");

        $("#selectSpinner").removeClass("d-none"); // Show spinner
        $("#selectProductBtn").prop("disabled", true); // Disable button

        setTimeout(() => {
            $("#product_id").val(productId);
            $('#product_image').attr('src',productImage);
            $('#product_title').text(productTitle);
            $('#product_description').text(productDescription);
            $("#productDetailsModal").modal("hide");
            showNotification("Product selected successfully.","success");
            $("#selectSpinner").addClass("d-none"); // Hide spinner
            $("#selectProductBtn").prop("disabled", false); // Enable button
        }, 1000);
    });
});

</script>


            <div class="form-group mb-3">
                <label>Starting Bid</label>
                <input type="text" name="starting_bid" class="form-control" required>
            </div>

           <div class="row">

                <div class="form-group mb-3 col-md-6">
                <label>Start Time</label>
                <input type="datetime-local" name="bid_start_time" class="form-control" required>
            </div>

            <div class="form-group mb-3 col-md-6">
                <label>End Time</label>
                <input type="datetime-local" name="bid_end_time" class="form-control" required>
            </div>

           </div>


            <button type="submit" class="btn btn-primary d-block m-auto mt-3">Add Bidding Product</button>
        </form>


<script>
$(document).ready(function () {
    $("#biding_list_product_form").submit(function (event) {
        let form = $(this);

        if (!form[0].checkValidity()) {

              let product_id = $("#product_id").val().trim();
        if (!product_id) {
            showNotification("Please Select A Product.","danger");
        }

            event.preventDefault();
            event.stopPropagation();
        }

        form.addClass('was-validated'); // Add Bootstrap validation styles
    });
});
</script>


    </div>

@endsection
