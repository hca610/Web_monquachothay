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
                'message' => 'Tạo tin nhắn thành công',
                'data' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo tin nhắn không thành công',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateMessage(Request $request)
    {
        try {
            $message = Message::findOrFail($request->message_id);
            if (auth()->user()->role != 'admin' &&
                $message->sender_id != auth()->user()->user_id &&
                $message->receiver_id != auth()->user()->user_id)
                throw new Exception('Người dùng không thể chỉnh sửa tin nhắn này');
            if (auth()->user()->role == 'admin') {
                $message = $this->update($request->all());
            }
            else {
                $message = $this->update([
                    'message_id' => $request->message_id,
                    'status' => $request->status
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Chỉnh sửa tin nhắn thành công',
                'data' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉnh sửa tin nhắn không thành công',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showChat(Request $request)
    {
        try {
            if ($request->has('user_id'))
                $user_id = $request->user_id;
            else
                $user_id = auth()->user()->user_id;
            $other_id = $request->other_id;
            if ($request->has('before'))
                $before = $request->before;
            else
                $before = now();
            if ($request->has('get'))
                $get = $request->get;
            else
                $get = 1;
            if (auth()->user()->role != 'admin' && 
                auth()->user()->user_id != $user_id)
                throw new Exception('Người dùng không thể xem cuộc trò chuyện này');
            $messages = Message::orderByRaw('created_at DESC, message_id DESC')
            ->where(function ($query) use ($user_id, $other_id) {
                $query
                ->where(function ($query) use ($user_id, $other_id) {
                    $query
                    ->where('sender_id', $user_id)
                    ->where('receiver_id', $other_id);
                })
                ->orwhere(function ($query) use ($user_id, $other_id) {
                    $query
                    ->where('sender_id', $other_id)
                    ->where('receiver_id', $user_id);
                });
            })
            ->where(function ($query) use ($before) {
                if (is_numeric($before) == 1)
                    $query->where('message_id', '<', $before);
                else
                    $query->where('created_at', '<', $before);
            })
            ->limit($get)
            ->get();
            return response()->json([
                'success' => true,
                'message' => 'Tìm kiếm cuộc trò chuyện thành công',
                'data' => $messages,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tìm kiếm cuộc trò chuyện không thành công',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showAllChat(Request $request)
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
            $messages = Message::orderByRaw('created_at DESC, message_id DESC')
            ->where(function ($query) use ($before) {
                if (is_numeric($before) == 1)
                    $query->where('message_id', '<', $before);
                else
                    $query->where('created_at', '<', $before);
            })
            ->limit($get)
            ->get();
            return response()->json([
                'success' => true,
                'message' => 'Tìm kiếm tất cả tin nhắn thành công',
                'data' => $messages,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tìm kiếm tất cả tin nhắn không thành công',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showMessage(Request $request)
    {
        try {
            $message_id = $request->message_id;
            $message = Message::findOrFail($message_id);
            if (auth()->user()->role != 'admin' &&
                auth()->user()->user_id != $message->sender_id &&
                auth()->user()->user_id != $message->receiver_id )
                throw new Exception('Người dùng không thể xem tin nhắn '.$message_id);
            return response()->json([
                'success' => true,
                'message' => 'Tìm kiếm tin nhắn '.$message_id.' thành công',
                'data' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tìm kiếm tin nhắn '.$message_id.' không thành công',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function countInChatByStatus(Request $request)
    {
        try {
            if ($request->has('user_id'))
                $user_id = $request->user_id;
            else
                $user_id = auth()->user()->user_id;
            $other_id = $request->other_id;
            $status = $request->status;
            if ($status != 'unseen' && $status != 'seen')
                throw new Exception('Trạng thái (status) tin nhan '.strtoupper($status).' không hợp lệ');
            if (auth()->user()->role != 'admin' && 
                $user_id != auth()->user()->user_id)
                throw new Exception('Người dùng không thể xem số lượng tin nhắn '.strtoupper($status).' của người dùng '.$user_id.' từ người dùng '.$other_id);
            $counter = Message::
            where('receiver_id', $user_id)
            ->where('sender_id', $other_id)
            ->where('status', $status)
            ->count();
            return response()->json([
                'success' => true,
                'message' => 'Đếm số lượng tin nhắn '.strtoupper($status).' của người dùng '.$user_id.' từ người dùng '.$other_id.' thành công',
                'data' => $counter,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi đếm số lượng tin nhắn '.strtoupper($status).' của người dùng '.$user_id.' từ người dùng '.$other_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showLastestChats(Request $request)
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
                throw new Exception('Người dùng không thể xem những người dùng nhắn tin gần nhất với người dùng '.$user_id);
            $lastestChats = Message::orderByRAW('MAX(messages.updated_at) DESC, message_id DESC')
            ->selectRaw('sender_id + receiver_id - ? as other_id,
                if(sender.user_id!=?,sender.name,receiver.name) as name,
                if(sender.user_id!=?,sender.email,receiver.email) as email,
                MAX(messages.updated_at) as updated_at', [$user_id, $user_id, $user_id])
            ->where(function ($query) use ($user_id) {
                $query
                ->where('sender_id', $user_id)
                ->orWhere('receiver_id', $user_id);
            })
            ->join('users as sender', 'sender.user_id', '=', 'sender_id')
            ->join('users as receiver', 'receiver.user_id', '=', 'receiver_id')
            ->groupByRaw('sender_id+receiver_id')
            ->havingRaw('MAX(messages.updated_at) < ?', [$before])
            ->limit($get)
            ->get();
            return response()->json([
                'success' => true,
                'message' => 'Tìm kiếm những người dùng nhắn tin gần nhất với người dùng '.$user_id.' thành công',
                'data' => $lastestChats,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tìm kiếm những người dùng nhắn tin gần nhất với người dùng '.$user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function countUserUnseenChatWith()
    {
        try {
            $user_id = auth()->user()->user_id;
            $count = Message::where('receiver_id', $user_id)
            ->where('status', 'unseen')
            ->groupBy('sender_id')
            ->count();
            return response()->json([
                'success' => true,
                'message' => 'Đếm số người dùng UNSEEN bởi người dùng '.$user_id.' thành công',
                'data' => $count,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi đếm số người dùng UNSEEN bởi người dùng '.$user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
