@extends("layouts.site")

@section('body')
	<h1>{{ $user->name }}'s tags</h1>
	@include('tags.taglist', ["user" => $user, "tags" => $tags])
@endsection