<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Exception;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $user = auth()->user();

            $originalName = $request->file('image')->getClientOriginalName();
            $extension = $request->file('image')->getClientOriginalExtension();

            $path = $request->file('image')->storeAs('/images', $user->user_id.'.'.$extension);

            $image = new Image();

            $image->name = $user->user_id.'.'.$extension;
            $image->path = $path;

            $image->save();

            return response()->json([
                'success' => true,
                'message' => 'Upload ảnh thành công',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
