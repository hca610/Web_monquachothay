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
            $message = Message::findOrFail($request->message_id);
            if (auth()->user()->role != 'admin' &&
                $message->sender_id != auth()->user()->user_id &&
                $message->receiver_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the chinh sua tin nhan nay');
            $data = $request->all();
            if ($message->sender_id == auth()->user()->user_id) {
                unset($data['receiver_id']);
            }
            if ($message->receiver_id == auth()->user()->user_id) {
                unset($data['receiver_id']);
                $data = ['status' => 'seen'];
            }
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
                throw new Exception('Nguoi dung khong the xem cuoc tro chuyen nay');
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

    public function showMessage(Request $request)
    {
        try {
            $message_id = $request->message_id;
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
                throw new Exception('Nguoi dung khong the xem nhung nguoi dung nhan tin gan nhat voi nguoi dung '.$user_id);
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
