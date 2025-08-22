<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Email</title>
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
</head>

<body>
    <div class="container-fluid">
        @if ($mailData['userType'] == 'admin')
            <b>MrAdmin. You requested this invoice ({{ $mailData['order']->orderId }})</b>
        @endif
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            @php
                                $order = $mailData['order'];
                                $orderItems = $order->items;
                            @endphp
                            <div class="col-sm-4 invoice-col">
                                <h1 class="h5 mb-3">Shipping Address</h1>
                                <address>
                                    <strong>{{ $order->first_name }}</strong>
                                    <strong>{{ $order->last_name }}</strong><br>
                                    {{ $order->address }}<br>
                                    {{ $order->city }}, {{ $order->zip }}<br>
                                    {{ $order->state }} <br>
                                    {{ $order->country_id }},
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
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Name</th>
                                    <th width="100">Price</th>
                                    <th width="100">Qty</th>
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $item)
                                    <tr>
                                        <td>

                                            @php
                                                $product = getProduct($item->sku);
                                                $image =
                                                    $product->defaultImage->image ?? $product->images()->first()->image;
                                            @endphp

                                            <a href="{{ route('front.product', $item->sku ?? '') }}">
                                                @if (!empty($image))
                                                    <img class="img-fluid" style="max-height: 80px;"
                                                        src="{{ asset($image) }}" alt="img">
                                                @else
                                                    <img class="img-fluid" style="max-height: 80px;"
                                                        src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                        alt="img">
                                                @endif
                                            </a>

                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->price * $item->qty }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <table class="d-flex justify-content-end pr-2">
                            <tbody>
                                <tr>
                                    <th class="text-right">Subtotal : </th>
                                    <td> {{ number_format($order->subtotal) }}</td>
                                </tr>
                                @if ($order->discount > 0)
                                    <tr>
                                        <th class="text-right">Discount : </th>
                                        <td>{{ $order->discount }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="text-right">Shipping : </th>
                                    <td>{{ $order->shipping }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Grand Total : </th>
                                    <td class="text-success font-weight-bold">
                                        {{ number_format($order->grand_total) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admin-assets/js/demo.js') }}"></script>

</html>
