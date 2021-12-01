<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Exception;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function showAllNotification() {
        try {
            $user = auth()->user();
            if ($user->role != 'admin') {
                throw new Exception('Ban khong phai la admin');
            }

            $notifications = Notification::paginate(20)->sortByDesc('created_at');
            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tim kiem toan bo thong bao bi loi',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function createNotification(Request $request)
    {
        try {
            $notification = new Notification;
            $notification->fill($request->all());
            $notification->save();
            return response()->json([
                'success' => true,
                'message' => 'Tao thong bao thanh cong',
                'data' => $notification
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tao thong bao khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateNotification(Request $request)
    {
        try {
            $notification = Notification::findOrFail($request->input('notification_id'));
            $notification->fill($request->all());
            $notification->save();
            return response()->json([
                'success' => true,
                'message' => 'Cap nhat thong bao thanh cong',
                'data' => $notification,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cap nhat thong bao khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function createAndUpdateNotification(Request $request)
    {
        try {
            $user = auth()->user();
            if ($user->role != 'admin') {
                throw new Exception('Ban khong phai la admin');
            }
            
            if ($request->notification_id == null)
                return $this->createNotification($request);
            else
                return $this->updateNotification($request);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xu ly thong bao khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showNotification($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            return response()->json([
                'success' => true,
                'notification' => $notification,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi tim kiem',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showUserNotification()
    {
        try {
            $user = auth()->user();

            $user_id = $user->user_id;
            $alluser_id = 1;
            $notifications = Notification::
            where('receiver_id', $user_id)
            ->orWhere('receiver_id', $alluser_id)
            ->paginate(20)
            ->sortByDesc('created_at');
            return response()->json([
                'success' => true,
                'data' => $notifications,
                'user_id' => $user_id
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi tim kiem thong bao cua nguoi dung ' . $user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
