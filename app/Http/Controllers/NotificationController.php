<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::simplePaginate(10);
        return response()->json($notifications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notification = new Notification;
        $notification->title = $request->input('title');
        $notification->detail = $request->input('detail');
        $notification->status = $request->input('status');
        $notification->receiver_id = $request->input('receiver_id');
        $notification->save();
        return response()->json($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::find($id);
        return response()->json($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $notification = Notification::find($id);
        $notification->title = $request->input('title');
        $notification->detail = $request->input('detail');
        $notification->status = $request->input('status');
        $notification->receiver_id = $request->input('receiver_id');
        $notification->save();
        return response()->json($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $notification = Notification::find($id)->delete;
        // Notification::truncate();
        // return response()->json($notification);
    }

    /**
     * Return all notifications user $user_id received in lastest create time order.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function showUserNoti($user_id)
    {
        $alluser_id = 0;
        $notifications = Notification::orderBy('created_at', 'desc')
            ->where('receiver_id', $user_id)
            ->orWhere('receiver_id', $alluser_id)
            ->simplePaginate(10);
        return response()->json($notifications);
    }
}
