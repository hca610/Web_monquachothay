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

    protected function show($id)
    {
        $message = Message::find($id);
        return $message;
    }

    //________________________________________________________________________________________________________________________

    public function createMessage(Request $request)
    {
        try {
            $message = new Message;
            $message->title = $request->input('title');
            $message->detail = $request->input('detail');
            $message->status = $request->input('status');
            $message->sender_id = $request->input('sender_id');
            $message->receiver_id = $request->input('receiver_id');
            $message->save();
            return response()->json([
                'success' => true,
                'message' => 'Tao tin nhan thanh cong',
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
            $message = Message::find($request->input('message_id'));
            $message->title = $request->input('title');
            $message->detail = $request->input('detail');
            $message->status = $request->input('status');
            $message->sender_id = $request->input('sender_id');
            $message->receiver_id = $request->input('receiver_id');
            $message->save();
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

    public function showUsersMessage($sender_id, $receiver_id)
    {
        try {
            $notifications = Message::orderBy('created_at', 'desc')
            ->where('sender_id', $sender_id)
            ->where('receiver_id', $receiver_id)
            ->where('title', '!=', "report")
            ->simplePaginate(10);
            return response()->json([
                'success' => true,
                'data' => $notifications,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Da xay ra loi khi tim kiem tin nhan',
            ]);
        }
    }


    ### Report ###
    public function reportCount($receiver_id)
    {
        try {
            $report_counter = Message::where('title', "report")
            ->where('receiver_id', $receiver_id)
            ->count();
            return response()->json([
                'success' => true,
                'message' => 'Da dem xong',
                'data' => $report_counter,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi dem',
            ]);
        }
    }

    public function showReports($receiver_id)
    {
        try {
            $reports = Message::where('title', "report")
            ->where('receiver_id', $receiver_id)
            ->orderBy('created_at', 'desc')
            ->get();
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem phan hoi thanh cong',
                'data' => $reports,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tim kiem phan hoi that bai',
            ]);
        }
    }
}
