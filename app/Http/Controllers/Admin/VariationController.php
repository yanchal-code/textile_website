<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VariationController extends Controller
{
    public function index($productId)
    {
        $product = Product::with('variations', 'variations.images')->findOrFail($productId);
        return view('admin.products.variations.index', ['product' => $product]);
    }

    public function create($id)
    {
        $data['product'] = Product::find($id);
        if ($data['product'] == null) {
            abort(404);
        }
        return view('admin.products.variations.create', $data);
    }

    public function edit($id)
    {

        $data['variation'] = ProductVariation::with('product', 'images')->find($id);

        if ($data['variation'] == null) {
            abort(404);
        } else {

            return view('admin.products.variations.edit', $data);
        }
    }


  public function store(Request $request, $id)
    {

        $product = Product::find($id);
        if ($product == null) {
            abort(404);
        }


        $validator = Validator::make(
            $request->all(),
            [
                'variations' => 'required|array|min:1',
                'variations.*.color' => 'nullable|string|max:50',
                'variations.*.size' => 'nullable|string|max:50',
                'variations.*.sku' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('products', 'sku'),
                    Rule::unique('product_variations', 'sku'),
                ],
                'variations.*.price' => 'nullable|numeric|min:0',
                'variations.*.c_price' => 'nullable|numeric|min:0',
                'variations.*.quantity' => 'nullable|integer|min:0',
                'variations.*.image_array' => 'nullable|array',
                'variations.*.image_array.*' => 'nullable|integer|exists:product_images,id',
                'variations.*.index_image' => 'nullable|integer|exists:product_images,id',
                'variations.*.video' => 'nullable|string|max:255',
            ],
            [
                'variations.required' => 'At least one variation is required.',
                'variations.*.sku.required' => 'Variation #:position → SKU is required.',
                'variations.*.sku.unique' => 'Variation #:position → SKU must be unique.',
                'variations.*.sku.max' => 'Variation #:position → SKU must not be longer than 255 characters.',
                'variations.*.sku.string' => 'Variation #:position → SKU must be a valid string.',

                'variations.*.price.numeric' => 'Variation #:position → Price must be numeric.',
                'variations.*.price.min' => 'Variation #:position → Price must be at least 0.',

                'variations.*.quantity.integer' => 'Variation #:position → Quantity must be an integer.',
                'variations.*.quantity.min' => 'Variation #:position → Quantity must be at least 0.',

                'variations.*.image_array.*.exists' => 'Variation #:position → One or more selected images do not exist.',
                'variations.*.index_image.exists' => 'Variation #:position → Selected index image does not exist.',
            ]
        );

        if (!$validator->passes()) {

            return  response()->json(
                [
                    'status' => 'validation',
                    'errors' => $validator->errors()
                ]
            );
        }

        foreach ($request->variations as $index => $variation) {

            $createdVariation = $product->variations()->create([
                'color' => $variation['color'] ?? null,
                'size' => $variation['size'] ?? null,
                'sku' => $variation['sku'] ?? null,
                'price' => $variation['price'] ?? 0,
                'c_price' => $variation['c_price'] ?? null,
                'quantity' => $variation['quantity'] ?? 0,
            ]);

            if (!empty($variation['image_array'])) {
                foreach ($variation['image_array'] as $temp_image_id) {
                    $sort_order = 0;
                    if ($temp_image_id == $variation['index_image']) {
                        $sort_order = 1;
                    }
                    $tempImageInfo = ProductImage::find($temp_image_id);
                    $tempImageInfo->product_id = $product->id;
                    $tempImageInfo->variation_id = $createdVariation->id;
                    if ($sort_order == 1) {
                        $tempImageInfo->is_default = 1;
                    }
                    $tempImageInfo->save();
                }
            }
        }

        session()->flash('success', 'Variations Added Successfully.');

        return  response()->json(
            [
                'status' => true,
                'message' => 'Variations Added Successfully Redirecting You In 2s',
                'redirect_url' => route('products.index'),
            ]
        );
    }

    public function store_edit(Request $request, $id)
    {

        $variation = ProductVariation::find($id);
        if ($variation == null) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'color' => 'required',
            'size' => 'required',
            'price' => 'required|numeric',
            'c_price' => 'nullable|numeric',
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products'),
                Rule::unique('product_variations')->ignore($id),
            ],
            'status' => 'required|numeric',

            'images' => 'nullable|array',
        ]);



        if (!$validator->passes()) {

            return  response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors()
                ]
            );
        }

        $variation = ProductVariation::find($id);

        $variation->update([
            'color' => $request->color,
            'size' => $request->size,
            'sku' => $request->sku,
            'price' => $request->price,
            'c_price' => $request->c_price,
            'quantity' => $request->quantity,
            'status' => $request->status,

        ]);


        if (!empty($request->image_urls)) {

            foreach ($request->image_urls as $imgUrl) {
                if (!empty($imgUrl)) {
                    $productImage = new ProductImage();
                    $productImage->product_id = $variation->product_id;
                    $productImage->variation_id = $variation->id;
                    $productImage->image = $imgUrl;
                    $productImage->save();
                }
            }
        }

        if (!empty($request->index_image)) {
            // Reset all images to not default
            $variation->images()->update(['is_default' => 0]);

            if (is_numeric($request->index_image)) {
                // Handle case where index_image is an ID
                $image = $variation->images()->find($request->index_image);

                if ($image) {
                    $image->update(['is_default' => 1]);
                }
            } else {
                // Handle case where index_image is an image name
                $image = $variation->images()->where('image', $request->index_image)->first();

                if ($image) {
                    $image->update(['is_default' => 1]);
                }
            }
        }


        session()->flash('success', 'Variation Updated Successfully.');

        return  response()->json(
            [
                'status' => true,
                'message' => 'Variation Updated Successfully.'
            ]
        );
    }

    public function deleteVariation(Request $request)
    {
        $varationID = $request->id;
        $variation = ProductVariation::find($varationID);
        if (empty($variation)) {
            session()->flash('error', 'Variation Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Variation Not Found.'
                ]
            );
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Variation Not Found.'
                ]
            );
        } else {

            $images = ProductImage::where('variation_id', $varationID)->get();

            foreach ($images as $image) {
                if (File::exists($image->image)) {
                    File::delete($image->image);
                }
            }

            ProductImage::where('variation_id', $varationID)->delete();

            $variation->delete();

            return  response()->json(
                [
                    'status' => true,
                    'message' => 'Variation Deleted Successfully.'
                ]
            );
        }
    }
}
