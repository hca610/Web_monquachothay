<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Exception;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;

class NotificationController extends Controller
{
    public function showAllNotifications() {
        try {
            UserController::checkrole('admin');
            $notifications = Notification::orderByDesc('created_at')->paginate(20);
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

    function create(array $arr)
    {
        $notification = new Notification;
        $notification->fill($arr);
        $notification->save();
        return $notification;
    }

    function update(array $arr)
    {
        $notification = UserController::findOrFail($arr['notification_id']);
        $notification->fill($arr);
        $notification->save();
        return $notification;
    }

    public function createNotification(Request $request)
    {
        try {
            UserController::checkrole('admin');
            $notification = $this->create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Tao thong bao thanh cong',
                'data' => $notification,
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
            UserController::checkrole('admin');
            $notification = Notification::findOrFail($request->id);
            if (auth()->user()->role == 'admin') {
                $notification = $this->update($request->all());
            }
            else {
                $notification = $this->update([
                    'notification_id' => $request->id,
                    'status' => $request->status
                ]);
            }
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

    public function showNotification($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            if (auth()->user()->role != 'admin' &&
                $notification->receiver_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the xem thong bao nay');
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem thong bao thanh con',
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

    public function showUserNotifications($user_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                $user_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the xem thong bao cua nguoi dung '.$user_id);
            $notifications = Notification::
            where('receiver_id', $user_id)
            ->orderByDesc('created_at')
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem thong bao cua nguoi dung '.$user_id.' thanh cong',
                'data' => $notifications,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi tim kiem thong bao cua nguoi dung ' . $user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function countUserNotificationsByStatus($user_id, $status)
    {
        try {
            if ($status != 'unseen' && $status != 'seen')
                throw new Exception('Trang thai (status) thong bao '.strtoupper($status).' khong hop le');
            if (auth()->user()->role != 'admin' && 
                $user_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the xem so luong thong bao '.strtoupper($status).' cua nguoi dung '.$user_id);
            $counter = Notification::
            where('receiver_id', $user_id)
            ->where('status', $status)
            ->count();
            return response()->json([
                'success' => true,
                'message' => 'Dem so luong thong bao '.strtoupper($status).' cua nguoi dung '.$user_id.' thanh cong',
                'data' => $counter,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi dem so luong thong bao '.strtoupper($status).' cua nguoi dung ' . $user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showUserNotificationsByStatus($user_id, $status)
    {
        try {
            if ($status != 'unseen' && $status != 'seen')
                throw new Exception('Trang thai (status) thong bao '.strtoupper($status).' khong hop le');
            if (auth()->user()->role != 'admin' && 
                $user_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the xem thong bao '.strtoupper($status).' cua nguoi dung '.$user_id);
            $counter = Notification::
            where('receiver_id', $user_id)
            ->where('status', $status)
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem thong bao '.strtoupper($status).' cua nguoi dung '.$user_id.' thanh cong',
                'data' => $counter,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi tim kiem thong bao '.strtoupper($status).' cua nguoi dung ' . $user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
