<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    public function show($folder, $filename)
    {
        $path = base_path("files/images/{$folder}/{$filename}");
        if (!file_exists($path)) {
            abort(404);
        }
        return Response::file($path);
    }
} 