<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Exception;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;

class ReviewController extends Controller
{
    protected function updateOrCreate(array $arr)
    {
        $review = Review::where('sender_id', $arr['sender_id'])
        ->where('receiver_id', $arr['receiver_id'])
        ->first();
        if ($review == null)
            $review = new Review;
        $review->fill($arr);
        $review->save();
        return $review;
    }

    public function createReview(Request $request)
    {
        try {
            $data = $request->all();
            $data['sender_id'] = auth()->user()->user_id;
            $review = self::updateOrCreate($data);
            return response()->json([
                'success' => true,
                'message' => 'Tao review thanh cong',
                'data' => $review,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tao review khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateReview(Request $request)
    {
        try {
            $data = $request->all();
            if (!$request->has('sender_id'))
                $data['sender_id'] = auth()->user()->user_id;
            $review = Review::where('sender_id', $data['sender_id'])
            ->where('receiver_id', $data['receiver_id'])
            ->first();
            if ($review == null)
                throw new Exception('Review khong ton tai');
            if (auth()->user()->role != 'admin' &&
                $review->sender_id != auth()->user()->user_id)
                throw new Exception('Nguoi dung khong the chinh sua review nay');
            $review = self::updateOrCreate($data);
            return response()->json([
                'success' => true,
                'message' => 'Chinh sua review thanh cong',
                'data' => $review,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chinh sua review khong thanh cong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function countReviewstoUser($receiver_id)
    {
        try {
            $count = Review::
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

    public function countReviewsfromUser($sender_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                auth()->user()->user_id != $sender_id)
                throw new Exception('Nguoi dung khong the dem so luong review duoc gui boi nguoi dung '.$sender_id);
            $count = Review::
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

    public function showReviewstoUser($receiver_id)
    {
        try {
            $reviews = Review::orderByDesc('created_at')
            ->where('receiver_id', $receiver_id)
            ->join('users as sender', 'sender.user_id', '=', 'sender_id')
            ->join('users as receiver', 'receiver.user_id', '=', 'receiver_id')
            ->select('reviews.*', 
                    'sender.name as sender_name', 
                    'sender.email as sender_email', 
                    'receiver.name as receiver_name', 
                    'receiver.email as receiver_email')
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tat ca phai hoi ve nguoi dung '.$receiver_id.' thanh cong',
                'data' => $reviews,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi tim kiem tat ca phai hoi ve nguoi dung '.$receiver_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showReviewsfromUser($sender_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                auth()->user()->user_id != $sender_id)
                throw new Exception('Nguoi dung khong the xem review duoc gui boi nguoi dung '.$sender_id);
            $reviews = Review::orderByDesc('created_at')
            ->where('sender_id', $sender_id)
            ->join('users as sender', 'sender.user_id', '=', 'sender_id')
            ->join('users as receiver', 'receiver.user_id', '=', 'receiver_id')
            ->select('reviews.*', 
                    'sender.name as sender_name', 
                    'sender.email as sender_email', 
                    'receiver.name as receiver_name', 
                    'receiver.email as receiver_email')
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tat ca phai hoi tu nguoi dung '.$sender_id.' thanh cong',
                'data' => $reviews,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi tim kiem tat ca phai hoi tu nguoi dung '.$sender_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showAllReviews()
    {
        try {
            UserController::checkrole('admin');
            $reviews = Review::orderByDesc('created_at')
            ->join('users as sender', 'sender.user_id', '=', 'sender_id')
            ->join('users as receiver', 'receiver.user_id', '=', 'receiver_id')
            ->select('reviews.*', 
                    'sender.name as sender_name', 
                    'sender.email as sender_email', 
                    'receiver.name as receiver_name', 
                    'receiver.email as receiver_email')
            ->paginate(20);
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem tat ca phai hoi tren he thong thanh cong',
                'data' => $reviews,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi tim kiem tat ca phai hoi trong he thong',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showReview($review_id)
    {
        try {
            $review = Review::findOrFail($review_id);
            if (auth()->user()->role != 'admin' &&
                auth()->user()->user_id != $review->sender_id)
                throw new Exception('Nguoi dung khong the xem review '.$review_id);
            $review = Review::where('review_id', $review_id)
            ->join('users as sender', 'sender.user_id', '=', 'reviews.sender_id')
            ->join('users as receiver', 'receiver.user_id', '=', 'reviews.receiver_id')
            ->select('reviews.*', 
                    'sender.name as sender_name', 
                    'sender.email as sender_email', 
                    'receiver.name as receiver_name', 
                    'receiver.email as receiver_email')
            ->get()[0];
            return response()->json([
                'success' => true,
                'message' => 'Tim kiem phai hoi '.$review_id.' tren he thong thanh cong',
                'data' => $review,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xay ra loi khi tim kiem phai hoi '.$review_id.' trong he thong',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
