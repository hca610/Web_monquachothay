<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Exception;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;

class NotificationController extends Controller
{
    public function showAllNotifications(Request $request)
    {
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
                'message' => 'Tìm kiếm tất cả thông báo trên hệ thông thành công',
                'data' => $notifications
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tìm kiếm tất cả thông báo trên hệ thống không thành công',
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
                'message' => 'Tạo thông báo thành công',
                'data' => $notification,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo thông báo không thành công',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateNotification(Request $request)
    {
        try {
            $notification = Notification::findOrFail($request->notification_id);
            if (
                auth()->user()->role != 'admin' &&
                $notification->receiver_id != auth()->user()->user_id
            )
                throw new Exception('Người dùng không thể chỉnh sửa thông báo này');
            if (auth()->user()->role == 'admin') {
                $notification = $this->update($request->all());
            } else {
                $notification = $this->update([
                    'notification_id' => $request->notification_id,
                    'status' => $request->status
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông báo thành công',
                'data' => $notification,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thông báo không thành công',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showNotification(Request $request)
    {
        try {
            $notification_id = $request->notification_id;
            $notification = Notification::findOrFail($notification_id);
            if (
                auth()->user()->role != 'admin' &&
                $notification->receiver_id != auth()->user()->user_id
            )
                throw new Exception('Người dùng không thể xem thông báo này');
            return response()->json([
                'success' => true,
                'message' => 'Tìm kiếm thông báo thành công',
                'notification' => $notification,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tìm kiếm',
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
            if (
                auth()->user()->role != 'admin' &&
                $user_id != auth()->user()->user_id
            )
                throw new Exception('Người dùng không thể xem thông báo của người dùng ' . $user_id);
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
                'message' => 'Tìm kiếm thông báo của người dùng ' . $user_id . ' thành công',
                'data' => $notifications,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tìm kiếm thông báo của người dùng ' . $user_id,
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
                throw new Exception('Trạng thái (status) của thông báo ' . strtoupper($status) . ' không hợp lệ');
            if (
                auth()->user()->role != 'admin' &&
                $user_id != auth()->user()->user_id
            )
                throw new Exception('Người dùng không thể xem số lượng thông báo ' . strtoupper($status) . ' của người dùng ' . $user_id);
            $counter = Notification::where('receiver_id', $user_id)
                ->where('status', $status)
                ->count();
            return response()->json([
                'success' => true,
                'message' => 'Đếm số lượng thông báo ' . strtoupper($status) . ' của người dùng ' . $user_id . ' thành công',
                'data' => $counter,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi đếm số lượng thông báo ' . strtoupper($status) . ' của người dùng ' . $user_id,
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
                throw new Exception('Trạng thái (status) của thông báo ' . strtoupper($status) . ' không hợp lệ');
            if (
                auth()->user()->role != 'admin' &&
                $user_id != auth()->user()->user_id
            )
                throw new Exception('Người dùng không thể xem thông báo ' . strtoupper($status) . ' của người dùng ' . $user_id);
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
                'message' => 'Tìm kiếm thông báo ' . strtoupper($status) . ' của người dùng ' . $user_id . ' thành công',
                'data' => $counter,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tìm kiếm thông báo ' . strtoupper($status) . ' của người dùng ' . $user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

}
