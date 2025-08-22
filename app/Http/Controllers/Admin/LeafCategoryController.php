<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory as Category;
use App\Models\LeafCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class LeafCategoryController extends Controller
{

    public function index(Request $request)
    {


        $leafCategories = LeafCategory::with('subCategory')
            ->latest('id');

        if (!empty($request->get('keyword'))) {
            $leafCategories = $leafCategories->where('leaf_categories.name', 'like', '%' . $request->get('keyword') . '%');
        }

        $leafCategories = $leafCategories->paginate(10);

        return view('admin.leafCategory.list', compact('leafCategories'));
    }

    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required',
                'category_id' => 'required',
                'status' => 'required',
                'spec_fields' => 'required',

            ], [
                'spec_fields.required' => 'Please add atleast one specification field'
            ]);

            if ($validator->passes()) {


                $imgPath = '';

                if ($request->hasFile('categoryImage')) {

                    $img_temp = $request->file('categoryImage');
                    if ($img_temp->isValid()) {
                        $extension = $img_temp->getClientOriginalExtension();
                        $imageName =  time() . uniqid() . '.' . $extension;

                        $img_temp->move(public_path('uploads/category'), $imageName);

                        $imgPath = 'uploads/category/' . $imageName;
                    }
                }

                $variation_variable_1st =  $request->v1st ?? '';
                $variation_variable_2nd =  $request->v2nd ?? '';

                if ($variation_variable_1st == '' && $variation_variable_2nd != '') {
                    $variation_variable_1st = $variation_variable_2nd;
                    $variation_variable_2nd = '';
                }

                $leafCategory = LeafCategory::updateOrCreate(
                    [
                        'sub_category_id' => $request->category_id,
                        'slug' => $request->slug,
                    ],
                    [
                        'name' => $request->name,
                        'image' => $imgPath,
                        'status' => $request->status,
                        'spec_fields' => json_encode($request->spec_fields),
                        'v1st' => $variation_variable_1st,
                        'v2nd' => $variation_variable_2nd,

                    ]
                );


                session()->flash('success', 'Sub Category Data Captured.');
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'Sub Category Added Successfully.'
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

        $subCategories = SubCategory::orderBy('name', 'ASC')->get();
        return view('admin.leafCategory.create', compact('subCategories'));
    }

    public function edit($leafCategoryId, Request $request)
    {
        $leafCategory = LeafCategory::find($leafCategoryId);


        if (empty($leafCategory)) {
            return redirect()->route('leafCategories.view')->with('error', 'Leaf Category Not Found');;
        }


        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required',
                'category_id' => 'required',
                'status' => 'required',
                'spec_fields' => 'required',

            ], [
                'spec_fields.required' => 'Please add atleast one specification field'
            ]);
            if ($validator->passes()) {

                if ($request->hasFile('categoryImage')) {

                    $img_temp = $request->file('categoryImage');

                    if ($img_temp->isValid()) {

                        $extension = $img_temp->getClientOriginalExtension();
                        $imageName =  time() . uniqid() . '.' . $extension;

                        $img_temp->move(public_path('uploads/category'), $imageName);

                        $imgPath = 'uploads/category/' . $imageName;

                        if (File::exists($leafCategory->image)) {
                            File::delete($leafCategory->image);
                        }

                        $leafCategory->image = $imgPath;
                    }
                }

                $variation_variable_1st =  $request->v1st ?? '';
                $variation_variable_2nd =  $request->v2nd ?? '';

                if ($variation_variable_1st == '' && $variation_variable_2nd != '') {
                    $variation_variable_1st = $variation_variable_2nd;
                    $variation_variable_2nd = '';
                }

                $leafCategory->name = $request->name;
                $leafCategory->slug = $request->slug;
                $leafCategory->status = $request->status;
                $leafCategory->sub_category_id = $request->category_id;
                $leafCategory->spec_fields = json_encode($request->spec_fields);
                $leafCategory->v1st = $variation_variable_1st;
                $leafCategory->v2nd = $variation_variable_2nd;

                $leafCategory->save();

                session()->flash('success', 'Leaf Category Updated Successfully.');
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'Leaf Category Updated Successfully.'
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


        $subCategories = SubCategory::orderBy('name', 'ASC')->get();
        return view('admin.leafCategory.edit', compact('leafCategory', 'subCategories'));
    }

    public function destroy(Request $request)
    {
        $leafCategoryId = $request->id;

        $leafCategory = LeafCategory::find($leafCategoryId);



        if (empty($leafCategory)) {
            session()->flash('error', 'Leaf Category Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Leaf Category Not Found.'
                ]
            );
            return redirect()->route('leafcategories.view');
        }


        $pruducts = Product::where('leaf_category_id',  $leafCategory->id)->get();

        foreach ($pruducts as $value) {
            $productImages = ProductImage::where('product_id', $value->id)->get();
            foreach ($productImages as  $pimage) {
                if (File::exists($pimage->image)) {
                    File::delete($pimage->image);
                }
            }
            $productImages = ProductImage::where('product_id', $value->id)->delete();

            $value->variations()->delete();
        }

        Product::where('leaf_category_id', $leafCategory->id)->delete();
        if (File::exists($leafCategory->image)) {
            File::delete($leafCategory->image);
        }
        $leafCategory->delete();


        session()->flash('success', 'Leaf Category Deleted Successfully.');
        return  response()->json(
            [
                'status' => true,
                'message' => 'Leaf Category Deleted Successfully.'
            ]
        );
    }

    public function specs(Request $request)
    {
        $leafCategoryId = $request->id;
        $leafCategory = LeafCategory::find($leafCategoryId);
        if (!$leafCategory) {
            return response()->json([], 404);
        }

        $specFields = is_string($leafCategory->spec_fields)
            ? json_decode($leafCategory->spec_fields, true)
            : $leafCategory->spec_fields;

        // return response()->json($specFields);

        return response()->json([
            'v1st' => $leafCategory->v1st ?? false,
            'v2nd' => $leafCategory->v2nd ?? false,
            'specFields' => $specFields
        ]);
    }
}
