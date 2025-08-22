<?php

namespace App\http\Services;

use Vimeo\Vimeo;

class VimeoService
{
    protected $vimeo;

    public function __construct()
    {
        $this->vimeo = new Vimeo(
            env('VIMEO_CLIENT_ID'),
            env('VIMEO_CLIENT_SECRET'),
            env('VIMEO_ACCESS_TOKEN')
        );
    }

    public function uploadVideo($filePath, $title, $description)
    {
        try {
            $response = $this->vimeo->upload($filePath, [
                'name' => $title,
                'description' => $description,
                'privacy' => ['view' => 'anybody'] // Public video
            ]);

            return $response; // Returns video URL
        } catch (\Exception $e) {
            return null;
        }
    }
}
