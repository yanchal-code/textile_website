<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Setting;
use App\Models\LeafCategory;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\ProductVariation;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null, $leafCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $leafCategorySelected = '';
        $brandsArray = [];
        $sortBy = '';
        $categorySelectedName = '';
        $subCategorySelectedName  = '';
        $leafCategorySelectedName = '';

        $products = Product::where('status', 1);

        if ($request->get('search') != '') {
            $products = $products->where('name', 'like', '%' . $request->get('search') . '%');
        }

        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $products = $products->where('category_id', $category->id);
                $categorySelected = $category->id;
                $categorySelectedName = $category->name;
            } else {
                abort(404);
            }
        }
        if (!empty($subCategorySlug)) {
            $sub_category = SubCategory::where('slug', $subCategorySlug)->first();
            if ($sub_category) {
                $products = $products->where('sub_category_id', $sub_category->id);
                $subCategorySelected = $sub_category->id;
                $subCategorySelectedName = $sub_category->name;
            } else {
                abort(404);
            }
        }

        if (!empty($leafCategorySlug)) {
            $leaf_category = LeafCategory::where('slug', $leafCategorySlug)->first();
            if ($leaf_category) {
                $products = $products->where('leaf_category_id', $leaf_category->id);
                $leafCategorySelected = $leaf_category->id;
                $leafCategorySelectedName = $leaf_category->name;
            } else {
                abort(404);
            }
        }



        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }


        $priceMin = intval($request->get('price_min'));
        $priceMax = intval($request->get('price_max'));

        if (Auth::check() && Auth::user()->region === 'India') {

            $$priceMin =  $priceMin > 0 ? intval($request->get('price_min')) / Setting::latest()->first()->conversion_rate_usd_to_inr : 0;

            $priceMax = intval($request->get('price_max')) / Setting::latest()->first()->conversion_rate_usd_to_inr;
        } else {
            $maxPrice = Product::max('price');
        }


        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            $products->whereBetween('price', [$priceMin, $priceMax]);
            $products->where('is_bidding', 0);
        }


        if ($request->get('sort')) {

            $sortBy = $request->get('sort');

            if ($request->get('sort') == 'latest') {
                $products =  $products->orderBy('updated_at', 'DESC');
            } elseif ($request->get('sort') == 'price_desc') {
                $products =  $products->orderBy('price', 'DESC');
            } elseif ($request->get('sort') == 'price_asc') {
                $products =  $products->orderBy('price', 'ASC');
            } else {
                $products =  $products->orderBy('updated_at', 'DESC');
            }
        } else {
            $products =  $products->orderBy('updated_at', 'DESC');
        }

        $totalResults = $products->count();

        $products =  $products->latest()
            // ->where('is_bidding', false)
            ->paginate(20);

        $data['products'] = $products;

        $brands = Brand::latest()->where('status', 1)->get();

        $data['brands'] = $brands;

        $data['categorySelectedName'] = $categorySelectedName;
        $data['subCategorySelectedName'] = $subCategorySelectedName;
        $data['leafCategorySelectedName'] = $leafCategorySelectedName;

        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['leafCategorySelected'] = $leafCategorySelected;

        $data['priceMin'] = intval($request->get('price_min'));
        $data['priceMax'] = intval($request->get('price_max'));

        $data['sortBy'] = $sortBy;


        return view('frontend_new.shop', $data);
    }

    public function productQuickView(Request $request)
    {
        $sku = $request->sku;
        $variation = ProductVariation::where('sku', $sku)
            ->with(['product' => function ($query) {
                $query->with(['images', 'defaultImage', 'category', 'subCategory', 'leafCategory', 'brand']);
            }])
            ->first();

        if ($variation) {

            $product = $variation->product;

            if (!$product) {
                abort(404, 'Product not found.');
            }

            $data['defaultSku'] = $product->sku;
            $data['defaultColor'] = $product->color;
            $data['defaultSize'] = $product->size;

            $product->sku = $variation->sku;
            $product->price = $variation->price;
            $product->quantity = $variation->quantity;
            $product->color = $variation->color;
            $product->size = $variation->size;
        } else {
            $product = Product::where('sku', $sku)
                ->with(['images', 'defaultImage', 'category', 'subCategory', 'leafCategory', 'brand', 'variations'])
                ->first();

            if (!$product) {
                abort(404, 'Product not found.');
            }
            $data['defaultSku'] = $product->sku;
            $data['defaultColor'] = $product->color;
            $data['defaultSize'] = $product->size;

            $data['selectedVariation'] = null;

            $data['variations'] = $product->variations()->where('status', 1)->get();
        }
        $data['product'] = $product;

        return view('front.partials.quickView', $data);
    }

    public function product($slug, $sku = null)
    {

        $variation = ProductVariation::where('sku', $sku)
            ->orWhere('sku', $slug)
            ->with(['product' => function ($query) {
                $query->with(['images', 'defaultImage', 'category', 'subCategory', 'leafCategory', 'brand', 'product_ratings']);
            }])
            ->first();

        if ($variation) {

            $product = $variation->product;

            $product->videos = $variation->videos;

            if (!$product) {
                abort(404, 'Product not found.');
            }


            $data['defaultSku'] = $product->sku;
            $data['defaultColor'] = $product->color;
            $data['defaultSize'] = $product->size;

            $product->sku = $variation->sku;
            $product->price = $variation->price;
            $product->compare_price = $variation->c_price;
            $product->quantity = $variation->quantity;
            $product->color = $variation->color;
            $product->size = $variation->size;
        } else {
            $product = Product::where('slug', $slug)
                ->orWhere('sku', $sku)
                ->orWhere('sku', $slug)
                ->with(['images', 'defaultImage', 'category', 'subCategory', 'leafCategory', 'brand', 'variations', 'product_ratings'])
                ->first();

            if (!$product) {
                abort(404, 'Product not found.');
            }
            $data['defaultSku'] = $product->sku;
            $data['defaultColor'] = $product->color;
            $data['defaultSize'] = $product->size;

            $data['selectedVariation'] = null;
            $data['variations'] = $product->variations()->where('status', 'active')->get();
        }

        $data['product'] = $product;

        $data['relatedProducts'] = Product::where('status', 1)
            ->where('leaf_category_id', $product->leaf_category_id)
            ->where('id', '!=', $product->id) // Exclude current product
            ->with('defaultImage')
            ->get()
            ->shuffle()
            ->take(8);

        return view('frontend_new.product-details', $data);
    }

    public function saveReview($productId, Request $request)
    {
        $product = Product::find($productId);

        if ($product == null) {
            return  response()->json(
                [
                    'status' => 'notFound',
                    'message' => 'Sorry, Product details not found.'
                ]
            );
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email:min:5|max:50',
            'rating' => 'required|numeric',
            'review' => 'required:min:3',
        ], [
            'rating.required' => 'Please give a rating.',

        ]);

        if ($validator->fails()) {
            return  response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors()
                ]
            );
        }

        if (ProductRating::where('email', $request->email)->count() >= 10) {
            return  response()->json(
                [
                    'status' => true,
                    'message' => 'you already submited your review for this product twice, Wait for some time.'
                ]
            );
        }
        $productRating = new ProductRating();
        $productRating->username = $request->name;
        $productRating->product_id = $product->id;
        $productRating->email = $request->email;
        $productRating->rating = $request->rating;
        $productRating->comment = $request->review;
        $productRating->save();

        return  response()->json(
            [
                'status' => true,
                'message' => 'ThankYou, Your review submitted Successfully.',
                'reload' => true
            ]
        );
    }

    public function saveReviewOrder(Request $request)
    {

        $product = Product::where('sku', $request->sku)->first();
        if ($product == null) {
            $variation = ProductVariation::where('sku', $request->sku)->first();
            $product = $variation->product;
        }

        if ($product == null) {
            return  response()->json(
                [
                    'status' => 'notFound',
                    'message' => 'Sorry, Product details not found.'
                ]
            );
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email:min:5|max:50',
            'rating' => 'required|numeric',
            'title' => 'required:min:3',
            'review' => 'required:min:3',
        ], [
            'rating.required' => 'Please give a rating.',

        ]);

        if ($validator->fails()) {
            return  response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors()
                ]
            );
        }

        if (ProductRating::where('email', $request->email)->count() >= 10) {
            return  response()->json(
                [
                    'status' => true,
                    'message' => 'you already submited your review for this product twice, Wait for some time.'
                ]
            );
        }
        $productRating = new ProductRating();
        $productRating->username = $request->name;
        $productRating->product_id = $product->id;
        $productRating->email = $request->email;
        $productRating->rating = $request->rating;
        $productRating->title = $request->title;
        $productRating->comment = $request->review;

        $productRating->save();

        return  response()->json(
            [
                'status' => true,
                'message' => 'ThankYou, Your review submitted Successfully.',
                'reload' => true
            ]
        );
    }

    public function partial(Request $request)
    {
        $sku = $request->sku;
        $variation = ProductVariation::where('sku', $sku)
            ->with(['product' => function ($query) {
                $query->with(['images', 'defaultImage', 'category', 'subCategory', 'leafCategory', 'brand']);
            }])
            ->first();

        if ($variation) {

            $product = $variation->product;

            if (!$product) {
                abort(404, 'Product not found.');
            }


            $data['defaultSku'] = $product->sku;
            $data['defaultColor'] = $product->color;
            $data['defaultSize'] = $product->size;

            $product->sku = $variation->sku;
            $product->price = $variation->price;
            $product->compare_price = $variation->c_price;
            $product->quantity = $variation->quantity;
            $product->color = $variation->color;
            $product->size = $variation->size;
        } else {
            $product = Product::where('sku', $sku)
                ->with(['images', 'defaultImage', 'category', 'subCategory', 'leafCategory', 'brand', 'variations'])
                ->first();

            if (!$product) {
                abort(404, 'Product not found.');
            }
            $data['defaultSku'] = $product->sku;
            $data['defaultColor'] = $product->color;
            $data['defaultSize'] = $product->size;

            $data['selectedVariation'] = null;

            $data['variations'] = $product->variations()->where('status', 1)->get();
        }
        $data['product'] = $product;

        return view('front.partials.product', $data);
    }
}
