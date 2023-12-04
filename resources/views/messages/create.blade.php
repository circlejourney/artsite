@extends('layouts.site')

@section('body')
	<h1>Message {{ $recipient->name }}</h1>
	@if($previousMessage) {{ $previousMessage->content }} @endif
	<form method="POST">
		@csrf
		<input type="text" name="subject" id="subject" class="form-control" @if($previousMessage) disabled value="{{ $previousMessage->subject }}" @endif>
		@if($previousMessage)
			<input type="checkbox" id="change-subject" onchange="$('#subject')[0].toggleAttribute('disabled', !this.checked)"> Change subject
		@endif
		<textarea name="content" class="form-control"></textarea>
		<button class="button-pill">Send</button>
	</form>
@endsection