<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\LeafCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVideo;
use App\Models\ProductRating;
use App\Models\ProductVariation;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        // Start building the query
        $products = Product::query();

        // Filter by SKU (supports partial match and multiple values separated by commas)
        if ($request->filled('sku')) {
            $skus = array_map('trim', explode(',', $request->sku));
            $products->where(function ($query) use ($skus) {
                foreach ($skus as $sku) {
                    $query->orWhere('sku', 'like', '%' . $sku . '%');
                }
            });
        }

        if ($request->filled('name')) {
            $names = array_map('trim', explode(',', $request->name));
            $products->where(function ($query) use ($names) {
                foreach ($names as $name) {
                    $query->orWhere('name', 'like', '%' . $name . '%');
                }
            });
        }

        if ($request->filled('designgroup')) {
            $names = array_map('trim', explode(',', $request->name));
            $products->where(function ($query) use ($names) {
                foreach ($names as $name) {
                    $query->orWhere('design_number', 'like', '%' . $name . '%');
                }
            });
        }


        if ($request->filled('d_type')) {
            $products->where('d_type', $request->d_type);
        }


        // Filter by categories (checkboxes support multiple values)
        if ($request->filled('category')) {
            $products->whereIn('category_id', $request->category);
        }

        // Filter by subcategories (checkboxes support multiple values)
        if ($request->filled('sub_category')) {
            $products->whereIn('sub_category_id', $request->sub_category);
        }

        // Filter by leaf categories (checkboxes support multiple values)
        if ($request->filled('leaf_category')) {
            $products->whereIn('leaf_category_id', $request->leaf_category);
        }

        // Filter by brand (checkboxes support multiple values)
        if ($request->filled('brand')) {
            $products->whereIn('brand_id', $request->brand);
        }

        // Eager load related models and paginate results
        $products = $products->with(['category', 'subCategory', 'leafCategory', 'brand'])->paginate(10);

        // Fetch necessary data for filters (categories, subcategories, leaf categories, brands)
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $leafCategories = LeafCategory::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'subCategories', 'leafCategories', 'brands'));
    }

    public function create()
    {

        $brands = Brand::get();

        $categories = Category::get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function edit($id)
    {

        $data['product'] = Product::with('variations', 'images')->find($id);

        if ($data['product'] == null) {
            abort(404);
        } else {

            $data['brands'] = Brand::get();

            $data['categories'] = Category::get();

            $data['subCategories'] = SubCategory::latest()->where('category_id', $data['product']->category_id)->get();

            $data['leafCategories'] = LeafCategory::latest()->where('sub_category_id', $data['product']->sub_category_id)->get();

            return view('admin.products.edit', $data);
        }
    }

    public function store(Request $request)
    {

        // return $request;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'leaf_category_id' => 'required|integer',
            'design_number' => 'nullable|string|max:255',
            'sku' => 'required|string|max:255|unique:products',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'has_variations' => 'nullable|boolean',

            'image_array' => 'required|array',
            'image_urls' => 'nullable|array',
            'index_image' => 'required',

            'meta_title' => 'required|string|max:255|unique:products',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'alt_image_text' => 'nullable|string|max:255',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->has_variations == 1) {
                $leaf = LeafCategory::find($request->leaf_category_id);

                if ($leaf) {
                    if (!empty($leaf->v1st) && empty($request->color)) {
                        $validator->errors()->add('color', $leaf->v1st . ' is required for this category.');
                    }

                    if (!empty($leaf->v2nd) && empty($request->size)) {
                        $validator->errors()->add('size', $leaf->v2nd . '  is required for this category.');
                    }
                }
            }
        });

        if (!$validator->passes()) {

            return  response()->json(
                [
                    'status' => 'validation',
                    'errors' => $validator->errors()
                ]
            );
        }

        $product = new Product();

        $product->name = $request->name;

        $product->slug = Str::slug($request->meta_title);

        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->leaf_category_id = $request->leaf_category_id;

        $product->brand_id = $request->brand_id;

        $product->design_number = $request->design_number;
        $product->sku = $request->sku;

        $product->price = $request->price;
        $product->compare_price = $request->c_price;

        $product->quantity = $request->quantity;

        $product->h_time = $request->h_time;
        $product->d_time = $request->d_time;
        $product->s_services = $request->s_services;

        $product->shipping = $request->shipping ?? 0;
        $product->shippingAddons = $request->shippingAddons ?? 0;

        $product->specs = json_encode($request->specs);

        $product->color = $request->color;
        $product->size = $request->size;

        $product->short_description = $request->short_description;
        $product->description = $request->description;

        $product->has_variations = $request->has_variations ?? 0;


        $product->is_featured = $request->is_featured;

        $product->status = $request->status;

        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;
        $product->alt_image_text = $request->alt_image_text;

        $product->save();

        // Product Images

        if (!empty($request->image_array)) {
            foreach ($request->image_array as $temp_image_id) {
                $sort_order = 0;
                if ($temp_image_id == $request->index_image) {
                    $sort_order = 1;
                }
                $tempImageInfo = ProductImage::find($temp_image_id);

                $tempImageInfo->product_id = $product->id;

                if ($sort_order == 1) {
                    $tempImageInfo->is_default = 1;
                } else {
                    $tempImageInfo->is_default = 0;
                }

                $tempImageInfo->save();
            }
        }

        if (!empty($request->image_urls)) {

            foreach ($request->image_urls as $imgUrl) {
                if (!empty($imgUrl)) {
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = $imgUrl;
                    $productImage->save();
                }
            }
        }

        if (!is_numeric($request->index_image)) {
            $productImages = $product->images();
            $imageToUpdate = $productImages->where('image', $request->index_image)->first();
            $imageToUpdate->is_default = 1;
            $imageToUpdate->save();
        }

        // if ($request->video != '') {
        //     $productVideo = new ProductVideo();
        //     $productVideo->product_id = $product->id;
        //     $productVideo->variation_id = 0;
        //     $productVideo->url = $request->video;
        //     $productVideo->save();
        // }


        session()->flash('success', 'Product Added Successfully.');

        if ($request->has_variations) {
            $redirect_url = route('variations.create', $product->id);
        } else {
            $redirect_url = route('products.index');
        }

        return  response()->json(
            [
                'status' => true,
                'message' => 'Product Added Successfully Redirecting You In 2s',
                'redirect_url' => $redirect_url,
            ]
        );
    }

    public function store_edit(Request $request, $id)
    {

        // return $request;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',

            'category_id' => 'required|integer',
            'sub_category_id' => 'nullable|integer',
            'leaf_category_id' => 'nullable|integer',
            'design_number' => 'nullable|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $id,
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',


            'quantity' => 'required|integer',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'has_variations' => 'nullable|boolean',
            'variations' => 'nullable|array',
            'images' => 'nullable|array',

            'meta_title' => 'required|string|max:255|unique:products,slug,' . $id,
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'alt_image_text' => 'nullable|string|max:255',
        ]);
        if (!$validator->passes()) {

            return  response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors()
                ]
            );
        }

        $specs = NULL;
        if($request->specs){
          $specs =  json_encode($request->specs);
        }

        $product = Product::find($id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->meta_title);

        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->leaf_category_id = $request->leaf_category_id;
        $product->brand_id = $request->brand;
        $product->design_number = $request->design_number;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->compare_price = $request->c_price;

        $product->specs = $specs;

        $product->color = $request->color;
        $product->size = $request->size;


        $product->h_time = $request->h_time;
        $product->d_time = $request->d_time;
        $product->s_services = $request->s_services;

        $product->shipping = $request->shipping;
        $product->shippingAddons = $request->shippingAddons ?? 0;

        $product->quantity = $request->quantity;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->is_featured = $request->is_featured;


        $product->status = $request->status;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;
        $product->alt_image_text = $request->alt_image_text;

        $product->save();


        if (!empty($request->image_urls)) {

            foreach ($request->image_urls as $imgUrl) {
                if (!empty($imgUrl)) {
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = $imgUrl;
                    $productImage->save();
                }
            }
        }

        if (!empty($request->index_image)) {
            // Reset all images without a variation_id to not default
            $product->images()->whereNull('variation_id')->update(['is_default' => 0]);

            if (is_numeric($request->index_image)) {
                // Handle case where index_image is an ID
                $image = $product->images()->find($request->index_image);

                if ($image) {
                    $image->update(['is_default' => 1]);
                }
            } else {
                // Handle case where index_image is an image name
                $image = $product->images()->where('image', $request->index_image)->first();

                if ($image) {
                    $image->update(['is_default' => 1]);
                }
            }
        }


        session()->flash('success', 'Product Updated Successfully.');

        return  response()->json(
            [
                'status' => true,
                'message' => 'Product Updated Successfully.'
            ]
        );
    }

    public function create_temp_img(Request $request, $productId = Null, $variationId = Null)
    {
        $image = $request->image;

        if (!empty($image)) {

            $ext = $image->getClientOriginalExtension();

            $newName = time() . uniqid() . '.' . $ext;

            $tempImage = new ProductImage();

            $tempImage->image = 'uploads/products/' . $newName;
            $tempImage->product_id = $productId ?? 0;
            $tempImage->variation_id = $variationId ?? Null;

            $tempImage->is_default = 0;

            $tempImage->save();
            $image->move(public_path() . '/uploads/products/', $newName);

            return response()->json([
                'status' => true,
                'image_id' =>  $tempImage->id,
                'message' => ' Image Uploaded Successfully.',
                'img_path' => 'uploads/products/' . $newName,
            ]);
        }
    }
    public function tempImagesDelete(Request $request)
    {

        $imgId = $request->imgId;

        $productImage = ProductImage::find($imgId);

        if (empty($productImage)) {

            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Associated Image Not Found.'
                ]
            );
        } else {

            $sourcePath = $productImage->image;

            if (File::exists($sourcePath)) {
                File::delete($sourcePath);
            }

            $productImage->delete();

            return  response()->json(
                [
                    'status' => true,
                    'message' => 'Image Deleted Successfully.'
                ]
            );
        }
    }
    public function getSubCategories($categoryId)
    {
        $category = Category::find($categoryId);

        if ($category) {
            $subCategories = $category->subCategories()->pluck('name', 'id');
            return response()->json($subCategories);
        }

        return response()->json([], 404);
    }
    public function getLeafCategories($subCategoryId)
    {
        $subCategory = SubCategory::find($subCategoryId);

        if ($subCategory) {
            $leafCategories = $subCategory->leafCategories()->pluck('name', 'id');
            return response()->json($leafCategories);
        }

        return response()->json([], 404);
    }

    public function destroy(Request $request)
    {
        $categoryId = $request->id;
        $product = Product::find($categoryId);

        if (empty($product)) {
            session()->flash('error', 'Product Data Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Product Data Not Found.'
                ]
            );
        }

        $product->variations()->delete();

        $productImages = ProductImage::where('product_id', $product->id)->get();

        if (!empty($productImages)) {
            foreach ($productImages as $value) {

                if (File::exists($value->image)) {
                    File::delete($value->image);
                }
            }

            ProductImage::where('product_id', $product->id)->delete();
        }

        $product->delete();

        session()->flash('success', 'Product Deleted Successfully.');
        return  response()->json(
            [
                'status' => true,
                'message' => 'Product Deleted Successfully.'
            ]
        );
    }

    public function reviews(Request $request)
    {

        if ($request->isMethod('post')) {
            $review = ProductRating::find($request->id);

            if ($review == null) {
                session()->flash('error', 'Review Data Not Found.');
                return  response()->json(
                    [
                        'status' => false,
                        'message' => 'Review Data Not Found.'
                    ]
                );
            }

            $review->status = $request->status;
            if ($request->status == 1) {
                $review->updated_at = Carbon::now();
                $review->save();
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'Approved successfully.',
                        'value' => '0',
                        'updated_At' => Carbon::parse($review->updated_at)->format('d M Y'),
                    ]
                );
            } else {
                $review->updated_at = null;
                $review->save();
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'This review is hidden now.',
                        'value' => '1',
                        'updated_At' => '0'
                    ]
                );
            }
        }


        $ratings = ProductRating::select('product_ratings.*', 'products.name as productName')->orderBy('product_ratings.created_at', 'DESC');
        $ratings = $ratings->leftJoin('products', 'products.id', 'product_ratings.product_id');

        $selectedStatus = '';
        if (!empty($request->get('status'))) {
            $status = $request->get('status');

            if ($status == 'active') {
                $status = 1;
            } else {
                $status = 0;
            }
            $ratings =  $ratings->where('product_ratings.status', '=', $status);
            if ($status == 1) {
                $status = 'active';
            } else {
                $status = 'hidden';
            }

            $selectedStatus = $status;
        }

        if (!empty($request->get('keyword'))) {

            $ratings = $ratings->where('product_ratings.email', 'like', '%' . $request->get('keyword') . '%');
            $ratings = $ratings->orwhere('product_ratings.username', 'like', '%' . $request->get('keyword') . '%');
        }

        $ratings = $ratings->paginate(12);


        return view('admin.products.reviews', ['ratings' => $ratings, 'selectedStatus' => $selectedStatus]);
    }

    public function reviewDestroy(Request $request)
    {
        $reviewId = $request->id;
        $review = ProductRating::find($reviewId);
        if (empty($review)) {
            session()->flash('error', 'Review Data Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Review Data Not Found.'
                ]
            );
        }

        $review->delete();

        session()->flash('success', 'Review Deleted Successfully.');
        return  response()->json(
            [
                'status' => true,
                'message' => 'Review Deleted Successfully.'
            ]
        );
    }
}
