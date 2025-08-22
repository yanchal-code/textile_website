<?php

namespace App\Http\Controllers;

use App\Models\DiscountCoupon;
use App\Models\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class giftController extends Controller
{

    public function gift()
    {

        if (Gift::where('user_id', auth()->id())->first()) {

            return $this->gift_info();
        }


        $giftTypes = ['coupon_code'];
        $selectedGift = Arr::random($giftTypes);
        $user = Auth::user();

        switch ($selectedGift) {
            case 'gift_card':
                $giftValue = rand(0, 10);
                Gift::create([
                    'user_id' => $user->id,
                    'type' => 'gift_card',
                    'gift_card_value' => $giftValue,
                ]);
                break;

            case 'coupon_code':
                // Fetch a random coupon code from discount_coupons table
                $coupon = DiscountCoupon::inRandomOrder()->first();
                Gift::create([
                    'user_id' => $user->id,
                    'type' => 'coupon_code',
                    'coupon_code' => $coupon->code,
                ]);
                break;

            case 'buy_one_get_one':
                Gift::create([
                    'user_id' => $user->id,
                    'type' => 'buy_one_get_one',
                    'buy_one_get_one' => true,
                ]);
                break;
        }

        return $this->gift_info();
    }

    public function gift_info()
    {
        $gift = Gift::where('user_id', auth()->id())->first();

        $htmlResponse = '<div class="gift-info">';

        if ($gift) {
            if ($gift->type == 'gift_card') {
                $htmlResponse .= '
                    <div class="gift-card">
                        <h2>🎉 Congratulations!</h2>
                        <p>You have a <strong>gift card</strong> worth <span class="gift-value">' . $gift->gift_card_value . '$</span>!</p>
                    </div>
                ';
            } elseif ($gift->type == 'coupon_code') {

                $coupon = DiscountCoupon::where('code', $gift->coupon_code)->first();

                if ($coupon->type == 'fixed') {

                    $htmlResponse .= '

                    <div class="gift-card">
                        <h2>🎉 Congratulations!</h2>
                        <p>You got a gift card worth  <span class="gift-value">' . $coupon->discount_amount . '$</span></p>
                         <p>Use code at checkout : <strong>' . $gift->coupon_code . '</strong></p>
                    </div>';
                } elseif ($coupon->type == 'percentage') {

                    $htmlResponse .= '
                    <div class="coupon-code">
                        <h2>🎊 Special Offer!</h2>
                        <p>You got a coupon code for : <strong>' . $coupon->discount_amount . '% OFF </strong></p>
                         <p>Use the code: <strong>' . $gift->coupon_code . '</strong></p>
                    </div>
                ';
                }
            } elseif ($gift->type == 'buy_one_get_one') {
                $htmlResponse .= '
                    <div class="bogo-offer">
                        <h2>✨ Exciting Deal!</h2>
                        <p>You are eligible for <strong>Buy One Get One Free!</strong></p>
                    </div>
                ';
            }
        } else {
            $htmlResponse .= '<p>No gifts available at this time.</p>';
        }

        $htmlResponse .= '</div>';

        return response()->json([
            'html' => $htmlResponse,
            'code' => $coupon->code,
        ]);
    }

    public function show()
    {
        $user = auth()->user();
        $gift = Gift::where('user_id', $user->id)->first();

        return view('front.account.gift', compact('gift'));
    }

    public function showGiftCard()
    {
        $user = auth()->user();

        // Fetch the user's gift card (coupon code)
        $gift = Gift::where('user_id', $user->id)->first();

        if (!$gift) {
            return response()->json(['error' => 'No gift cards found.'], 404);
        }

        // Fetch the coupon details using the coupon_code in the gift
        $coupon = DiscountCoupon::where('code', $gift->coupon_code)->first();

        if (!$coupon) {
            return response()->json(['error' => 'Coupon code not found.'], 404);
        }

        return response()->json([
            'gift' => $gift,
            'coupon' => $coupon,
        ]);
    }
}
