<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductExampleExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\File;

class ImportController extends Controller
{
    public function importProducts(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'file' => 'required',
            'leafCategoryId' => 'required|numeric',
        ]);
        if ($validator->passes()) {

            $file = $request->file;
            $leafCategoryId = $request->leafCategoryId;
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '-' . uniqid() . '.' . $ext;

            $file->move(public_path() . '/uploads/excelImports/', $fileName);

            $path = public_path() . '/uploads/excelImports/' . $fileName;

            Excel::import(new ProductImport($leafCategoryId), $path);

            if (File::exists($path)) {
                File::delete($path);
            }
            session()->flash('success', 'Data Imported Successfully.');
            return  response()->json(
                [
                    'status' => true,
                    'message' => 'Data Uploaded.'
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

     public function exportExample(Request $request)
    {
        $leafCategoryId = $request->export_leaf_category;
        if ($leafCategoryId == '') {
            return redirect()->back()->with('error', 'Please Select A Leaf Category First');
        }
        return Excel::download(new ProductExampleExport($leafCategoryId), 'example.xlsx');
    }
}
