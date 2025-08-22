<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::paginate(10);
        return view('admin.carousel.index', compact('carousels'));
    }

    public function create()
    {
        return view('admin.carousel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'heighlight' => 'required|string|max:255',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'btn_text' => 'required|string',
            'link' => 'nullable|url',
            'description' => 'nullable|string',
            'status' => 'required|numeric',
        ]);

        if ($request->hasFile('image_path')) {

            $img_temp = $request->file('image_path');
            if ($img_temp->isValid()) {
                $extension = $img_temp->getClientOriginalExtension();
                $imageName =  time() . uniqid() . '.' . $extension;

                // $dPath = public_path() . '/uploads/category/' . $imageName;
                // $image = $image->resizeDown(1100, 700);
                // $image->toJpeg(80)->save($dPath);

                $img_temp->move(public_path('uploads/carousel'), $imageName);

                $imgPath = 'uploads/carousel/' . $imageName;
            }
        }
        $validated['image_path'] = $imgPath;

        Carousel::create($validated);

        session()->flash('success', 'Carousel Item Successfully.');
        return  response()->json(
            [
                'status' => true,
                'message' => 'Carousel Item Added Successfully.'
            ]
        );
    }

    public function edit(Carousel $carousel)
    {
        return view('admin.carousel.edit', compact('carousel'));
    }

    public function update(Request $request, Carousel $carousel)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'heighlight' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'btn_text' => 'required|string',
            'link' => 'nullable|url',
            'description' => 'nullable|string',
            'status' => 'required|numeric',

        ]);

        if ($request->hasFile('image_path')) {

            $img_temp = $request->file('image_path');
            if ($img_temp->isValid()) {
                $extension = $img_temp->getClientOriginalExtension();
                $imageName =  time() . uniqid() . '.' . $extension;


                // $dPath = public_path() . '/uploads/category/' . $imageName;
                // $image = $image->resizeDown(1100, 700);
                // $image->toJpeg(80)->save($dPath);

                $img_temp->move(public_path('uploads/carousel'), $imageName);

                if (File::exists($carousel->image_path)) {
                    File::delete($carousel->image_path);
                }

                $imgPath = 'uploads/carousel/' . $imageName;
                $validated['image_path'] = $imgPath;
            }
        }


        $carousel->update($validated);

        session()->flash('success', 'Carousel item updated successfully.');
        return  response()->json(
            [
                'status' => true,
                'message' => 'Carousel item updated successfully.'
            ]
        );
    }

    public function destroy(Carousel $carousel)
    {
        if (File::exists($carousel->image_path)) {
            File::delete($carousel->image_path);
        }
        $carousel->delete();
        return redirect()->route('carousel.index')->with('success', 'Carousel item deleted successfully.');
    }
}
