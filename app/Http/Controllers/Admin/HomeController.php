<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class HomeController extends Controller
{
    public function home()
    {


        $hotSellingProducts = OrderItem::select('sku', DB::raw('SUM(qty) as total_quantity'))
            ->groupBy('sku')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        $topCustomers = Order::join('users', 'orders.user_id', '=', 'users.id')
            ->where('status', '!=', 'cancelled')
            ->select('users.id', 'users.name', 'users.email', DB::raw('SUM(orders.grand_total) as total_grand_total'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_grand_total')
            ->take(5) // top 5 customers
            ->get();
        $data['topCustomers'] = $topCustomers;
        $topOrders = Order::orderBy('grand_total', 'DESC')->where('status', '!=', 'cancelled')->take(10)->get();

        $topRecentOrders = Order::latest()->where('status', '!=', 'cancelled')->take(10)->get();
        $data['topOrders'] = $topOrders;

        $data['topRecentOrders'] = $topRecentOrders;

        $outOfStockProducts = Product::orderBy('sku', 'ASC')
            ->where('status', '=', 1)
            ->where('quantity', '=', 0)
            ->get();
        $data['outOfStockProducts'] = $outOfStockProducts;

        $totalProductSold = OrderItem::sum('qty');

        $totalProduct = Product::where('status', 1)->count();

        $data['totalProduct'] = $totalProduct;
        $data['totalProductSold'] = $totalProductSold;

        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $data['totalOrders'] =  $totalOrders;

        $totalNewOrders = Order::where('status', 'pending')->count();
        $data['totalNewOrders'] =  $totalNewOrders;

        $totalSales = Order::where('status', '!=', 'cancelled')->sum('grand_total');
        $data['totalSales'] =  $totalSales;

        $data['hotSellingProducts'] =  $hotSellingProducts;


        // Biggest order
        $biggestOrder = Order::orderBy('grand_total', 'DESC')->where('status', '!=', 'cancelled')->first();
        $data['biggestOrder'] =  $biggestOrder;
        // $lowestOrder = order::orderBy('grand_total', 'ASC')->where('status', '!=', 'cancelled')->first();
        // $data['lowestOrder'] =  $lowestOrder;

        $data['totalUsers'] = User::where('role', 2)->count();

        $totalUsersActive = User::where('is_blocked', 0)->where('role', 2)->count();
        $totalUsersBlocked = User::where('is_blocked', 1)->where('role', 2)->count();

        $data['totalUsersActive'] = $totalUsersActive;
        $data['totalUsersBlocked'] = $totalUsersBlocked;



        $startOfMonth = Carbon::now()->startOfMonth();
        $currentDate = Carbon::now();

        $totalSalesThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('grand_total');
        $data['totalSalesThisMonth'] = $totalSalesThisMonth;

        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $totalSalesLastMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfLastMonth)
            ->whereDate('created_at', '<=', $endOfLastMonth)
            ->sum('grand_total');


        $data['totalSalesLastMonth'] = $totalSalesLastMonth;


        return view('admin.home', $data);
    }
    public function settings(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->update($request);
        }

        return view('admin.settings');
    }

    public function update(Request $request)
    {

        $allowedKeys = Setting::pluck('key')->toArray();

        $inputs = $request->except(['_token', 'logoImage', 'faviconImage']);

        $filteredInputs = collect($inputs)
            ->only($allowedKeys)
            ->toArray();

        $inputs = $filteredInputs;

        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = public_path('/uploads/settings/' . $logoName);

            $oldLogo = Setting::where('key', 'logo')->first();

            if ($oldLogo && File::exists(public_path($oldLogo->value))) {
                File::delete(public_path($oldLogo->value));
            }

            $logo->move(public_path('/uploads/settings/'), $logoName);

            Setting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => 'uploads/settings/' . $logoName]
            );
        }

        if ($request->hasFile('faviconImage')) {
            $favicon = $request->file('faviconImage');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $faviconPath = public_path('/uploads/settings/' . $faviconName);

            $oldFavicon = Setting::where('key', 'faviconImage')->first();
            if ($oldFavicon && File::exists(public_path($oldFavicon->value))) {
                File::delete(public_path($oldFavicon->value));
            }

            $favicon->move(public_path('/uploads/settings/'), $faviconName);

            Setting::updateOrCreate(
                ['key' => 'faviconImage'],
                ['value' => 'uploads/settings/' . $faviconName]
            );
        }

        config(['settings' => Setting::pluck('value', 'key')->toArray()]);

        return response()->json([
            'status' => true,
            'reload' => true,
            'message' => 'Settings updated successfully'
        ]);
    }


    public function fetchGroup(Request $request)
    {
        $group = $request->group;
        $settings = Setting::where('group', $group)->get();

        return response()->json($settings);
    }


    public function slug(Request $request)
    {
        $slug = '';
        if (!empty($request->title)) {
            $slug = Str::slug($request->title);
        }
        return  response()->json(
            [
                'status' => true,
                'slug' => $slug
            ]
        );
    }
}
