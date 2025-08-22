<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        DB::enableQueryLog();

        $selectedStatus = '';
        // $selectedBrand = '';

        $orders = Order::latest('orders.created_at')
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->select(
                'orders.*',
                'payments.id as payment_id',
                'payments.gateway as payment_gateway',
                'payments.gateway_email as paypal_email',
                'payments.gateway_payer_id as paypal_payer_id',
                'payments.phonepe_transaction_id',
                'payments.amount as payment_amount',
                'payments.currency as payment_currency',
                'payments.status as payment_status',
                'payments.created_at as payment_created_at'
            );

        if (!empty($request->get('status'))) {
            $status = $request->get('status');
            $orders =  $orders->where('orders.status', '=', $status);
            $selectedStatus = $status;
        }

        if (!empty($request->get('keyword'))) {

            $orders = $orders->orwhere('orders.first_name', 'like', '%' . $request->get('keyword') . '%');

            $orders = $orders->orwhere('orders.email', 'like', '%' . $request->get('keyword') . '%');

            $orders = $orders->orwhere('orders.orderId', 'like', '%' . $request->get('keyword') . '%');

            $orders = $orders->orwhere('orders.mobile', 'like', '%' . $request->get('keyword') . '%');
        }


        $orders = $orders->paginate(10);
        $data['orders'] = $orders;
        $data['selectedStatus'] = $selectedStatus;

        return view('admin.orders.list', $data);
    }
    public function detail($id)
    {
        $order = Order::where('orders.id', $id)
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->select(
                'orders.*',
                'payments.gateway as payment_gateway',
                'payments.gateway_payer_id as paypal_payer_id',
                'payments.phonepe_transaction_id',
            )
            ->first();


        if ($order == Null) {
            abort(404);
        }


        $orderItems = OrderItem::where('order_id', $id)
            ->orderBy('name', 'DESC')
            ->get();

        $data['orderItems'] = $orderItems;
        $data['order'] = $order;
        return view('admin.orders.detail', $data);
    }

    public  function updateStatus($id, Request $request)
    {
        $order = order::find($id);

        if ($order == Null) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'shipped_date' => 'required_if:status,shipped',
        ]);
        if ($request->isMethod('post')) {

            if ($validator->passes()) {

                if ($request->shipped_date != '') {

                    $shipped_date = $request->shipped_date;

                    $order->shipped_date = $shipped_date;
                    $order->shippment_detail = $request->shippment_detail . '|' . $request->shippment_detail2;
                }
                $order->status = $request->status;

                $order->save();
                session()->flash('success', 'Order updated successfully.');
                return response()->json([
                    'status' => true,
                ]);
            } else {
                return  response()->json(
                    [
                        'status' => false,
                        'errors' => $validator->errors()
                    ]
                );
            }
        }
    }

    public function sendInvoiceEmail(Request $request, $orderId)
    {
        orderEmail($orderId, $request->userType);
        session()->flash('success', 'Order invoice sent successfully.');
        return response()->json([
            'status' => true,
        ]);
    }
}
