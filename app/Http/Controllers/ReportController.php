<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Exception;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;

class ReportController extends Controller
{
    protected function create(array $arr)
    {
        $report = new Report;
        $report->fill($arr);
        $report->save();
        return $report;
    }

    protected function update(array $arr)
    {
        $report = Report::findOrFail($arr['report_id']);
        $report->fill($arr);
        $report->save();
        return $report;
    }

    public function createReport(Request $request)
    {
        try {
            $data = $request->all();
            $data['sender_id'] = auth()->user()->user_id;
            $report = self::create($data);
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
            $report = Report::findOrFail($data['report_id']);
            if (auth()->user()->role != 'admin' &&
                $report->sender_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the chinh sua phan hoi nay');
            $report = self::update($data);
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
            $count = Report::
            where('receiver_id', $receiver_id)
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
            $count = Report::
            where('sender_id', $sender_id)
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
            $reports = Report::orderByDesc('created_at')
            ->where('receiver_id', $receiver_id)
            ->join('users as sender', 'sender.user_id', '=', 'sender_id')
            ->join('users as receiver', 'receiver.user_id', '=', 'receiver_id')
            ->select('reports.*', 
                    'sender.name as sender_name', 
                    'sender.email as sender_email', 
                    'receiver.name as receiver_name', 
                    'receiver.email as receiver_email')
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
            $reports = Report::orderByDesc('created_at')
            ->where('sender_id', $sender_id)
            ->join('users as sender', 'sender.user_id', '=', 'sender_id')
            ->join('users as receiver', 'receiver.user_id', '=', 'receiver_id')
            ->select('reports.*', 
                    'sender.name as sender_name', 
                    'sender.email as sender_email', 
                    'receiver.name as receiver_name', 
                    'receiver.email as receiver_email')
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
            $reports = Report::orderByDesc('created_at')
            ->join('users as sender', 'sender.user_id', '=', 'sender_id')
            ->join('users as receiver', 'receiver.user_id', '=', 'receiver_id')
            ->select('reports.*', 
                    'sender.name as sender_name', 
                    'sender.email as sender_email', 
                    'receiver.name as receiver_name', 
                    'receiver.email as receiver_email')
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
            $report = Report::findOrFail($report_id);
            if (auth()->user()->role != 'admin' &&
                auth()->user()->user_id != $report->sender_id)
                throw new Exception('Nguoi dung khong the xem phan hoi '.$report_id);
            $report = Report::where('report_id', $report_id)
            ->join('users as sender', 'sender.user_id', '=', 'reports.sender_id')
            ->join('users as receiver', 'receiver.user_id', '=', 'reports.receiver_id')
            ->select('reports.*', 
                    'sender.name as sender_name', 
                    'sender.email as sender_email', 
                    'receiver.name as receiver_name', 
                    'receiver.email as receiver_email')
            ->get()[0];
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
