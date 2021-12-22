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
                'message' => 'Tạo review thành công',
                'data' => $review,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo review không thành công',
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
                throw new Exception('Review không tồn tại');
            if (auth()->user()->role != 'admin' &&
                $review->sender_id != auth()->user()->user_id)
                throw new Exception('Người dùng không thể chỉnh sửa review này');
            $review = self::updateOrCreate($data);
            return response()->json([
                'success' => true,
                'message' => 'Chỉnh sửa review thành công',
                'data' => $review,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉnh sửa review không thành công',
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
                'message' => 'Đếm số phản hồi về người dùng '.$receiver_id.' thành công',
                'data' => $count,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xảy ra lỗi khi đếm số phản hồi về người dùng '.$receiver_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function countReviewsfromUser($sender_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                auth()->user()->user_id != $sender_id)
                throw new Exception('Người dùng không thể đếm số lương review được gửi bởi người dùng '.$sender_id);
            $count = Review::
            where('sender_id', $sender_id)
            ->where('status', '!=', 'hidden')
            ->count();
            return response()->json([
                'success' => true,
                'message' => 'Đếm số review từ người dùng '.$sender_id.' thành công',
                'data' => $count,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xảy ra lỗi khi đếm số lượng review từ người dùng '.$sender_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showReviewstoUser($receiver_id)
    {
        try {
            $reviews = Review::orderByDesc('updated_at')
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
                'message' => 'Tìm kiếm tất cả review về người dùng '.$receiver_id.' thành công',
                'data' => $reviews,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xảy ra lỗi khi tìm kiếm tất cả review về người dùng '.$receiver_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showReviewsfromUser($sender_id)
    {
        try {
            if (auth()->user()->role != 'admin' && 
                auth()->user()->user_id != $sender_id)
                throw new Exception('Người dùng không thể xem review được gửi từ người dùng '.$sender_id);
            $reviews = Review::orderByDesc('updated_at')
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
                'message' => 'Tìm kiếm tất cả review từ người dùng '.$sender_id.' thành công',
                'data' => $reviews,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xảy ra lỗi khi tìm kiếm tất cả review từ người dùng '.$sender_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showAllReviews()
    {
        try {
            UserController::checkrole('admin');
            $reviews = Review::orderByDesc('updated_at')
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
                'message' => 'Tìm kiếm tất cả review trên hệ thống thành công',
                'data' => $reviews,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xảy ra lỗi khi tìm kiếm tất cả review trên hệ thống',
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
                throw new Exception('Người dùng không thể xem review '.$review_id);
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
                'message' => 'Tìm kiếm phản hồi '.$review_id.' trên hệ thống thành công',
                'data' => $review,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xảy ra lỗi khi tìm kiếm phản hồi '.$review_id.' trên hệ thống',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
