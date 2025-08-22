@if ($gift)
    <div class="card mt-3">
        <div class="card-body">
            @if ($gift->type == 'gift_card')
                <h5>🎁 Gift Card</h5>
                <p>Gift Card Value: <strong>${{ number_format($gift->gift_card_value, 2) }}</strong></p>
            @elseif ($gift->type == 'coupon_code')
                <h5>🎟️ Coupon Code</h5>
                <p>Use Coupon Code: <strong>{{ $gift->coupon_code }}</strong></p>
            @elseif ($gift->type == 'buy_one_get_one')
                <h5>🛍️ Buy One Get One Free</h5>
                <p>Congratulations! You're eligible for a Buy One Get One Free offer.</p>
            @endif
        </div>
    </div>
@else
    <div class="alert alert-info mt-3">
        <p>You have not received any gift yet.</p>
    </div>
@endif
