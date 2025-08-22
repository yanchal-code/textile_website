<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::latest();
        if (!empty($request->get('keyword'))) {
            $pages = $pages->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $pages = $pages->paginate(10);

        return view('admin.pages.list', compact('pages'));
    }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required|unique:pages',
                'content' => 'required',

                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable|string',
                'alt_image_text' => 'nullable|string|max:255',
            ]);
            if ($validator->passes()) {

                $page = new page();
                $page->name = $request->name;
                $page->slug = $request->slug;
                $page->status = $request->status;
                $page->content = $request->content;

                $page->meta_title = $request->meta_title;
                $page->meta_description = $request->meta_description;
                $page->meta_keywords = $request->meta_keywords;
                $page->alt_image_text = $request->alt_image_text;
                $page->created_at = now();
                $page->updated_at = now();
                $page->save();

                session()->flash('success', 'Page Created Successfully.');
                return  response()->json(
                    [
                        'status' => true,
                        'message' => 'Page Added Successfully.'
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
        return view('admin.pages.create');
    }
    public function edit($id, Request $request)
    {
        $page = page::find($id);

        if (empty($page)) {
            session()->flash('error', 'Page Not Found.');
            return redirect()->route('pages.view');
        } else {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'status' => 'required',
                    'content' => 'required',
                    'slug' => 'required|unique:pages,slug,' . $page->id . 'id',

                    'meta_title' => 'nullable|string|max:255',
                    'meta_description' => 'nullable|string',
                    'meta_keywords' => 'nullable|string',
                    'alt_image_text' => 'nullable|string|max:255',
                ]);
                if ($validator->passes()) {
                    $page->name = $request->name;
                    $page->slug = $request->slug;
                    $page->status = $request->status;
                    $page->content = $request->content;

                    $page->meta_title = $request->meta_title;
                    $page->meta_description = $request->meta_description;
                    $page->meta_keywords = $request->meta_keywords;
                    $page->alt_image_text = $request->alt_image_text;
                    $page->updated_at = now();
                    $page->save();
                    session()->flash('success', 'Page Updated Successfully.');
                    return  response()->json(
                        [
                            'status' => true,
                            'message' => 'Page Updated Successfully.'
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

        return view('admin.pages.edit', compact('page'));
    }
    public function destroy(Request $request)
    {
        $pageId = $request->id;
        $page = page::find($pageId);
        if (empty($page)) {
            session()->flash('error', 'Page Not Found.');
            return  response()->json(
                [
                    'status' => false,
                    'message' => 'Page Not Found.'
                ]
            );
            return redirect()->route('pages.view');
        } else {


            $page->delete();
            session()->flash('success', 'Page Deleted Successfully.');
            return  response()->json(
                [
                    'status' => true,
                    'message' => 'Page Deleted Successfully.'
                ]
            );
        }
    }
}
