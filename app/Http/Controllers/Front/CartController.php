<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    public function cart()
    {
        $cartContent = Cart::content();

        $data['cartContent'] = $cartContent;
        return view('frontend_new.cart', $data);
    }

    public function addToCart(Request $request)
    {


        $variationId = $request->id;



        $options = $request->input('options', []);
        $string = '';

        if (!empty($options)) {
            $formattedOptions = [];

            foreach ($options as $key => $value) {
                $formattedKey = str_replace(' ', '_', strtolower($key));
                $formattedOptions[] = "{$formattedKey} = {$value}";
            }

            $string = implode(' | ', $formattedOptions);
        }



        if (session()->has('code')) {
            session()->forget('code');
        }

        $product = Product::with('images')->where('sku', $variationId)->first();

        if ($product == null) {

            $variation = ProductVariation::where('sku', $variationId)->first();

            if ($variation == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, product not found.'
                ]);
            }

            $product = $variation->product;
            $product->sku = $variation->sku;
            $product->quantity = $variation->quantity;
            $product->color = $variation->color;
            $product->size = $variation->size;
            $product->price = $variation->price;
        }

        $qty = $request->qty ?? 1;

        if ($qty > $product->quantity) {
            $qty = $product->quantity;
        }

        $cartContent = Cart::content();
        $productAlreadyExists = false;
        $rowId = null;

        foreach ($cartContent as $key => $item) {
            if ($item->id == $product->sku) {
                $productAlreadyExists = true;
                $rowId = $key;
            }

            if ($productAlreadyExists) {

                Cart::update($item->rowId, [
                    'qty' => $qty,
                    'name' => $product->name . ' ' . $string,
                ]);
                $productAlreadyExists = false;
            }
        }

        if ($productAlreadyExists) {
            $message = 'Cart Item Updated.';
        } else {

            Cart::add(
                $product->sku,
                $product->name . ' ' . $string,
                $qty,
                $product->price,
                [
                    'product_image' => $product->defaultImage->image ?? $product->images->first()->image,
                    'size' => $product->size,
                    'color' => $product->color
                ]
            );
            $message = 'Product added successfully.';
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'totalItemsInCart' => Cart::count(),
            'cartContent' => Cart::content()
        ]);
    }

    public function updateCart(Request $request)
    {
        if (session()->has('code')) {
            session()->forget('code');
        }

        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        // Check in products table first
        $variation = Product::where('sku', $itemInfo->id)->first();

        // Check in product_variations table if not found in products
        if (!$variation) {
            $variation = ProductVariation::where('sku', $itemInfo->id)->first();
        }

        if (!$variation) {
            return response()->json([
                'status' => false,
                'message' => 'Product variation not found.'
            ]);
        }

        if ($qty <= $variation->quantity) {
            Cart::update($rowId, $qty);
            $message = 'Cart updated successfully.';
            $status = true;
        } else {
            $message = 'Sorry, requested quantity is not available in stock. Available items in stock is ' . $variation->quantity;
            $status = false;
            $qty = $variation->quantity;
        }

        $item_total = $itemInfo->price * $qty;
        $cart_subtotal = Cart::subtotal();
        $totalItemsInCart = Cart::count();

        return response()->json([
            'status' => $status,
            'message' => $message,
            'qty' => $qty,
            'item_total' => $item_total,
            'cart_subtotal' => $cart_subtotal,
            'totalItemsInCart' => $totalItemsInCart
        ]);
    }
    public function miniCartContent(Request $request)
    {
        $cartItems = Cart::content();
        return view('front.partials.cart', compact('cartItems'));
    }


    public function reArrangeCart(Request $request)
    {
        if (Cart::count() > 0) {
            session()->forget('invalidCart');
            foreach (Cart::content() as $key => $item) {

                $product = Product::Where('sku', $item->id)->first();

                if ($product == null) {

                    $variation = ProductVariation::where('sku', $item->id)->first();

                    if ($variation == null) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Sorry, product not found.'
                        ]);
                    }
                    $product = $variation->product;
                    $product->quantity = $variation->quantity;
                }

                if (!$product || $product->quantity <= 0) {
                    Cart::remove($key);
                } elseif ($item->qty > $product->quantity) {
                    Cart::update($key, $product->quantity);
                }
            }

            session()->flash('success', 'Cart items are refreshed with available stock, and out-of-stock items are removed.');
            return response()->json([
                'status' => true
            ]);
        } else {
            session()->flash('error', 'Sorry, something unexpected happened.');
            return response()->json([
                'status' => false
            ]);
        }
    }

    private function checkCartItems()
    {
        if (Cart::count() > 0) {
            foreach (Cart::content() as $key => $item) {

                $product = Product::Where('sku', $item->id)->first();

                if ($product == null) {

                    $variation = ProductVariation::where('sku', $item->id)->first();

                    if ($variation == null) {
                        return  [
                            'status' => false,
                            'message' => 'Sorry, sku not found.'
                        ];
                    }
                    $product = $variation->product;
                    $product->quantity = $variation->quantity;
                }

                if ($product->quantity <= 0) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sorry, Product is out of stock.'
                    ]);
                } elseif ($item->qty > $product->quantity) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sorry, insufficient quantity.'
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'All Good'
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Sorry, something unexpected happened.'
            ]);
        }
    }

    public function deleteItem(Request $request)
    {

        if (session()->has('code')) {
            session()->forget('code');
        }

        $rowId = $request->rowId;

        $itemInfo = Cart::get($rowId);

        if ($itemInfo == null) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found in cart.'

            ]);
        } else {

            Cart::remove($request->rowId);

            $card_subtotal = Cart::subtotal();
            $totalItemsInCart = Cart::count();
            return response()->json([
                'status' => true,
                'message' => 'Item removed successfully.',
                'card_subtotal' => $card_subtotal,
                'totalItemsInCart' => $totalItemsInCart

            ]);
        }
    }

    public function checkout()
    {
        if (Cart::count() > 0) {
            if (Auth::check()) {

                if (session()->has('urlintended')) {
                    session()->forget('urlintended');
                }

                $checkCart = $this->checkCartItems()->getData(true);

                if (!$checkCart['status']) {
                    session()->put('invalidCart', 1);
                    return redirect()->route('front.cart')->with('error', $checkCart['message']);
                }

                $totalShipping = 0;

                $address = CustomerAddress::where('user_id', Auth::user()->id)->first();

                $grandTotal = $totalShipping + Cart::subtotal(2, '.', '');

                $data['address'] = $address;
                $data['shipping'] = $totalShipping;
                $data['grandTotal'] = $grandTotal;

                if (session()->has('code')) {
                    $code = session()->get('code');
                }

                return view('frontend_new.checkout', $data);
            } else {
                session(['urlintended' => url()->current()]);
                session()->save();
                session()->flash('message', 'Please Login to your account to proceed.');
                return redirect()->route('account.login');
            }
        } else {
            return redirect()->route('front.cart');
        }
    }

    public function shipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required|numeric',

        ]);

        if ($validator->passes()) {

            // $shipping = shippingCharge::where('country_id', '=', $country)->first();

            // if ($shipping != '') {
            //     $totalItems = 0;
            //     foreach (Cart::content() as  $item) {
            //         $totalItems += $item->qty;
            //     }
            //     $totalShipping = $shipping->amount * $totalItems;

            //     return response()->json([
            //         'status' => true,
            //         'shipping' => $totalShipping

            //     ]);
            // } else {
            //     return response()->json([
            //         'status' => false,
            //         'shipping' => 0
            //     ]);
            // }

            return response()->json([
                'status' => false,
                'shipping' => 0
            ]);
        }
    }


    public function thanks()
    {
        if (session()->has('orderId')) {

            $orderId = session()->get('orderId');
            $cartContent = session()->get('cartContent');
            $subTotal = session()->get('subTotal');
            $shipping = session()->get('shipping');
            $discount = session()->get('discount');
            $grandTotal = session()->get('grandTotal');
            $CustomerAddress = session()->get('CustomerAddress');
            $paymentMethod = session()->get('paymentMethod');

            $order = session()->get('order');


            // session()->forget('orderId');


            $data['orderId'] = $orderId;
            $data['cartContent'] = $cartContent;

            $data['subTotal'] = $subTotal;
            $data['shipping'] = $shipping;
            $data['discount'] = $discount;
            $data['grandTotal'] = $grandTotal;
            $data['CustomerAddress'] = $CustomerAddress;
            $data['paymentMethod'] = $paymentMethod;

            $data['order'] = $order;

            return view('front.thanks', $data);
        } else {
            return redirect()->route('index');
        }
    }

    public function applyCoupon(Request $request)
    {
        if (session()->has('code')) {
            session()->forget('code');
        }

        if ($request->removeCoupon == 1) {
            session()->flash('success', 'Coupon Code Removed.');
            return redirect()->back();
        }

        $code = DiscountCoupon::where(['code' => $request->code, 'status' => 1])->first();
        if ($code == null) {
            if (session()->has('code')) {
                session()->forget('code');
            }
            return redirect()->back()->with('error', 'Invalid Coupon Code.');
        }

        if ($code->code === $request->code) {

            $now = Carbon::now();

            if ($code->starts_at != '') {
                $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);

                if ($now->lt($startsAt)) {
                    session()->flash('error', 'Invalid Coupon Code.');
                    return redirect()->back();
                }
            }
            if ($code->expires_at != '') {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

                if ($now->gt($expiresAt)) {
                    session()->flash('error', 'This Coupon Code has Expired.');
                    return redirect()->back();
                }
            }

            if ($code->discount_amount >= Cart::subtotal(2, '.', '')) {

                session()->flash('error', 'This product is not eligible for discount.');

                return redirect()->back();
            }

            if ($code->min_amount != '') {

                if (Cart::subtotal(2, '.', '') < $code->min_amount) {

                    session()->flash('error', 'Your cart subtotal is not eligible for this discount coupon. Min. subtotal Rs. ' . $code->min_amount . ' required.');

                    return redirect()->back();
                }
            }

            if ($code->type == 'freeShipping') {

                $shipping = 0;

                // if ($shipping != '') {
                //     $totalItems = 0;
                //     foreach (Cart::content() as  $item) {
                //         $totalItems += $item->qty;
                //     }
                //     $totalShipping = $shipping->amount * $totalItems;
                // } else {
                //     $totalShipping = 0;
                // }
                $totalShipping = 0;

                $code->discount_amount = $totalShipping;
            } elseif ($code->type == 'percent') {
                $code->discount_amount = ($code->discount_amount / 100) * Cart::subtotal(2, '.', '');
            }

            if ($code->max_uses != '') {
                $codeUses = Order::where('coupon_id', $code->id)->count();
                if ($codeUses >= $code->max_uses) {
                    session()->flash('error', 'Sorry, This coupon has expired.');
                    return redirect()->back();
                }
            }
            if ($code->max_uses_user != '') {
                if (Auth::check() == true) {
                    $codeUserUses = Order::where('coupon_id', $code->id)->where('user_id', Auth::user()->id)->count();
                    if ($codeUserUses >= $code->max_uses_user) {
                        session()->flash('error', 'Sorry Coupon Expired [ You used this coupon Maximum Times. ].');
                        return redirect()->back();
                    }
                } else {
                    session(['urlintended' => route('front.cart')]);
                    session()->save();
                    session()->flash('message', 'You have to start your session first to apply this Coupon Code.');
                    return redirect()->route('account.login');
                }
            }

            session()->put('code', $code);
            session()->flash('success', 'Coupon Code Applied Successfully.');
            return redirect()->back();
        } else {
            session()->flash('error', 'Invalid Coupon Code.');
            if (session()->has('code')) {
                session()->forget('code');
            }
            return redirect()->back();
        }
    }
}
