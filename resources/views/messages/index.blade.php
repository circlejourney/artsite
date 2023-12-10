@extends('layouts.site')

@section('body')
	<h1>Messages</h1>
	<div class="nav nav-pills">
		<a class="nav-link" href="{{ route("messages") }}">Inbox</a>
		<a class="nav-link" href="{{ route("messages.outbox") }}">Outbox</a>
	</div>
	<div class="list-group">
	@forelse($messages as $message)
		<div class="list-group-item mail-item @if($message->recipient==auth()->user() && !$message->read) unread @endif">
			@if($message->recipient == auth()->user())
				{!! $message->read ? "<i class='fa fa-envelope-open'></i>" : "<i class='fa fa-envelope'></i>" !!}
			@else
				<i class="fa fa-arrow-right-from-bracket"></i>
			@endif
			{!! $message->sender == auth()->user() ? "You" : $message->sender->getNametag() !!}
			to
			{!! $message->recipient == auth()->user() ? "you" : $message->recipient->getNametag() !!}:
				
			<a href="{{ route("messages.show", ["message" => $message]) }}">
				{{ $message->subject ?? "[No subject]" }}
			</a>
		</div>
	@empty
		No messages found.
	@endforelse
	</div>
@endsection