<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Exception;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;

class NotificationController extends Controller
{
    public function showAllNotifications(Request $request) {
        try {
            if ($request->has('before'))
                $before = $request->before;
            else
                $before = now();
            if ($request->has('get'))
                $get = $request->get;
            else
                $get = 1;
            UserController::checkrole('admin');
            $notifications = Notification::orderByRaw('created_at DESC, notification_id DESC')
            ->where(function ($query) use ($before) {
                if (is_numeric($before) == 1)
                    $query->where('notification_id', '<', $before);
                else
                    $query->where('created_at', '<', $before);
            })
            ->limit($get)
            ->get();
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
        $notification = Notification::findOrFail($arr['notification_id']);
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
            $notification = Notification::findOrFail($request->notification_id);
            if (auth()->user()->role != 'admin' &&
                $notification->receiver_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the chinh sua thong bao nay');
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

    public function showNotification(Request $request)
    {
        try {
            $notification_id = $request->notification_id;
            $notification = Notification::findOrFail($notification_id);
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

    public function showUserNotifications(Request $request)
    {
        try {
            if ($request->has('user_id'))
                $user_id = $request->user_id;
            else
                $user_id = auth()->user()->user_id;
            if ($request->has('before'))
                $before = $request->before;
            else
                $before = now();
            if ($request->has('get'))
                $get = $request->get;
            else
                $get = 1;
            if (auth()->user()->role != 'admin' && 
                $user_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the xem thong bao cua nguoi dung '.$user_id);
            $notifications = Notification::orderByRaw('created_at DESC, notification_id DESC')
            ->where('receiver_id', $user_id)
            ->where(function ($query) use ($before) {
                if (is_numeric($before) == 1)
                    $query->where('notification_id', '<', $before);
                else
                    $query->where('created_at', '<', $before);
            })
            ->limit($get)
            ->get();
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

    public function countUserNotificationsByStatus(Request $request)
    {
        try {
            if ($request->has('user_id'))
                $user_id = $request->user_id;
            else
                $user_id = auth()->user()->user_id;
            $status = $request->status;
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

    public function showUserNotificationsByStatus(Request $request)
    {
        try {
            if ($request->has('user_id'))
                $user_id = $request->user_id;
            else
                $user_id = auth()->user()->user_id;
            if ($request->has('before'))
                $before = $request->before;
            else
                $before = now();
            if ($request->has('get'))
                $get = $request->get;
            else
                $get = 1;
            $status = $request->status;
            if ($status != 'unseen' && $status != 'seen')
                throw new Exception('Trang thai (status) thong bao '.strtoupper($status).' khong hop le');
            if (auth()->user()->role != 'admin' && 
                $user_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the xem thong bao '.strtoupper($status).' cua nguoi dung '.$user_id);
            $counter = Notification::orderByRaw('created_at DESC, notification_id DESC')
            ->where('receiver_id', $user_id)
            ->where('status', $status)
            ->where(function ($query) use ($before) {
                if (is_numeric($before) == 1)
                    $query->where('notification_id', '<', $before);
                else
                    $query->where('created_at', '<', $before);
            })
            ->limit($get)
            ->get();
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
