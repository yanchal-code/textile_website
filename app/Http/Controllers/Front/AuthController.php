<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\forgotPasswordMail;
use App\Models\BiddingProduct;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wishlist;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->passes()) {

                $admin =  User::where('email', $request->email)->first();

                if ($admin) {
                    if (!$admin->is_blocked) {
                        $remember = !empty($request->remember) ? true : false;

                        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
                            $authorized_admin = Auth::user();
                            $admin->login_attempts = 0;
                            $admin->save();

                            if ($authorized_admin->role == 2) {

                                if (session()->has('urlintended')) {
                                    $url = session()->get('urlintended');
                                    session()->forget('urlintended');
                                    return redirect($url);
                                } else {
                                    return redirect()->route('account.profile');
                                }
                            } else {
                                Auth::logout();
                                return  redirect()->route('admin.login')->withInput($request->only('email'))->with('error', 'Admin should authanticate from here.');
                            }
                        } else {
                            // Increment login attempts
                            $admin->login_attempts++;
                            $admin->save();
                            if ($admin->login_attempts >= 10) {
                                $admin->is_blocked = true;
                                $admin->save();
                                return redirect()->route('account.login')->withInput($request->only('email'))->with('error', 'Your account has been blocked due to multiple incorrect login attempts.');
                            } else {
                                return  redirect()->route('account.login')->with('error', 'Incorrect password. Remember: Your Account will be blocked after ' . 10 - $admin->login_attempts . ' wrong attempts')->withInput($request->only('email'))->withErrors(['password' => 'Incorrect  password.']);
                            }
                        }
                    } else {
                        $admin->remember_token = Str::random(40);
                        $currentTime = Carbon::now();
                        $time60MinutesLater = $currentTime->addMinutes(60);
                        $admin->reset_attempted =  $time60MinutesLater;
                        $admin->save();
                        Mail::to($request->email)->send(new forgotPasswordMail($admin));
                        return redirect()->route('account.login')->withInput($request->only('email'))->with('error', 'Your account has been blocked check your email to reset password.');
                    }
                } else {

                    return  redirect()->route('account.login')->withInput($request->only('email'))->with('error', 'Account Not Found.');
                }
            } else {

                return  redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
            }
        }

        $data['selectedForm'] = 'login';
        return view('front.account.login');
    }
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users',
                'phone' => 'required|numeric|digits:10',
                'password' => 'required|min:5',
                'cpassword' => 'required|same:password'

            ], [
                'email.unique' => 'Account already exists with this email address.',
                'cpassword.same' => 'Confirm password not match please check and re-confirm your password.',
                'phone.digits' => 'Please enter a valid phone number.'
            ]);


            if ($validator->passes()) {

                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone = $request->phone;

                $user->password = Hash::make($request->cpassword);

                $user->save();


                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'User Registered Successfully, you can proceed to login.'
                    ]
                );
            } else {

                return  response()->json(
                    [
                        'status' => false,
                        'errors' => $validator->errors()
                    ]
                );
            }
        }

        return view('front.account.register');
    }

    public function forgot(Request $request)
    {

        if ($request->isMethod('post')) {

            $user = User::where('email', '=', $request->email)->first();

            if (!empty($user)) {
                $userType = $user->role;
                if ($userType == 1) {
                    return  response()->json([
                        'status' => 'notFound',
                        'message' => 'We are not authorized to proceed this request.'
                    ]);
                }
                $user->remember_token = Str::random(40);
                $currentTime = Carbon::now();
                $time60MinutesLater = $currentTime->addMinutes(60);
                $user->reset_attempted =  $time60MinutesLater;
                $user->save();

                Mail::to($request->email)->send(new forgotPasswordMail($user));

                return  response()->json([
                    'status' => true,
                    'message' => 'Request generated successfully, Please check you email to reset password.'
                ]);
            } else {
                return  response()->json([
                    'status' => 'notFound',
                    'message' => 'No users found with the provided email.'
                ]);
            }
        }
    }

    public function profile()
    {
        $user = Auth::user();
        $orders = Order::where('orders.user_id', $user->id)->orderBy('orders.created_at', 'DESC')
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->select(
                'orders.*',
                'payments.gateway as payment_gateway',
                'payments.currency'
            )->get();

        $data['orders'] = $orders;
        $data['address'] = CustomerAddress::where('user_id', Auth::user()->id)->first();


        return view('front.account.home', $data);
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('account.login'));
    }


    public function updateAddress(Request $request)
    {
        if ($request->isMethod('post')) {

            // First Step
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'phone' => 'required|numeric|digits:10',
                'email' => 'required|email',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'zip' => 'required',
                'address' => 'required',
            ], [
                'address.required' => 'Please enter street address.',
                'phone.digit' => 'Please enter a valid phone number',

            ]);

            if (!$validator->passes()) {
                return  response()->json(
                    [
                        'status' => false,
                        'errors' => $validator->errors()
                    ]
                );
            }

            $user = Auth::user();
            if ($user == null) {
                return  response()->json(
                    [
                        'status' => 'false',
                        'errors' => 'Sorry, Something Went Wrong.'
                    ]
                );
            }
            CustomerAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->phone,
                    'country' => $request->country,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                ]
            );
            session()->flash('type', 'success');
            session()->flash('message', 'Default Address Updated Successfully.');
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Default Address Updated Successfully.',
                    'reload' => true
                ]
            );
        } else {
            abort(404);
        }
    }
    public function updateUserInfo(Request $request)
    {
        if ($request->isMethod('post')) {

            $user = User::find(Auth::user()->id);

            if ($user == null) {
                return  response()->json(
                    [
                        'status' => false,
                        'errors' => 'Sorry, Something Went Wrong.'
                    ]
                );
            }
            $rules = [
                'email' => 'required|email|unique:users,email,' . Auth::user()->id . 'id',
                'name' => 'required|min:3',
                'phone' => 'required|numeric|digits:10',
            ];

            if ($request->changePassword) {
                $rules['password'] = 'required';
                $rules['newPassword'] = 'required|min:3';
                $rules['confirmNewPassword'] = 'required|same:newPassword';
            }

            $validator = Validator::make($request->all(), $rules, [
                'email.unique' => 'This email already exists please choose different one.',
                'phone.digits' => 'Please enter a valid phone number.'
            ]);


            if ($validator->passes()) {

                if ($request->changePassword) {

                    if (!Hash::check($request->password, $user->password)) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Your current password is incorrect.'
                        ]);
                    }
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->phone = $request->phone;
                    $user->password = Hash::make($request->newPassword);
                    $user->updated_at =  Carbon::now();
                    $user->save();
                } else {

                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->phone = $request->phone;
                    $user->updated_at =  Carbon::now();
                    $user->save();
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Information Updated Successfully.',
                    'reload' => true,
                ]);
            } else {
                return  response()->json(
                    [
                        'status' => false,
                        'errors' => $validator->errors()
                    ]
                );
            }
        } else {
            abort(404);
        }
    }
    public function orders()
    {

        $user = Auth::user();
        $orders = Order::where('orders.user_id', $user->id)->orderBy('orders.created_at', 'DESC')
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->select(
                'orders.*',
                'payments.gateway as payment_gateway',
                'payments.currency'
            )->get();

        $data['orders'] = $orders;
        return view('front.account.home', $data);
    }
    public function orderDetail($id)
    {

        $user = Auth::user();
        $order = Order::where('orders.user_id', $user->id)->where('orders.id', $id)
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->select(
                'orders.*',
                'payments.gateway as payment_gateway',
                'payments.currency'
            )->first();

        if ($order) {
            $orderItems = OrderItem::where('order_id', $id)->get();

            $data['order'] = $order;
            $data['orderItems'] = $orderItems;

            return view('front.account.orderDetails', $data);
        } else {
            abort(404);
        }
    }
    public function wishlist()
    {
        $data = [];
        $products = Product::whereIn('id', function ($query) {
            $query->select('product_id')
                ->from('wishlists')
                ->where('user_id', Auth::id());
        })->paginate(30);
        $data['products'] = $products;
        return view('front.account.wishlist', $data);
    }



    // public function updateRegion($country = 'Other')
    // {
    //     if ($country == 'India' || $country == 'Other') {

    //         $user = Auth::user();
    //         $user->region = $country;
    //         $user->save();

    //         $settings = Setting::latest()->first();

    //         if (Cart::count() > 0) {
    //             foreach (Cart::content() as $key => $item) {

    //                 $product = Product::Where('sku', $item->id)->first();

    //                 if ($product == null) {

    //                     $variation = ProductVariation::where('sku', $item->id)->first();

    //                     if ($variation == null) {
    //                         continue;
    //                     }

    //                     $product = $variation->product;
    //                     $product->price = $variation->price;
    //                 }

    //                 if ($item->options->is_bid) {

    //                     if ($user->region === 'India') {
    //                         $newPrice =  $item->price * $settings->conversion_rate_usd_to_inr;
    //                     } else {
    //                         $newPrice = $item->price / $settings->conversion_rate_usd_to_inr;
    //                     }
    //                     Cart::update($item->rowId, ['price' => $newPrice]);
    //                     continue;
    //                 }

    //                 if ($user->region === 'India') {
    //                     $newPrice = $product->price;
    //                 } else {
    //                     $newPrice = $product->price;
    //                 }
    //                 Cart::update($item->rowId, ['price' => $newPrice]);
    //             }
    //         }

    //         return redirect()->back()->with('success', 'Region updated successfully!');
    //     } else {
    //         return redirect()->back()->with('error', 'Something Went Wrong');
    //     }
    // }
}
