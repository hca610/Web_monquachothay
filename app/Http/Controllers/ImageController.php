<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

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

            $path = $request->file('image')->storeAs('/images', $user->user_id . '.' . $extension, ['disk' => 'public']);

            $image = new Image();

            $image->name = $user->user_id . '.' . $extension;
            $image->path = public_path('storage/images/' . $image->name);

            $image->save();

            $user->image_link = url('/') . '/api/get-image/' . $user->user_id;
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

    public function getImage($user_id)
    {
        $images = DB::table('images')
            ->where('name', 'like', $user_id . '%')
            ->orderByDesc('created_at')
            ->limit(1)
            ->get();

        if (sizeof($images) > 0)
            return response()->file($images[0]->path);
        else
            return response()->file(public_path('storage/images/' . 'noimage.png'));
    }
}
