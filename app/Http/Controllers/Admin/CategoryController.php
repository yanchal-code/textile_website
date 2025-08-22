<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();
        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $categories = $categories->paginate(10);

        return view('admin.category.list', compact('categories'));
    }


    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required|unique:categories',
                'categoryImage' => 'required|image'
            ]);
            if ($validator->passes()) {

                if ($request->hasFile('categoryImage')) {

                    $img_temp = $request->file('categoryImage');
                    if ($img_temp->isValid()) {
                        $extension = $img_temp->getClientOriginalExtension();
                        $imageName =  time() . uniqid() . '.' . $extension;


                        // $dPath = public_path() . '/uploads/category/' . $imageName;
                        // $image = $image->resizeDown(1100, 700);
                        // $image->toJpeg(80)->save($dPath);

                        $img_temp->move(public_path('uploads/category'), $imageName);

                        $imgPath = 'uploads/category/' . $imageName;
                    }
                }

                $category = new category();
                $category->name = $request->name;
                $category->slug = $request->slug;
                $category->status = $request->status;
                $category->image = $imgPath;
                $category->save();

                session()->flash('success', 'Category Added Successfully.');
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'Category Added Successfully.'
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
        return view('admin.category.create');
    }
    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            session()->flash('error', 'Category Not Found.');
            return redirect()->route('categories.view');
        } else {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'slug' => 'required|unique:categories,slug,' . $category->id . 'id',
                ]);
                if ($validator->passes()) {
                    if ($request->hasFile('categoryImage')) {
                        $manager = new ImageManager(new Driver());
                        $img_temp = $request->file('categoryImage');
                        if ($img_temp->isValid()) {

                            $extension = $img_temp->getClientOriginalExtension();
                            $imageName =  time() . uniqid() . '.' . $extension;
                            $image = $manager->read($img_temp);
                            $dPath = public_path() . '/uploads/category/' . $imageName;
                            $imgPath = 'uploads/category/' . $imageName;
                            $image->toJpeg(80)->save($dPath);

                            if (File::exists($category->image)) {
                                File::delete($category->image);
                            }
                            $category->name = $request->name;
                            $category->slug = $request->slug;
                            $category->status = $request->status;
                            $category->image = $imgPath;
                            $category->save();
                        }
                    } else {
                        $category->name = $request->name;
                        $category->slug = $request->slug;
                        $category->status = $request->status;
                        $category->save();
                    }
                    session()->flash('success', 'Category Updated Successfully.');
                    return  response()->json(
                        [
                            'status' => true,
                            'message' => 'Category Updated Successfully.'
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
        return view('admin.category.edit', compact('category'));
    }

    public function destroy(Request $request)
    {
        $categoryId = $request->id;
        $category = Category::find($categoryId);
        if (empty($category)) {
            session()->flash('error', 'Category Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Category Not Found.'
                ]
            );

            return redirect()->route('categories.view');
        } else {

            if (File::exists($category->image)) {
                File::delete($category->image);
            }

            $pruducts = Product::where('category_id',  $categoryId)->get();

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

            Product::where('category_id',  $categoryId)->delete();


            $subCategories = SubCategory::where('category_id', $categoryId)->get();

            foreach ($subCategories as $subCategory) {
                $subCategory->leafCategories()->delete();
                $subCategory->delete();
            }

            $category->delete();

            session()->flash('success', 'Category Deleted Successfully.');
            return  response()->json(
                [
                    'status' => true,
                    'message' => 'Category Deleted Successfully.'
                ]
            );
        }
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
