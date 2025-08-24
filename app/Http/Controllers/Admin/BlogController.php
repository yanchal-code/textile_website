<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function destroy(Request $request)
    {
        $blogId = $request->id;

        $blog = Blog::find($blogId);

        if (empty($blog)) {
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'blog Not Found.'
                ]
            );
        }

        if (File::exists($blog->image)) {
            File::delete($blog->image);
        }

        $blog->delete();

        session()->flash('success', 'Blog Deleted Successfully.');
        return  response()->json(
            [
                'status' => true,
                'message' => 'Blog Deleted Successfully.'
            ]
        );
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8048',
        ]);
        if (!$validator->passes()) {

            return  response()->json(
                [
                    'status' => 'validation',
                    'errors' => $validator->errors()
                ]
            );
        }
        $slug = Str::slug($request->title);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'blog_image_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/blogs/'), $imageName);
            $imagePath = '/uploads/blogs/' . $imageName;
        }

        Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imagePath,
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'alt_image_text' => $request->alt_image_text,
            'meta_description' => $request->meta_description,
            'user_id' => Auth::guard('admin')->user()->id,
            'status' => $request->status ?? 1,
        ]);

        return  response()->json(
            [
                'status' => true,
                'message' => 'Blog created successfully!',
                'reload' => true
            ]
        );
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8048',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'status' => 'validation',
                'errors' => $validator->errors()
            ]);
        }

        $slug = Str::slug($request->title);

        $imagePath = $blog->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'blog_image_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/blogs/'), $imageName);
            $imagePath = '/uploads/blogs/' . $imageName; // store relative path
        }

        $blog->update([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imagePath,
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'alt_image_text' => $request->alt_image_text,
            'meta_description' => $request->meta_description,
            'status' => $request->status ?? 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Blog updated successfully!',
            'reload' => true
        ]);
    }
}
