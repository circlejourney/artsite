@extends('layouts.site')

@section('body')
	<h1>Create message</h1>
	<form method="POST">
		@csrf
		Recipient
		<input type="text" name="recipient" id="recipient" class="form-control" value="{{ old("recipient", $recipientName ?? "") }}">
		Subject
		<input type="text" name="subject" id="subject" class="form-control" value="{{ old("subject") }}">
		Message
		<textarea name="content" class="form-control"></textarea>
		<button class="button-pill">Send</button>
	</form>
@endsection