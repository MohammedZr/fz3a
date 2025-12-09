<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chat = Chat::firstOrCreate([
            'user_id' => auth()->id()
        ]);

        $messages = $chat->messages()->with('sender')->get();

        return view('chat.index', compact('chat','messages'));
    }

    public function send(Request $request)
    {
        $request->validate(['message'=>'required']);

        $chat = Chat::where('user_id', auth()->id())->first();

        $chat->messages()->create([
            'sender_id' => auth()->id(),
            'message'   => $request->message
        ]);

        return response()->json(['success'=>true]);
    }

    public function fetch(Chat $chat)
    {
        return $chat->messages()->with('sender')->get();
    }
}
