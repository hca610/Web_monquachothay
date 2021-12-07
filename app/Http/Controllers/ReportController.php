<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Exception;
use Illuminate\Http\Request;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;

class ReportController extends MessageController
{
    private static $message_type = 'report';

    public function createReport(Request $request)
    {
        try {
            $data = $request->all();
            $data['type'] = self::$message_type;
            $data['sender_id'] = auth()->user()->user_id;
            $report = MessageController::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Tao phan hoi thanh cong',
                'data' => $report,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tao phan hoi khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateReport(Request $request)
    {
        try {
            $data = $request->all();
            if (array_key_exists('report_id', $data))
                $data['message_id'] = $data['report_id'];
            $report = Message::findOrFail($data['message_id']);
            if (auth()->user()->role != 'admin' &&
                $report->sender_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the chinh sua phan hoi nay');
            if ($report->type != self::$message_type)
                throw new Exception('Day khong phai la phan hoi');
            $data['type'] = self::$message_type;
            $report = MessageController::update($data);
            return response()->json([
                'success' => true,
                'message' => 'Chinh sua phan hoi thanh cong',
                'data' => $report,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chinh sua phan hoi khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function countReportstoUser($receiver_id)
    {
        try {
            $count = Message::
            where('type', self::$message_type)
            ->where('receiver_id', $receiver_id)
            ->where('status', '!=', 'hidden')
            ->count();
            return response()->json([
                'success' => true,
                'message' => 'Dem so phai hoi ve nguoi dung '.$receiver_id.' thanh cong',
                'data' => $count,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi dem so phai hoi ve nguoi dung '.$receiver_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function countReportsfromUser($sender_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                auth()->user()->user_id != $sender_id)
                throw new Exception('Nguoi dung khong the dem so luong phan hoi duoc gui boi nguoi dung '.$sender_id);
            $count = Message::
            where('type', self::$message_type)
            ->where('sender_id', $sender_id)
            ->where('status', '!=', 'hidden')
            ->count();
            return response()->json([
                'success' => true,
                'message' => 'Dem so phai hoi tu nguoi dung '.$sender_id.' thanh cong',
                'data' => $count,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi dem so phai hoi tu nguoi dung '.$sender_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showReportstoUser($receiver_id)
    {
        try {
            UserController::checkrole('admin');
            $reports = Message::orderByDesc('created_at')
            ->where('type', self::$message_type)
            ->where('receiver_id', $receiver_id)
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tat ca phai hoi ve nguoi dung '.$receiver_id.' thanh cong',
                'data' => $reports,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi tim kiem tat ca phai hoi ve nguoi dung '.$receiver_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showReportsfromUser($sender_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                auth()->user()->user_id != $sender_id)
                throw new Exception('Nguoi dung khong the xem phan hoi duoc gui boi nguoi dung '.$sender_id);
            $reports = Message::orderByDesc('created_at')
            ->where('type', self::$message_type)
            ->where('sender_id', $sender_id)
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tat ca phai hoi tu nguoi dung '.$sender_id.' thanh cong',
                'data' => $reports,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi tim kiem tat ca phai hoi tu nguoi dung '.$sender_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showAllReports()
    {
        try {
            UserController::checkrole('admin');
            $reports = Message::orderByDesc('created_at')
            ->where('type', self::$message_type)
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tat ca phai hoi tren he thong thanh cong',
                'data' => $reports,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi tim kiem tat ca phai hoi trong he thong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showReport($report_id)
    {
        try {
            $report = Message::findOrFail($report_id);
            if (auth()->user()->role != 'admin' &&
                auth()->user()->user_id != $report->sender_id)
                throw new Exception('Nguoi dung khong the xem phan hoi '.$report_id);
            if ($message->type != self::$message_type)
                throw new Exception('Day khong phai la phan hoi');
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem phai hoi '.$report_id.' tren he thong thanh cong',
                'data' => $report,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi tim kiem phai hoi '.$report_id.' trong he thong',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
