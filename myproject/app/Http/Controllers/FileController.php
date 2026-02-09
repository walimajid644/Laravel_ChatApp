<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function download($path)
    {
        if (!Storage::disk('gridfs')->exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return Storage::disk('gridfs')->response($path);
    }
}