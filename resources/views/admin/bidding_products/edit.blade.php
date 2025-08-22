@extends('admin.includes.layout')

@section('content')
    <script>
        $('#nav-iteml_bidding').addClass('active');
    </script>
    <div class="container">
        <h3 class="mb-3">Update Bidding Product</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('bidding_products.update', $biddingProduct->id) }}" method="post">
            @csrf
            <div class="form-group mb-3">
                <label>Product</label>
                <select name="product_id" class="form-control" required>
                    <option value="">Select a product</option>
                    @foreach ($products as $product)
                        <option {{ $biddingProduct->product_id == $product->id ? 'selected' : '' }}
                            value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Starting Bid</label>
                <input step="0.1" value="{{ $biddingProduct->starting_bid }}" type="number" name="starting_bid"
                    class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Start Time</label>
                <input value="{{ $biddingProduct->bid_start_time }}" type="datetime-local" name="bid_start_time"
                    class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>End Time</label>
                <input value="{{ $biddingProduct->bid_end_time }}" type="datetime-local" name="bid_end_time"
                    class="form-control" required>
            </div>


            <button type="submit" class="btn btn-primary">Update Bidding Product</button>
        </form>
    </div>
@endsection
