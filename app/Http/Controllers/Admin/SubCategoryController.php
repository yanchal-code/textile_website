<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\LeafCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::with('category')
            ->latest('id');
        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
        }
        $subCategories = $subCategories->paginate(10);

        return view('admin.subCategory.list', compact('subCategories'));
    }
    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required',
                'category_id' => 'required',
                'status' => 'required'

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

                $subCategory = SubCategory::updateOrCreate(
                    [
                        'category_id' => $request->category_id,
                        'slug' => $request->slug,
                    ],
                    [
                        'name' => $request->name,
                        'image' => $imgPath,
                        'status' => $request->status,
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
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.subCategory.create', compact('categories'));
    }
    public function edit($SubCategoryId, Request $request)
    {
        $sub_category = SubCategory::find($SubCategoryId);

        if (empty($sub_category)) {
            session()->flash('error', 'Sub Category Not Found.');
            return redirect()->route('subCategories.view');
        } else {
            if ($request->isMethod('post')) {

                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'slug' => 'required',
                    'category_id' => 'required',
                    'status' => 'required'

                ]);
                if ($validator->passes()) {

                    if ($request->hasFile('categoryImage')) {

                        $img_temp = $request->file('categoryImage');

                        if ($img_temp->isValid()) {

                            $extension = $img_temp->getClientOriginalExtension();
                            $imageName =  time() . uniqid() . '.' . $extension;

                            $img_temp->move(public_path('uploads/category'), $imageName);

                            $imgPath = 'uploads/category/' . $imageName;

                            if (File::exists($sub_category->image)) {
                                File::delete($sub_category->image);
                            }

                            $sub_category->image = $imgPath;
                        }
                    }

                    $sub_category->name = $request->name;
                    $sub_category->slug = $request->slug;
                    $sub_category->status = $request->status;
                    $sub_category->category_id = $request->category_id;
                    $sub_category->save();

                    session()->flash('success', 'Sub Category Updated Successfully.');
                    return  response()->json(
                        [
                            'status' => true,
                            'message' => 'Sub Category Updated Successfully.'
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
        }

        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.subCategory.edit', compact('sub_category', 'categories'));
    }
    public function destroy(Request $request)
    {
        $SubCategoryId = $request->id;
        $SubCategory = SubCategory::find($SubCategoryId);
        if (empty($SubCategory)) {
            session()->flash('error', 'Sub Category Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Sub Category Not Found.'
                ]
            );
            return redirect()->route('subCategories.view');
        } else {

            $pruducts = Product::where('sub_category_id',  $SubCategory->id)->get();

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

            Product::where('sub_category_id', $SubCategory->id)->delete();

            $SubCategory->leafCategories()->delete();

            if (File::exists($SubCategory->image)) {
                File::delete($SubCategory->image);
            }

            $SubCategory->delete();

            session()->flash('success', 'Sub Category Deleted Successfully.');
            return  response()->json(
                [
                    'status' => true,
                    'message' => 'Sub Category Deleted Successfully.'
                ]
            );
        }
    }
}
