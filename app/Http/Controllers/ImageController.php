<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\User;
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

            $path = $request->file('image')->storeAs('/images', $user->user_id.'.'.$extension, ['disk' => 'public']);

            $image = new Image();

            $image->name = $user->user_id.'.'.$extension;
            $image->path = $path;

            $image->save();

            $user->image_link = public_path('storage/images/'.$image->name);
            $user->save();

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

    public function getImage($id) {
        $user = User::find($id);
        if ($user->image_link == NULL) {
            return response()->file(public_path('storage/images/noimage.png'));
        }
        return response()->file($user->image_link);
    }
}
