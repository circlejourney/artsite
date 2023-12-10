@extends("layouts.profile", ["user" => $user])

@section('profile-body')
	<h1>{{ $user->name }}'s tags</h1>
	@include('tags.taglist', ["user" => $user, "tags" => $tags])
@endsection