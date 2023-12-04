@extends('layouts.site')

@section('body')
	<h1>Messages</h1>
	@forelse($messages as $message)
	@empty
		No messages found.
	@endforelse
@endsection