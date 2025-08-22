<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TagController extends Controller
{

    public function index()
    {

        return view('admin.edit_tag_codes');
    }


    public function edit($type)
    {

        $filePath = resource_path("views/seo/{$type}.blade.php");

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->json(['content' => File::get($filePath)]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'content' => 'nullable|string'
        ]);

        $filePath = resource_path("views/seo/{$request->type}.blade.php");

        $content = urldecode(base64_decode($request->content));
        File::put($filePath, $content);

        return response()->json(['message' => ucfirst($request->type) . ' tags updated successfully']);
    }
}
