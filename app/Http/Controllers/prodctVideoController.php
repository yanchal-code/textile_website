<?php

namespace App\Http\Controllers;

use App\http\Services\VimeoService;
use App\Models\ProductVideo;
use Illuminate\Http\Request;

class prodctVideoController extends Controller
{

    protected $vimeoService;

    public function __construct(VimeoService $vimeoService)
    {
        $this->vimeoService = $vimeoService;
    }

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'video' => 'required|mimes:mp4,mov,avi|max:51200' // 50MB max
        ]);

        $file = $request->file('video');
        $videoPath = $file->getPathname();
        $title = "Product Video - " . now();
        $description = "Uploaded via Laravel";

        $videoUrl = $this->vimeoService->uploadVideo($videoPath, $title, $description);

        if (!$videoUrl) {
            return response()->json(['error' => 'Vimeo upload failed'], 500);
        }

        // Save video URL to DB
        $productImage = ProductVideo::create([
            'product_id' => $request->product_id,
            'url' => $videoUrl,
            'is_default' => false,
        ]);

        return response()->json(['message' => 'Video uploaded successfully', 'video_url' => $videoUrl]);
    }
}
