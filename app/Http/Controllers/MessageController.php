<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::simplePaginate(10);
        return response()->json($messages);
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
        $message = new Message;
        $message->title = $request->input('title');
        $message->detail = $request->input('detail');
        $message->status = $request->input('status');
        $message->sender_id = $request->input('sender_id');
        $message->receiver_id = $request->input('receiver_id');
        $message->save();
        return response()->json($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = Message::find($id);
        return response()->json($message);
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
        $message = Message::find($id);
        $message->title = $request->input('title');
        $message->detail = $request->input('detail');
        $message->status = $request->input('status');
        $message->sender_id = $request->input('sender_id');
        $message->receiver_id = $request->input('receiver_id');
        $message->save();
        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show message from user to user.
     *
     * @param  int  $sender_id, $receiver_id
     * @return \Illuminate\Http\Response
     */
    public function showUsersMessage($sender_id, $receiver_id)
    {
        $notifications = Message::orderBy('created_at', 'desc')
            ->where('sender_id', $sender_id)
            ->where('receiver_id', $receiver_id)
            ->simplePaginate(10);
        dd($notifications);
        return response()->json($notifications);
    }

    /**
     * Count number of reports an user received.
     *
     * @param  int  $receiver_id
     * @return \Illuminate\Http\Response
     */
    public function reportCount($receiver_id)
    {
        $report_counter = Message::where('title', "report")
            ->where('receiver_id', $receiver_id)
            ->count();
        return response()->json($report_counter);
    }

    /**
     * Show reports an user received.
     *
     * @param  int  $receiver_id
     * @return \Illuminate\Http\Response
     */
    public function showReports($receiver_id)
    {
        $reports = Message::where('title', "report")
            ->where('receiver_id', $receiver_id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($reports);
    }
}
