@extends('admin.includes.layout')

@section('content')
    <script>
        $('#nav-iteml_orders').addClass('active');
    </script>

    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="h2">Orders</div>
                </div>
                <div class="col-sm-6 text-right">

                </div>
            </div>
        </div>

    </section>


    <div class="container-fluid">
        <b> (SEARCH BY user/order name , user/order email , orderID , user/order phone)</b>
        <div class="border-0">
            <div class="card-header row mb-2 row justify-content-start">
                <div class="card-title col-md-2 mb-md-0 mb-2 col-6">
                    <a href="{{ route('orders.view') }}" class="btn btn-default mr-2">Reset</a>
                </div>
                <div class="col-md-9 col-12">
                    <form method="get" class="row">
                        <div class="card-tools d-table col-md-3 col-8 mb-2">
                            <select name="status" id="status_inputs" class="form-control d-table filterInputs">
                                <option value="">Status Filter</option>
                                <option @if ($selectedStatus == 'pending') Selected @endif value="pending">Not Seen
                                </option>
                                <option @if ($selectedStatus == 'viewed') Selected @endif value="viewed">Viewed
                                </option>
                                <option @if ($selectedStatus == 'shipped') Selected @endif value="shipped">Shipped
                                </option>
                                <option @if ($selectedStatus == 'delivered') Selected @endif value="delivered">Delivered
                                </option>
                                <option @if ($selectedStatus == 'cancelled') Selected @endif value="cancelled">Cancelled
                                </option>
                            </select>
                        </div>
                        <div class="card-tools col-md-6 col-10">
                            <div class="input-group input-group">
                                <input id="keyword" value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                    class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="">
                <div class=" table-responsive p-0">
                    <table class="table table-hover table-bordered text-center text-nowrap">
                        <thead class="bg-dark">
                            <tr>
                                <th>No.</th>
                                <th>Orders #</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Method</th>

                                <th>Order Total</th>
                                <th>Currency</th>
                                <th>Transaction Amount</th>

                                <th>Transaction Email</th>
                                <th>Transaction ID</th>
                                <th>Payer ID</th>
                                <th>Status</th>

                                <th>Date Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->isNotEmpty())
                                @foreach ($orders as $key => $order)
                                    <tr>
                                        <td>{{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                        <td><a href="{{ route('order.detail', $order->id) }}">{{ $order->orderId }}</a>
                                        </td>
                                        <td>{{ $order->first_name }}</td>
                                        <td>
                                            <b> order : {{ $order->email }}</b>
                                        </td>
                                        <td>
                                            <b> order : {{ $order->mobile }}</b>
                                        </td>
                                        <td>
                                            {{ $order->payment_gateway }}
                                        </td>

                                        <td>
                                            {{ $order->grand_total }}
                                        </td>
                                        <td>{{ $order->payment_currency }}</td>
                                        <td>{{ number_format($order->payment_amount, 2) }}
                                        </td>

                                        <td>
                                            {{ $order->paypal_email }}
                                        </td>

                                        <td>
                                            {{ $order->phonepe_transaction_id }}
                                        </td>
                                        <td>
                                            {{ $order->paypal_payer_id }}
                                        </td>

                                        <td>
                                            @if ($order->status == 'pending')
                                                <span class="badge bg-danger">NotSeen</span>
                                            @elseif ($order->status == 'viewed')
                                                <span class="badge bg-info">SeenByAdmin</span>
                                            @elseif ($order->status == 'shipped')
                                                <span class="badge bg-warning">Shipped</span>
                                            @elseif ($order->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                <span class="badge bg-success">Delivered</span>
                                            @endif
                                        </td>



                                        <td> {{ !empty($order->created_at) ? carbon\Carbon::parse($order->created_at)->format('d M Y | h:i A') : '' }}
                                            |
                                            {{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="14" class="text-center">No Records Founds.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $orders->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
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

        function viewHtmlORtext(data, type) {
            $('#viewTd').html('');
            if (type == 'html') {
                $('#viewTd').html(data);

            } else {
                $('#viewTd').text(data);
            }

            $('#myModal').modal('show');
        }


        $('.tdHtml').click(function(e) {
            var tdHtml = $(this).html();
            viewHtmlORtext(tdHtml, 'html');
        });

        $('.filterInputs').change(function(e) {
            e.preventDefault();
            apply_filters();
        });

        function apply_filters() {

            var keyword = $('#keyword').val();
            if (keyword != '') {
                url = "?keyword=" + keyword;
            } else {
                url = "?blank";
            }
            if ($('#status_inputs').val() != '') {
                url += '&status=' + $('#status_inputs').val();
            }


            window.location.href = url;
        }

        $('.view_all_images').click(function(e) {
            e.preventDefault();
            $('#viewTd').html('');
            var productImagesArray = $(this).data('array');

            $.each(productImagesArray, function(index, item) {
                var imageURL = item.image;
                var imgField =
                    ` <div class="col-md-6">
                                        <div  class="card mb-3">
                                            <img class="img-fluid" src="{{ asset('${imageURL}') }}" alt="productImage">
                                        </div>
                                    </div>`;

                $('#viewTd').append(imgField);
            });

            $('#myModal').modal('show');

        });
    </script>
@endsection
