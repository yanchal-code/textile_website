<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request, $type = 'users')
    {

        if ($type == 'admins') {
            $users = User::latest()
                ->where('role', 1);
            $data['totalUsers'] = User::where('role', 1)->count();
            $totalUsersActive = User::where('is_blocked', 0)->where('role', 1)->count();
            $totalUsersBlocked = User::where('is_blocked', 1)->where('role', 1)->count();
        } else {

            $users = User::latest()
                ->where('role', 2);
            $data['totalUsers'] = User::where('role', 2)->count();
            $totalUsersActive = User::where('is_blocked', 0)->where('role', 2)->count();
            $totalUsersBlocked = User::where('is_blocked', 1)->where('role', 2)->count();
        }
        $data['type'] = $type;
        $data['totalUsersActive'] = $totalUsersActive;
        $data['totalUsersBlocked'] = $totalUsersBlocked;

        $selectedStatus = '';

        if (!empty($request->get('status'))) {
            $status = $request->get('status');

            if ($status == 'active') {
                $status = '0';
                $selectedStatus = 'active';
            } else if ($status == 'blocked') {
                $status = '1';
                $selectedStatus = 'blocked';
            }
            $users =  $users->where('is_blocked', '=', $status);
        }
        if (!empty($request->get('keyword'))) {
            $users = $users->where('name', 'like', '%' . $request->get('keyword') . '%')->where('role', 2);
            $users = $users->orwhere('email', 'like', '%' . $request->get('keyword') . '%')->where('role', 2);
        }
        $totaResults = $users->count();

        $users = $users->paginate(12);

        $data['users'] = $users;
        $data['selectedStatus'] = $selectedStatus;
        $data['totaResults'] = $totaResults;

        return view('admin.users.users', $data);
    }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users',
                'phone' => 'required|numeric|digits:10',
                'password' => 'required|min:5',
                'cpassword' => 'required|same:password',

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
                $user->is_blocked = $request->status;
                if ($request->user_type == 'admin') {
                    $user->role = 1;
                }
                $user->password = Hash::make($request->cpassword);

                $user->save();
                session()->flash('success', 'User Created Successfully.');

                return  response()->json(
                    [
                        'status' => true,
                    ]
                );
            } else {

                $errors = $validator->errors();
                if ($errors->has('email')) {
                    return response()->json([
                        'exists' => true,
                        'message' => $errors->first('email')
                    ]);
                }
                return  response()->json(
                    [
                        'status' => false,
                        'errors' => $validator->errors()
                    ]
                );
            }
        }
    }
    public function edit(Request $request)
    {

        $user = User::find($request->id);

        if ($user->is_superadmin) {
            return back()->with('error', 'Changes Not Allowed For Superadmin.');
        }

        if (empty($user)) {
            session()->flash('error', 'User Not Found.');
            return redirect()->route('users.view');
        }
        if ($request->isMethod('post')) {

            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id . 'id',
                'phone' => 'required|numeric|digits:10',
            ];
            if ($request->password != '') {
                $rules['password'] = 'required|min:3';
            }
            $validator = Validator::make($request->all(), $rules, [
                'email.unique' => 'Account already exists with this email address.',
                'phone.digits' => 'Please enter a valid phone number.'
            ]);
            if ($validator->passes()) {

                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->email = $request->email;
                $user->updated_at = Carbon::now();
                $user->is_blocked = $request->status;

                if ($request->password != '') {
                    $user->password = Hash::make($request->password);
                }

                $user->save();

                session()->flash('success', 'User Data Updated Successfully.');
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'User Data Updated Successfully.'
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

        return response()->json([
            'status' => true,
            'message' => 'Data recieved for ' . $user->name,
            'name' => $user->name,
            'userId' => $user->id,
            'email' => $user->email,
            'phone' => $user->phone,
            'is_blocked' => $user->is_blocked

        ]);
    }
    public function destroy(Request $request)
    {
        $userId = $request->id;
        $user = User::find($userId);

        if (empty($user)) {
            session()->flash('error', 'User Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'User Not Found.'
                ]
            );
            return redirect()->route('users.view');
        } else {

            if ($user->is_superadmin) {
                return back()->with('error', 'Changes Not Allowed For Superadmin.');
            }

            if ($user->role == 1) {
                $user->permissions()->detach();
            }

            $user->delete();
            session()->flash('success', 'User Deleted Successfully.');
            return  response()->json(
                [
                    'status' => true,
                    'message' => 'User Deleted Successfully.'
                ]
            );
        }
    }
}
