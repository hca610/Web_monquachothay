<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Exception;
use Illuminate\Http\Request;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;

class ChatController extends MessageController
{
    private static $message_type = 'chat';

    public function createMessage(Request $request)
    {
        try {
            $data = $request->all();
            $data['type'] = self::$message_type;
            $data['sender_id'] = auth()->user()->user_id;
            $report = MessageController::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Tao tin nhan hoi thoai thanh cong',
                'data' => $report,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tao tin nhan hoi thoai khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateMessage(Request $request)
    {
        try {
            $data = $request->all();
            $report = Message::findOrFail($data['message_id']);
            if (auth()->user()->role != 'admin' &&
                $report->sender_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the chinh sua tin nhan hoi thoai nay');
            if ($report->type != self::$message_type)
                throw new Exception('Day khong phai la tin nhan hoi thoai');
            $data['type'] = self::$message_type;
            $report = MessageController::update($data);
            return response()->json([
                'success' => true,
                'message' => 'Chinh sua tin nhan hoi thoai thanh cong',
                'data' => $report,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chinh sua tin nhan hoi thoai khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showChat($user_id, $other_id)
    {
        echo $user_id.'  '.$other_id;
        try {
            if (auth()->user()->role != 'admin' && 
                auth()->user()->user_id != $user_id)
                throw new Exception('Nguoi dung khong the xem cuoc hoi thoai nay');
            $messages = Message::orderByDesc('created_at')
            ->where('type', self::$message_type)
            ->where(function ($query) use ($user_id, $other_id) {
                $query
                ->where('sender_id', $user_id)
                ->where('receiver_id', $other_id);
            })
            ->orwhere(function ($query) use ($user_id, $other_id) {
                $query
                ->where('sender_id', $other_id)
                ->where('receiver_id', $user_id);
            })
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem hoi thoai thanh cong',
                'data' => $messages,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tim kiem hoi thoai khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showAllChat()
    {
        try {
            UserController::checkrole('admin');
            $messages = Message::orderByDesc('created_at')
            ->where('type', self::$message_type)
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tat ca tin nhan hoi thoai thanh cong',
                'data' => $messages,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tim kiem tat ca tin nhan hoi thoai khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showMessage($message_id)
    {
        try {
            $message = Message::findOrFail($message_id);
            if (auth()->user()->role != 'admin' &&
                auth()->user()->user_id != $message->sender_id &&
                auth()->user()->user_id != $message->receiver_id )
                throw new Exception('Nguoi dung khong the xem tin nhan hoi thoai '.$message_id);
            if ($message->type != self::$message_type)
                throw new Exception('Day khong phai la tin nhan hoi thoai');
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tin nhan hoi thoai '.$message_id.' thanh cong',
                'data' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tim kiem tin nhan hoi thoai '.$message_id.' khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function countUnseen($user_id, $other_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                $user_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the xem so luong tin nhan chua duoc xem cua nguoi dung '.$user_id.' tu nguoi dung '.$other_id);
            $counter = Message::
            where('receiver_id', $user_id)
            ->where('sender_id', $other_id)
            ->where('type', self::$message_type)
            ->where('status', 'unseen')
            ->count();
            return response()->json([
                'success' => true,
                'message' => 'Dem so luong tin nhan chua xem cua nguoi dung '.$user_id.' tu nguoi dung '.$other_id.' thanh cong',
                'data' => $counter,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi dem so luong tin nhan chua xem cua nguoi dung '.$user_id.' tu nguoi dung '.$other_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
