<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{

    public function index(Request $request)
    {
        $categories = brand::latest();
        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $categories = $categories->paginate(10);

        return view('admin.brands.list', compact('categories'));
    }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required|unique:brands',
                'status' => 'required'
            ]);
            if ($validator->passes()) {

                $category = new Brand();
                $category->name = $request->name;
                $category->slug = $request->slug;
                $category->status = $request->status;
                $category->save();

                session()->flash('success', 'Product Group Added Successfully.');
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'Product Group Added Successfully.'
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
        return view('admin.brands.create');
    }


    public function edit($categoryId, Request $request)
    {
        $category = Brand::find($categoryId);

        if (empty($category)) {
            session()->flash('error', 'Product Group Not Found.');
            return redirect()->route('brands.view');
        } else {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'slug' => 'required|unique:brands,slug,' . $category->id . 'id',
                ]);
                if ($validator->passes()) {

                    $category->name = $request->name;
                    $category->slug = $request->slug;
                    $category->status = $request->status;
                    $category->save();
                    session()->flash('success', 'Product Group Updated Successfully.');
                    return  response()->json(
                        [
                            'status' => true,
                            'message' => 'Product Group Updated Successfully.'
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

        return view('admin.brands.edit', compact('category'));
    }
    public function destroy(Request $request)
    {
        $categoryId = $request->id;
        $category = Brand::find($categoryId);
        if (empty($category)) {
            session()->flash('error', 'Record Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Record Not Found.'
                ]
            );
            return redirect()->route('brands.view');
        } else {

            $category->delete();

            session()->flash('success', 'Product Group Deleted Successfully.');
            return  response()->json(
                [
                    'status' => true,
                    'message' => 'Product Group Deleted Successfully.'
                ]
            );
        }
    }
}
