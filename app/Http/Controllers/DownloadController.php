<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function downloadExample()
    {
        $filePath = public_path('uploads/product_import.csv');

        if (file_exists($filePath)) {
            return Response::download($filePath, 'product_import.csv');
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
    }
}
