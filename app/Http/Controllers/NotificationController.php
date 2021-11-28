<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Exception;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $notification = new Notification;
            $notification->title = $request->input('title');
            $notification->detail = $request->input('detail');
            $notification->status = $request->input('status');
            $notification->receiver_id = $request->input('receiver_id');
            $notification->save();
            return response()->json([
                'success' => true,
                'message' => 'Tao thong bao thanh cong',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tao thong bao khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        try {
            $notification = Notification::find($id);
            return response()->json([
                'success' => true,
                'data' => $notification,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi tim kiem',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $notification = Notification::find($request->input('notification_id'));
            $notification->title = $request->input('title');
            $notification->detail = $request->input('detail');
            $notification->status = $request->input('status');
            $notification->receiver_id = $request->input('receiver_id');
            $notification->save();
            return response()->json([
                'success' => true,
                'message' => 'Cap nhat thanh cong',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cap nhat khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showUserNoti($user_id)
    {
        try {
            $alluser_id = 0;
            $notifications = Notification::orderBy('created_at', 'desc')
            ->where('receiver_id', $user_id)
            ->orWhere('receiver_id', $alluser_id)
            ->simplePaginate(10);
            return response()->json([
                'success' => true,
                'data' => $notifications,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
