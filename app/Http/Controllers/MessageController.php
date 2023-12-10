<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Services\SanitiseService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
		$outbox = strpos($request->route()->uri(), "/outbox") !== false;
		if($outbox) $messages = Message::where("sender_id", auth()->user()->id)->orderBy("created_at", "desc")->get();
		else $messages = Message::where("recipient_id", auth()->user()->id)->orderBy("created_at", "desc")->get();
		return view("messages.index", ["messages" => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $username=null)
    {
		return view("messages.create", ["recipientName" => $username]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Message $message=null, string $username=null)
    {
		$user = User::where("name", $username ?? $request->recipient)->first();
		if($user === null) return redirect()->back()->withErrors("Recipient user does not exist.");
		if($user->id == $request->user()->id) return redirect()->back()->withErrors("You cannot send a message to yourself.");
        $message = Message::create([
			"sender_id" => $request->user()->id,
			"recipient_id" => $user->id,
			"subject" => $request->subject,
			"content" => $request->content
		]);
		return redirect(route("messages.show", ["message" => $message]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
		$subject = SanitiseService::of($message->subject ?? "")->makeTag()->get();

		$messageHistory = Message::where([
			["sender_id", "=", $message->sender->id],
			["recipient_id", "=", $message->recipient->id]
		])->orWhere([
			["sender_id", "=", $message->recipient->id],
			["recipient_id", "=", $message->sender->id]
		])->get()
		->filter(function($i) use($subject) {
			return SanitiseService::of($i->subject ?? "")->makeTag()->get() == $subject;
		});
		if(auth()->user() == $message->recipient) $message->update(["read" => true]);
        return view("messages.show", ["message" => $message, "messageHistory" => $messageHistory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
