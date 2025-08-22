<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        $categories = DiscountCoupon::latest();
        if (!empty($request->get('keyword'))) {
            if (!empty($request->get('search_by'))) {
                $categories = $categories->where($request->get('search_by'), 'like', '%' . $request->get('keyword') . '%');
            } else {
                $categories = $categories->where('code', 'like', '%' . $request->get('keyword') . '%');
            }
        }
        $categories = $categories->paginate(10);

        return view('admin.discount.list', compact('categories'));
    }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'type' => 'required',
                'min_amount' => 'required_if:type,freeShipping',
                'discount_amount' => 'required_if:type,fixed,percent',
                'status' => 'required',
            ]);

            if (!empty($request->starts_at)) {
                $now = Carbon::now();

                // Parse the datetime-local format (Y-m-d\TH:i)
                $startsAt = Carbon::createFromFormat('Y-m-d\TH:i', $request->starts_at);

                // Check if the start date is in the past
                if ($startsAt->lte($now)) {
                    return response()->json([
                        'status' => 'dateTime',
                        'error' => 'dateTime',
                        'message' => 'Start DateTime cannot be less than the current DateTime.'
                    ]);
                }

                // Parse the expiry datetime
                $expiresAt = Carbon::createFromFormat('Y-m-d\TH:i', $request->expires_at);

                // Check if the expiry date is after the start date
                if ($expiresAt->lte($startsAt)) {
                    return response()->json([
                        'status' => 'dateTime',
                        'message' => 'Expiry DateTime cannot be less than the Starting DateTime.'
                    ]);
                }
            }




            if ($validator->passes()) {

                $coupon = new DiscountCoupon();
                $coupon->name = $request->name;
                $coupon->code = $request->code;
                $coupon->description = $request->description;
                $coupon->max_uses = $request->max_uses;

                $coupon->max_uses_user = $request->max_uses_user;
                $coupon->type = $request->type;
                $coupon->discount_amount = $request->discount_amount;
                $coupon->min_amount = $request->min_amount;
                $coupon->status = $request->status;

                $coupon->starts_at = $request->starts_at;
                $coupon->expires_at = $request->expires_at;

                $coupon->save();

                session()->flash('success', 'Coupon Code Added Successfully.');
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'Coupon Code Added Successfully.'
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
        return view('admin.discount.create');
    }

    public function edit($discount, Request $request)
    {
        $code = DiscountCoupon::find($discount);

        if (empty($code)) {
            session()->flash('error', 'Discount Data Not Found.');
            return redirect()->route('discount.view');
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'type' => 'required',
                'min_amount' => 'required_if:type,freeShipping',
                'discount_amount' => 'required_if:type,fixed,percent',
                'status' => 'required',
            ]);

            if (!empty($request->starts_at)) {
                $now = Carbon::now();

                // Parse the datetime-local format (Y-m-d\TH:i)
                $startsAt = Carbon::createFromFormat('Y-m-d\TH:i', $request->starts_at);

                // Check if the start date is in the past
                if ($startsAt->lte($now)) {
                    return response()->json([
                        'status' => 'dateTime',
                        'error' => 'dateTime',
                        'message' => 'Start DateTime cannot be less than the current DateTime.'
                    ]);
                }

                // Parse the expiry datetime
                $expiresAt = Carbon::createFromFormat('Y-m-d\TH:i', $request->expires_at);

                // Check if the expiry date is after the start date
                if ($expiresAt->lte($startsAt)) {
                    return response()->json([
                        'status' => 'dateTime',
                        'message' => 'Expiry DateTime cannot be less than the Starting DateTime.'
                    ]);
                }
            }

            if ($validator->passes()) {

                $coupon = $code;
                $coupon->name = $request->name;
                $coupon->code = $request->code;
                $coupon->description = $request->description;
                $coupon->max_uses = $request->max_uses;

                $coupon->max_uses_user = $request->max_uses_user;
                $coupon->type = $request->type;
                $coupon->discount_amount = $request->discount_amount;

                $coupon->min_amount = $request->min_amount;
                $coupon->status = $request->status;

                $coupon->starts_at = $request->starts_at;
                $coupon->expires_at = $request->expires_at;

                $coupon->save();

                session()->flash('success', 'Coupon Code Updated Successfully.');
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'Coupon Code Updated Successfully.'
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

        return view('admin.discount.edit', compact('code'));
    }

    public function destroy(Request $request)
    {
        $codeId = $request->id;
        $code = DiscountCoupon::find($codeId);

        if (empty($code)) {
            session()->flash('error', 'Coupon Details Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Coupon Details Not Found.'
                ]
            );
        } else {

            $code->delete();

            session()->flash('success', 'Coupon Deleted Successfully.');
            return  response()->json(
                [
                    'status' => true,
                    'message' => 'Coupon Deleted Successfully.'
                ]
            );
        }
    }
}
