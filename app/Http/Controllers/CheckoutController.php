<?php

namespace App\Http\Controllers;

use App\Mail\orderEmail;
use App\Models\BiddingProduct;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Setting;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Ixudra\Curl\Facades\Curl;
use Srmklive\PayPal\Services\PayPal;

class CheckoutController extends Controller
{
    public function processCheckout(Request $request)
    {
        // check user Authentication
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'You must be logged in to checkout.'
            ]);
        }

        // Check Post Method
        if ($request->isMethod('post')) {

            // input validations
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required|numeric|digits:10',
                'email' => 'required|email',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'zip' => 'required',
                'address' => 'required',
                'paymentMethod' => 'required|in:phonePe,paypal,cod',
            ], [
                'address.required' => 'Please enter street address.',
                'phone.digits' => 'Please enter a valid phone number',
                'paymentMethod.required' => 'Please select a payment method',
                'paymentMethod.in' => 'Please select a valid payment method',
            ]);

            if (!$validator->passes()) {
                return response()->json([
                    'status' => 'validate',
                    'errors' => $validator->errors(),
                ]);
            }

            $user = Auth::user();

            // update customer address
            $customerAddress = CustomerAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => $request->name,
                    'last_name' => $request->l_name,
                    'email' => $request->email,
                    'mobile' => $request->phone,
                    'country' => $request->country,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                ]
            );

            // discount code check
            // $discountDetails
            if (session()->has('code')) {
                $code = session()->get('code');

                $discountDetails =  [
                    'discount' => $code->discount_amount,
                    'coupon_id' => $code->id,
                    'discount_code' => $code->code,
                ];
            } else {
                $discountDetails =  [
                    'discount' => 0,
                    'coupon_id' => null,
                    'discount_code' => null,
                ];
            }

            // payment method check
            $paymentMethod = $request->paymentMethod;

            if ($paymentMethod == 'cod') {
                return $this->cod($request, $customerAddress, $discountDetails);
            } else if ($paymentMethod == 'phonePe') {
                return $this->handlePhonePayPayment($request, $customerAddress, $discountDetails);
            } else if ($paymentMethod == 'paypal') {
                return $this->handlePayPalPayment($request, $customerAddress, $discountDetails);
            }
        } else {
            abort(404);
        }
    }
    public function cod(Request $request, $customerAddress, $discountDetails)
    {

        $subTotal = Cart::subtotal(2, '.', '');
        $shipping = calculateShipping();
        $discount = $discountDetails['discount'];
        $grandTotal = $subTotal + $shipping - $discount;
        $grandTotal = number_format($grandTotal, 2, '.', '');

        $orderId = 'ODR' . mt_rand(100000, 999999);

        session()->put('checkout_data', [
            'name' => $request->name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'note' => $request->note,
            'customer_address' => $customerAddress,
            'discountDetails' => $discountDetails,
            'subTotal' => $subTotal,
            'grandTotal' => $grandTotal,
            'shipping' => $shipping,
            'orderId' => $orderId
        ]);


        $checkoutData = session()->get('checkout_data');

        if (!$checkoutData) {
            return redirect()->route('front.cart')->with('error', 'Invalid session data.');
        }

        $discountDetails = $checkoutData['discountDetails'];

        // create order
        $order = $this->createOrder(
            $checkoutData,
            $discountDetails,
            "COD"
        );

        // store order items
        $this->storeOrderItems($order->id);

        // update quantities
        $this->updateProductQuantities();

        // send order email to user and admin both

        $this->sendOrderEmail($order->id);

        // finalize order
        $this->finalizeOrder();

        return redirect()->route('account.orders')->with('success', 'Order Placed Successfully.');
    }



    public function getAccessToken()
    {
        // if (Cache::has('phonepe_access_token')) {
        //     return Cache::get('phonepe_access_token');
        // }

        $clientId = env('PHONEPE_CLIENT_ID');
        $clientSecret = env('PHONEPE_CLIENT_SECRET');
        $clientVersion = env('PHONEPE_ENV') === 'production'
            ? env('PHONEPE_CLIENT_VERSION')
            : 1; // version 1 for UAT

        $url = env('PHONEPE_ENV') === 'production'
            ? "https://api.phonepe.com/apis/identity-manager/v1/oauth/token"
            : "https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token";

        $payload = [
            "client_id" => $clientId,
            "client_version" => $clientVersion,
            "client_secret" => $clientSecret,
            "grant_type" => "client_credentials",
        ];

        $response = Http::asForm()->post($url, $payload);
        if ($response->successful()) {
            $data = $response->json();
            $accessToken = $data['access_token'] ?? null;
            $expiresAt = $data['expires_at'] ?? null;

            if ($accessToken && $expiresAt) {

                $expirySeconds = $expiresAt - time();

                // Cache::put('phonepe_access_token', $accessToken, $expirySeconds);

                return $accessToken;
            }
        }

        return false;
    }

    public function checkOrderStatus($merchantOrderId)
    {

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return response()->json(['error' => 'Failed to fetch access token'], 500);
        }

        $url = env('PHONEPE_ENV') === 'production'
            ? "https://api.phonepe.com/apis/pg/checkout/v2/order/{$merchantOrderId}/status"
            : "https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/order/{$merchantOrderId}/status";

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'O-Bearer ' . $accessToken,
        ])->get($url, [
            'details' => false,
            'errorContext' => true
        ]);

        return $response->json();
    }

    public function handlePhonePayPayment(Request $request, $customerAddress, $discountDetails)
    {

        $access_token = $this->getAccessToken();

        if (!$access_token) {
            return response()->json(['error' => 'Failed to fetch access token'], 500);
        }

        $environment = env('PHONEPE_ENV', 'testing');

        $merchantId = env('PHONEPE_MERCHANT_ID');


        $saltIndex = 1;

        $subTotal = Cart::subtotal(2, '.', '');
        $shipping = calculateShipping();
        $discount = $discountDetails['discount'];
        $grandTotal = $subTotal + $shipping - $discount;
        $grandTotal = number_format($grandTotal, 2, '.', '');

        $orderId = 'ODR' . mt_rand(100000, 999999);

        $amount = $grandTotal * 100;

        $callbackUrl = route('phonepe.payment.callback');


        $payload = [
            "merchantOrderId" => $orderId,
            "amount" => $amount,
            "callbackUrl" => $callbackUrl,
            "paymentFlow" => [
                "type" => "PG_CHECKOUT",
                "message" => "Payment message used for collect requests",
                "merchantUrls" => [
                    "redirectUrl" => $callbackUrl,
                    "callbackUrl" => $callbackUrl,
                ]
            ]
        ];

        // $payloadJson = json_encode($payload);

        $url = env('PHONEPE_ENV') === 'production'
            ? "https://api.phonepe.com/apis/pg/checkout/v2/pay"
            : "https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/pay";

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'O-Bearer ' . $access_token,
        ])->post($url, $payload);


        if ($response === false) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while initiating the payment. Please try again.'
            ]);
        }

        // return $response;
        if (isset($response['orderId']) && $response['orderId']) {

            session()->put('checkout_data', [
                'name' => $request->name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country' => $request->country,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'note' => $request->note,
                'customer_address' => $customerAddress,
                'discountDetails' => $discountDetails,
                'subTotal' => $subTotal,
                'grandTotal' => $grandTotal,
                'shipping' => $shipping,
                'orderId' => $orderId
            ]);

            session()->put('order', [
                'orderId' => $orderId,
                'expireAt' => $response['expireAt'],
                'state' => $response['state'],
            ]);

            return response()->json([
                'status' => true,
                'redirect_url' => $response['redirectUrl'],
            ]);
        } else {

            if (session()->has('checkout_data')) {
                session()->forget('checkout_data');
            }

            if (session()->has('order')) {
                session()->forget('order');
            }


            return $response;

            // return response()->json([
            //     'status' => false,
            //     'message' => 'PhonePe failed.',
            //     'err' => $response,
            // ]);
        }
    }

    public function phonePeCallback(Request $request)
    {


        $order = session()->get('order');

        if (!$order) {
            return redirect()->route('front.cart')->with('error', 'Invalid order data.');
        }

        $merchantOrderId = $order['orderId'];

        $state = $order['state'];

        $data = $this->checkOrderStatus($merchantOrderId);

        // return $data;

        if (isset($data['state']) && $data['state'] == 'COMPLETED') {
            $checkoutData = session()->get('checkout_data');

            if (!$checkoutData) {
                return redirect()->route('front.cart')->with('error', 'Invalid checkout data.');
            }

            $discountDetails = $checkoutData['discountDetails'];

            // create order
            $order = $this->createOrder(
                $checkoutData,
                $discountDetails,
                "PhonePe"
            );

            // store order items
            $this->storeOrderItems($order->id);

            // update quantities
            $this->updateProductQuantities();

            // send order email to user and admin both
            $this->sendOrderEmail($order->id);

            // save payment details
            $this->storePaymentDetails($data, $order->id, 'phonePe');

            // finalize order
            $this->finalizeOrder();

            return redirect()->route('account.orders')->with('success', 'Order Placed Successfully.');
        } elseif (isset($data['status']) && $data['status'] == 'duplicate') {

            return redirect()->route('account.profile')->with('success', 'Your Order Is Already Placed.');
        } else {
            return redirect()->route('front.cart')->with('error', 'Payment Failed.');
        }
    }

    public function handlePayPalPayment($request, $customerAddress, $discountDetails)
    {
        // generated paypal client id
        $paypal = new Paypal();
        $paypal->setApiCredentials(config('paypal'));
        $token = $paypal->getAccessToken();
        $paypal->setAccessToken($token);

        $subTotal = Cart::subtotal(2, '.', '');
        $shipping = calculateShipping();
        $discount = $discountDetails['discount'];
        $grandTotal = $subTotal + $shipping - $discount;
        $grandTotal = number_format($grandTotal, 2, '.', '');
        $orderId = 'ODR' . mt_rand(100000, 999999);

        // Initiate PayPal Payment
        $order = $paypal->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $grandTotal,
                    ],
                    "description" => "Order #$orderId",
                ]
            ]
        ]);

        if (isset($order['id'])) {
            session()->put('paypal_order_id', $order['id']);

            session()->put('checkout_data', [
                'name' => $request->name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country' => $request->country,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'note' => $request->note,
                'customer_address' => $customerAddress,
                'discountDetails' => $discountDetails,
                'subTotal' => $subTotal,
                'grandTotal' => $grandTotal,
                'shipping' => $shipping,
                'orderId' => $orderId
            ]);

            return response()->json([
                'status' => true,
                'redirect_url' => $order['links'][1]['href'],
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong with PayPal payment.',
            ]);
        }
    }

    public function paypalSuccess(Request $request)
    {
        // paypal configration
        $paypal = new Paypal();
        $paypal->setApiCredentials(config('paypal'));
        $token = $paypal->getAccessToken();
        $paypal->setAccessToken($token);


        $paypalOrderId = session()->get('paypal_order_id');

        $response = $paypal->capturePaymentOrder($paypalOrderId);

        if ($response['status'] == 'COMPLETED') {

            $checkoutData = session()->get('checkout_data');

            if (!$checkoutData) {
                return redirect()->route('front.cart')->with('error', 'Invalid session data.');
            }

            $discountDetails = $checkoutData['discountDetails'];

            // create order
            $order = $this->createOrder(
                $checkoutData,
                $discountDetails,
                "PayPal"
            );

            // store order items
            $this->storeOrderItems($order->id);

            // update quantities
            $this->updateProductQuantities();

            // send order email to user and admin both
            $this->sendOrderEmail($order->id);

            // save payment details
            $this->storePaymentDetails($response, $order->id, 'paypal');

            // finalize order
            $this->finalizeOrder();

            return redirect()->route('account.orders')->with('success', 'Order Placed Successfully.');
        } else {
            return redirect()->route('front.cart')->with('error', 'Payment failed.');
        }
    }

    public function paypalCancel()
    {
        session()->forget('paypal_order_id');
        if (session()->has('checkout_data')) {
            session()->forget('checkout_data');
        }
        return redirect()->route('front.cart')->with('error', 'Payment cancelled.');
    }

    private function storePaymentDetails($response, $orderId, $gateway)
    {
        if ($gateway == 'phonePe') {
            Payment::create([
                'user_id' => Auth::user()->id,
                'order_id' => $orderId,
                'gateway' => 'PhonePe',
                'phonepe_transaction_id' => $response['orderId'],
                'amount' => $response['amount'] / 100,
                'currency' => 'INR',
                'status' => $response['state'],
            ]);
        } elseif ($gateway == 'paypal') {

            Payment::create([
                'user_id' => Auth::user()->id,
                'order_id' => $orderId,
                'gateway' => 'Paypal',
                'phonepe_transaction_id' => $response['id'],
                'gateway_email' => $response['payer']['email_address'],
                'gateway_payer_id' => $response['payer']['payer_id'],
                'amount' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'],
                'currency' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'],
                'status' => $response['status'],
            ]);
        }
    }

    private function createOrder($checkoutData, $discountDetails, $paymentMethod)
    {
        $order = new Order();

        $order->orderId = $checkoutData['orderId'];
        $order->user_id = Auth::id();
        $order->subtotal = $checkoutData['subTotal'];
        $order->shipping = $checkoutData['shipping'];
        $order->coupon_code = $discountDetails['discount_code'];
        $order->discount = $discountDetails['discount'];
        $order->grand_total = $checkoutData['grandTotal'];
        $order->status = 'pending';
        $order->payment_type = $paymentMethod;
        $order->payment_status = 'paid';

        $order->first_name = $checkoutData['name'];
        $order->last_name = $checkoutData['l_name'];
        $order->email = $checkoutData['email'];
        $order->mobile = $checkoutData['phone'];
        $order->country = $checkoutData['country'];
        $order->address = $checkoutData['address'];
        $order->city = $checkoutData['city'];
        $order->state = $checkoutData['state'];
        $order->zip = $checkoutData['zip'];
        $order->order_note = $checkoutData['note'];

        $order->save();

        return $order;
    }
    private function storeOrderItems($orderId)
    {
        foreach (Cart::content() as $item) {

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
                $product->color = $variation->color;
                $product->size = $variation->size;
                $product->sku = $variation->sku;
            }

            if (!$product) {
                continue; // Optionally handle the case where the variation is not found
            }

            $color = $item->options->color ?? 'default';
            $size = $item->options->size ?? 'default';

            $orderItem = new OrderItem();
            $orderItem->order_id = $orderId;
            $orderItem->sku = $item->id;
            $orderItem->name = $item->name . '-' . $color . '-' . $size;
            $orderItem->qty = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->total = $item->price * $item->qty;
            $orderItem->save();
        }
    }
    private function updateProductQuantities()
    {
        foreach (Cart::content() as $item) {

            $product = Product::Where('sku', $item->id)->first();

            if ($product == null) {

                $variation = ProductVariation::where('sku', $item->id)->first();

                if ($variation == null) {
                    continue;
                }

                $product = $variation->product;
                $product->quantity = $variation->quantity;
            }

            if (!$product) {
                continue;
            }

            $product->quantity -= $item->qty;
            $product->save();

            
        }
    }

    private function finalizeOrder()
    {

        Cart::destroy();

        if (session()->has('checkout_data')) {
            session()->forget('checkout_data');
        }


        if (session()->has('order')) {
            session()->forget('order');
        }

        if (session()->has('paypal_order_id')) {
            session()->forget('paypal_order_id');
        }


        if (session()->has('code')) {
            session()->forget('code');
        }
    }

    private function sendOrderEmail($orderId)
    {

        try {

            $order = Order::find($orderId);
            orderEmail($orderId, 'customer');
            $subject = 'Recieved New Order.';
            $email = Setting::latest()->first()->email;
            $mailData = [
                'subject' => $subject,
                'order' => $order,
                'userType' => 'admin'
            ];
            Mail::to($email)->send(new orderEmail($mailData));
        } catch (\Throwable $th) {
        }
    }
}
