@extends('admin.includes.layout')
@section('title')
@endsection
@section('headTag')
    <link rel="stylesheet" href="{{ asset('admin-assets/css/datetimepicker.css') }}">
@endsection

@section('content')
    <script>
        $('#nav-iteml_orders').addClass('active');
    </script>
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-5 mt-3">
                <div class="col-sm-6">
                    <div class="h3">Order : {{ $order->orderId }}</div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('orders.view') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                <h1 class="h5 mb-3">Shipping Address</h1>
                                <address>
                                    <strong>{{ $order->first_name }}</strong>
                                    <strong>{{ $order->last_name }}</strong><br>
                                    {{ $order->address }}<br>
                                    {{ $order->city }}, {{ $order->zip }}<br>
                                    {{ $order->state }} <br>
                                    {{ $order->country_id }} <br>

                                    Phone: {{ $order->mobile }}<br>
                                    Email: {{ $order->email }}
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice - {{ $order->orderId }}</b><br>
                                <br>
                                <b>Order ID : </b> {{ $order->orderId }}<br>
                                <b>Total : </b>{{ number_format($order->grand_total, 2) }}<br>
                                <b>Status : </b>
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
                                <br>
                                <b>Ordered Date : </b>
                                {{ !empty($order->created_at) ? carbon\Carbon::parse($order->created_at)->format('d M Y | h:i A') : '' }}
                                <br>

                                @if ($order->shipped_date != '')
                                    <b>Shipped Date : </b>
                                    {{ !empty($order->shipped_date) ? carbon\Carbon::parse($order->shipped_date)->format('d M Y') : '' }}
                                    <br>
                                @endif
                            </div>
                            @if ($order->order_note != null)
                                <div class="col-sm-4 invoice-col bg-white">
                                    Order Note : {{ $order->order_note }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">
                        <table class="table table-striped">
                            <thead class="bg-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th width="100">Price</th>
                                    <th width="100">Qty</th>
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $item)
                                    <tr>
                                        <td>

                                            <a href="{{ route('front.product', $item ? $item->sku : 'deleted') }}">
                                                @php
                                                    $product = getProduct($item->sku);
                                                @endphp
                                                @if (!empty($product))
                                                    <img class="img-fluid" style="max-height: 80px;"
                                                        src="{{ asset($product->defaultImage->image ?? $product->images->first()->image) }}"
                                                        alt="img">
                                                @else
                                                    <img class="img-fluid" style="max-height: 80px;"
                                                        src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                        alt="img">
                                                @endif
                                            </a>

                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->sku }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->price * $item->qty }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        <table class="pr-2 table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-left text-dark">Payment</th>
                                    <td>{{ $order->payment_type }}</td>
                                    <th class="text-right text-dark">Subtotal : </th>
                                    <td>{{ number_format($order->subtotal, 2) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-dark">Transaction ID</th>
                                    <td>{{ $order->phonepe_transaction_id }}</td>
                                    <th class="text-right text-dark">Discount : </th>
                                    <td>{{ $order->discount }}</td>
                                </tr>

                                <tr>
                                    <th class="text-left text-dark">Payment Status</th>
                                    <td>{{ $order->payment_status }}</td>

                                    <th class="text-right text-dark">Shipping : </th>
                                    <td>{{ $order->shipping }}</td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td></td>
                                    <th class="text-right text-dark">Grand Total : </th>
                                    <td class="text-success font-weight-bold">
                                        {{ number_format($order->grand_total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Order Status</h2>
                        <form id="chageOrderStatus">
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option {{ $order->status == 'pending' ? 'selected' : '' }} value="pending">Pending
                                    </option>
                                    <option {{ $order->status == 'viewed' ? 'selected' : '' }} value="viewed">Seen By
                                        Admin
                                    </option>
                                    <option {{ $order->status == 'shipped' ? 'selected' : '' }} value="shipped">Order
                                        Shipped</option>
                                    <option {{ $order->status == 'delivered' ? 'selected' : '' }} value="delivered">
                                        Delivered</option>
                                    <option {{ $order->status == 'cancelled' ? 'selected' : '' }} value="cancelled">
                                        Cancelled</option>
                                </select>
                            </div>
                            <div id="shipped_dateDiv" class="mb-3 {{ !empty($order->shipped_date) ? '' : 'd-none' }}">
                                <label for="shipped_date">Shipped Date</label>
                                <input value="{{ !empty($order->shipped_date) ? $order->shipped_date : '' }}"
                                    type="datetime-local" class="form-control" name="shipped_date" class="form-control"
                                    name="shipped_date" id="shipped_date">


                                @php
                                    $shippmentDetail = !empty($order->shippment_detail) ? $order->shippment_detail : '';
                                    $shippmentParts = explode('|', $shippmentDetail);
                                    $shippmentCompany = $shippmentParts[0] ?? '';
                                    $shippmentTrackingId = $shippmentParts[1] ?? '';
                                @endphp

                                <label for="shippment_detail">Shippment company</label>
                                <input value="{{ $shippmentCompany }}" autocomplete="off" type="text"
                                    class="form-control" name="shippment_detail" id="shippment_detail">
                                <br>
                                <label for="shippment_detail2">Shippment tracking-ID</label>
                                <input value="{{ $shippmentTrackingId }}" autocomplete="off" type="text"
                                    class="form-control" name="shippment_detail2" id="shippment_detail2">


                            </div>
                            <div class="mb-3">
                                <span id="categoryCreatFormSpinner">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </span>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">

                    <div class="card-body">
                        <form method="post" id="sendInvoice">
                            <h2 class="h4 mb-3">Send Inovice Email</h2>
                            <div class="mb-3">
                                <select name="userType" id="userType" class="form-control">
                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <span id="sendInvoiceCreatFormSpinner">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </span>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('admin-assets/js/datetimepicker.js') }}"></script>

    <script>
        $('#status').change(function(e) {

            if ($('#status').val() == 'shipped') {
                $('#shipped_dateDiv').removeClass('d-none');
            } else {
                $('#shipped_dateDiv').addClass('d-none');
            }
        });

        $('#chageOrderStatus').submit(function(e) {
            event.preventDefault();
            event.stopPropagation();
            var formData = new FormData($(this)[0]);
            if (confirm('Are you sure you want to change order status')) {
                $.ajax({
                    type: "post",
                    url: "{{ route('order.update', $order->id) }}",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('#categoryCreatFormSpinner').html(
                            '<span id="slugLoader"><span class="loader"></span> Loading...</span>'
                        );
                    },
                    success: function(response) {

                        $('#categoryCreatFormSpinner').html(
                            '<button type="submit" class="btn btn-primary">Update</button>')
                        if (response.status === false) {
                            var errorsHtml = '';
                            var errors = response.errors;
                            var count = 1;
                            for (var key in errors) {

                                if (errors.hasOwnProperty(key)) {
                                    errorsHtml += '<p>' + count + '. ' + errors[key][0] + '</p>';
                                }
                                count = count + 1;
                            }


                            showNotification(errorsHtml, 'danger', 'html');
                        } else if (response.status === true) {
                            window.location.reload()
                        } else {

                            showNotification(response.message, 'danger', 'text');
                        }

                    },
                    error: function(xhr, status, error) {
                        $('#categoryCreatFormSpinner').html(
                            '  <button type="submit" class="btn btn-primary">Update</button>');

                        var errorMessage = "";
                        try {
                            var responseJson = JSON.parse(xhr.responseText);
                            errorMessage = responseJson.message;
                        } catch (e) {
                            errorMessage = "An error occurred: " + xhr.status + " " + xhr.statusText;
                        }

                        showNotification(errorMessage, 'danger', 'text');
                    }
                });
            }
        });


        $('#sendInvoice').submit(function(e) {
            event.preventDefault();
            event.stopPropagation();
            var formData = new FormData($(this)[0]);
            if (confirm('Are you sure you want to send email')) {
                $.ajax({
                    type: "post",
                    url: "{{ route('order.sendInvoice', $order->id) }}",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('#sendInvoiceCreatFormSpinner').html(
                            '<span id="slugLoader"><span class="loader"></span> Loading...</span>'
                        );
                    },
                    success: function(response) {

                        $('#sendInvoiceCreatFormSpinner').html(
                            '<button type="submit" class="btn btn-primary">Send</button>')
                        if (response.status === false) {
                            var errorsHtml = '';
                            var errors = response.errors;
                            var count = 1;
                            for (var key in errors) {

                                if (errors.hasOwnProperty(key)) {
                                    errorsHtml += '<p>' + count + '. ' + errors[key][0] + '</p>';
                                }
                                count = count + 1;
                            }


                            showNotification(errorsHtml, 'danger', 'html');
                        } else if (response.status === true) {
                            window.location.reload()
                        } else {

                            showNotification(response.message, 'danger', 'text');
                        }

                    },
                    error: function(xhr, status, error) {
                        $('#sendInvoiceCreatFormSpinner').html(
                            '<button type="submit" class="btn btn-primary">Send</button>');

                        var errorMessage = "";
                        try {
                            var responseJson = JSON.parse(xhr.responseText);
                            errorMessage = responseJson.message;
                        } catch (e) {
                            errorMessage = "An error occurred: " + xhr.status + " " + xhr.statusText;
                        }

                        showNotification(errorMessage, 'danger', 'text');
                    }
                });
            }
        });
    </script>
@endsection
