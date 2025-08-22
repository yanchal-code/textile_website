@extends('admin.includes.layout')

@section('content')
    <script>
        $('#nav-iteml_bidding').addClass('active');
    </script>
    <section class="content-header">
        <div class="container-fluid my-2 mb-3">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="h4">Bidding Products</div>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('bidding_products.create') }}" class="btn btn-sm btn-primary">Add New Bidding
                        Product</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-dark">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Starting Bid</th>

                        <th>Current Bid</th>

                        <th>Highest Bid</th>
                        <th>Highest Bidder</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th colspan="2">Status</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($biddingProducts->isNotEmpty())
                        @foreach ($biddingProducts as $key => $biddingProduct)
                            <tr>
                                <td>{{ $key + 1 + ($biddingProducts->currentPage() - 1) * $biddingProducts->perPage() }}
                                @if( $biddingProduct->product)
                                <td>{{ $biddingProduct->product->name }}</td>
                                @else
                                        <td>Product Deleted</td>
                                @endif
                                <td>{{ $biddingProduct->starting_bid }}</td>
                                <td>{{ $biddingProduct->current_bid ?? 'N/A' }}</td>

                                <td>{{ $biddingProduct->highest_bid ?? 'N/A' }}</td>
                                <td>{{ $biddingProduct->highestBidder->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($biddingProduct->bid_start_time)->format('d M Y, h:i A') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($biddingProduct->bid_end_time)->format('d M Y, h:i A') }}</td>

                                <td>
                                    @if (\Carbon\Carbon::now()->greaterThan($biddingProduct->bid_end_time))
                                        <span class="badge bg-secondary">Closed</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>

                                <td>
                                    <span
                                        class="badge  {{ $biddingProduct->is_purchased ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $biddingProduct->is_purchased ? 'Purchased' : 'Pending' }}</span>
                                </td>

                                <td>
                                    <a href="{{ route('bidding_products.edit', $biddingProduct->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <form action="{{ route('bidding_products.destroy', $biddingProduct->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11">No Data found</td>
                        </tr>
                    @endif

                </tbody>
            </table>
            {{ $biddingProducts->links() }}
        </div>
    </div>
@endsection
