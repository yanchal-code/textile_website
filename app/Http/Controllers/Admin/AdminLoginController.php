<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\forgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->passes()) {
                $admin = User::where('email', $request->email)->first();

                if ($admin) {
                    if (!$admin->is_blocked) {
                        $remember = !empty($request->remember) ? true : false;

                        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
                            $authorized_admin = Auth::guard('admin')->user();
                            $admin->login_attempts = 0;
                            $admin->save();

                            if ($authorized_admin->role == 1) {
                                return redirect()->route('admin.home');
                            } else {
                                Auth::guard('admin')->logout();
                                return redirect()->back()->withInput($request->only('email'))->with('error', 'You are not authorised to access Admin Panel.');
                            }
                        } else {
                            $admin->login_attempts++;
                            $admin->save();

                            if ($admin->login_attempts >= 10) {
                                $admin->is_blocked = true;
                                $admin->save();
                                return redirect()->back()->withInput($request->only('email'))->with('error', 'Your account has been blocked due to multiple incorrect login attempts.');
                            } else {
                                return redirect()->back()->with('error', 'Incorrect password. Remember: Your Account will be blocked after ' . 10 - $admin->login_attempts . ' wrong attempts')->withInput($request->only('email'))->withErrors(['password' => 'Incorrect password.']);
                            }
                        }
                    } else {
                        // $admin->remember_token = Str::random(40);
                        // $currentTime = Carbon::now();
                        // $time60MinutesLater = $currentTime->addMinutes(60);
                        // $admin->reset_attempted =  $time60MinutesLater;
                        // $admin->save();
                        // Mail::to($request->email)->send(new forgotPasswordMail($admin));
                        return redirect()->back()->withInput($request->only('email'))->with('error', 'Your account has been blocked click forgot password and reset password.');
                    }
                } else {
                    return redirect()->back()->withInput($request->only('email'))->with('error', 'Account Not Found.');
                }
            } else {
                return redirect()->back()->withErrors($validator)->withInput($request->only('email'));
            }
        }

        return view('admin.auth.login');
    }

    public function changePassword(Request $request)
    {
        if ($request->isMethod('post')) {

            $user = Auth::user();
            $userId = $user->id;

            $rules = [
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required|email|unique:users,email,' . $userId . 'id',
                'password' => 'required',
            ];

            if ($request->changePassword) {
                $rules['newPassword'] = 'required|min:3';
                $rules['confirmNewPassword'] = 'required|same:newPassword';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->passes()) {

                if (!Hash::check($request->password, $user->password)) {

                    return response()->json([
                        'status' => 'wrongPassword',
                        'message' => 'Your current password is incorrect.'
                    ]);
                } else {
                    $user = User::find(Auth::guard('admin')->user()->id);
                    $user->updated_at =  Carbon::now();
                    if ($request->changePassword) {
                        $user->name = $request->name;
                        $user->email = $request->email;
                        $user->phone = $request->phone;

                        $user->password = Hash::make($request->newPassword);
                        $user->save();
                    } else {

                        $user->name = $request->name;
                        $user->email = $request->email;
                        $user->phone = $request->phone;

                        $user->save();
                    }
                    session()->flash('success', 'Admin Information Updated Successfully.');
                    return response()->json([
                        'status' => true,
                    ]);
                }
            } else {
                return  response()->json(
                    [
                        'status' => false,
                        'errors' => $validator->errors()
                    ]
                );
            }
        }
        return view('admin.auth.changePassword');
    }
    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = User::where('email', '=', $request->email)->first();

            if (!empty($user)) {
                $user->remember_token = Str::random(40);
                $currentTime = Carbon::now();

                $time20MinutesLater = $currentTime->addMinutes(20);

                $user->reset_attempted =  $time20MinutesLater;
                $user->save();
                Mail::to($request->email)->send(new forgotPasswordMail($user));
                return  redirect()->back()->withInput($request->only('email'))->with('success', 'Request generated successfully. Please  check you email to reset your password.');
            } else {
                return  redirect()->back()->withInput($request->only('email'))->with('error', 'Account Not Found.');
            }
        }
        return view('admin.auth.forgotPassword');
    }
    public function reset($token, Request $request)
    {
        $user = User::where('remember_token', '=', $token)->first();

        if (!empty($user)) {

            $currentTime = Carbon::now();

            if ($user->reset_attempted && $currentTime->lte($user->reset_attempted)) {

                if ($request->isMethod('post')) {
                    if ($request->password == '' || $request->cPassword == '') {
                        return  redirect()->back()->with('error', 'Please Fill Both Fields.');
                    }
                    if ($request->password == $request->cPassword) {

                        $user->remember_token = null;
                        $user->reset_attempted  = null;
                        $user->is_blocked = 0;
                        $user->login_attempts = 0;
                        $user->updated_at = $currentTime;
                        $user->email_verified_at = $currentTime;
                        $user->password = Hash::make($request->cPassword);
                        $user->save();
                        if ($user->role == 1) {
                            return  redirect()->route('admin.login')->with('success', 'Password updated successfully.');
                        } else {
                            return  redirect()->route('account.login')->with('success', 'Password updated successfully.');
                        }
                    } else {
                        return  redirect()->back()->with('error', 'New password and confirm password not matching.');
                    }
                } else {
                    return view('admin.auth.newPassword', compact('user'));
                }
            } else {
                echo "Your token has been expired.";
                $user->remember_token = Null;
                $user->reset_attempted  = Null;
                $user->save();
            }
        } else {
            abort(404);
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect(route('admin.login'));
    }
}
