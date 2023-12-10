@extends('layouts.site')

@section('body')
	<h1>Message</h1>
	<div>Subject:</div>
	<h2 class="no-margin">{{ $message->subject }}</h2>
	{{ $message->sender == auth()->user() ? "You" : $message->sender->getNametag() }} said to {{ $message->recipient == auth()->user() ? "you" : $message->recipient->getNametag() }}:
	<div class="card">
		<div class="card-body">
			{!! $message->content !!}
		</div>
	</div>

	@unless($message->sender == auth()->user())
		<h2>Reply</h2>
		<form method="POST">
			@csrf
			<input type="hidden" name="recipient" value="{{ $message->sender->name }}">
			<input type="hidden" name="subject" value="{{ $message->subject }}">
			Subject
			<input type="text" name="subject" id="subject" class="form-control" disabled value="{{ $message->subject }}">
			<input type="checkbox" id="change-subject" onchange="$('#subject')[0].toggleAttribute('disabled', !this.checked)">
			<label for="change-subject">Change subject</label>
			<br>
			Message
			<textarea name="content" class="form-control"></textarea>
			<button class="button-pill">Send</button>
		</form>
	@endunless
	
	<h2>Message history: "{{ $message->subject }}"</h2>
	@forelse($messageHistory as $oldmessage)
		<div @if($oldmessage->id == $message->id) class="font-weight-bold" @endif>
			{!! $oldmessage->sender->getNametag() !!}:
			{!! $oldmessage->content !!}
		</div>
	@empty
	@endforelse

@endsection