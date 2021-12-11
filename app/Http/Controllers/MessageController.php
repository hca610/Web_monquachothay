<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Exception;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;

class MessageController extends Controller
{
    protected function create(array $arr)
    {
        $message = new Message;
        $message->fill($arr);
        $message->save();
        return $message;
    }

    protected function update(array $arr)
    {
        $message = Message::findOrFail($arr['message_id']);
        $message->fill($arr);
        $message->save();
        return $message;
    }

    public function createMessage(Request $request)
    {
        try {
            $data = $request->all();
            $data['sender_id'] = auth()->user()->user_id;
            $message = self::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Tao tin nhan thanh cong',
                'data' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tao tin nhan khong thanh cong',
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
                throw new Exception('Nguoi dung khong the chinh sua tin nhan nay');
            $message = MessageController::update($data);
            return response()->json([
                'success' => true,
                'message' => 'Chinh sua tin nhan thanh cong',
                'data' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chinh sua tin nhan khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showChat($user_id, $other_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                auth()->user()->user_id != $user_id)
                throw new Exception('Nguoi dung khong the xem cuoc tro chuyen nay');
            $messages = Message::orderByDesc('created_at')
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
                'message' => 'Tim kiem cuoc tro chuyen thanh cong',
                'data' => $messages,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tim kiem cuoc tro chuyen khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showAllChat()
    {
        try {
            UserController::checkrole('admin');
            $messages = Message::orderByDesc('created_at')
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tat ca tin nhan thanh cong',
                'data' => $messages,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tim kiem tat ca tin nhan khong thanh cong',
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
                throw new Exception('Nguoi dung khong the xem tin nhan '.$message_id);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tin nhan '.$message_id.' thanh cong',
                'data' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tim kiem tin nhan '.$message_id.' khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function countInChatByStatus($user_id, $other_id, $status)
    {
        try {
            if ($status != 'unseen' && $status != 'seen')
                throw new Exception('Trang thai (status) tin nhan '.strtoupper($status).' khong hop le');
            if (auth()->user()->role != 'admin' && 
                $user_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the xem so luong tin nhan '.strtoupper($status).' cua nguoi dung '.$user_id.' tu nguoi dung '.$other_id);
            $counter = Message::
            where('receiver_id', $user_id)
            ->where('sender_id', $other_id)
            ->where('status', $status)
            ->count();
            return response()->json([
                'success' => true,
                'message' => 'Dem so luong tin nhan '.strtoupper($status).' cua nguoi dung '.$user_id.' tu nguoi dung '.$other_id.' thanh cong',
                'data' => $counter,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi dem so luong tin nhan '.strtoupper($status).' cua nguoi dung '.$user_id.' tu nguoi dung '.$other_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showLastestChats($user_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                $user_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the xem nhung nguoi dung nhan tin gan nhat voi nguoi dung '.$user_id);
            $lastestChats = Message::orderByDesc('updated_at')
            ->where('sender_id', $user_id)
            ->orWhere('receiver_id', $user_id)
            ->groupByRaw('sender_id+receiver_id')
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem nhung nguoi dung nhan tin gan nhat voi nguoi dung '.$user_id,
                'data' => $lastestChats,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi tim kiem nhung nguoi dung nhan tin gan nhat voi nguoi dung '.$user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
