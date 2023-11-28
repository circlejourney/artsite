@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name . "'s Stats"]);

@section('profile-body')
	<h1>{{$user->name}}'s Stats</h1>
	<div class="list-group">
		<div class="list-group-item">Artwork count: {{ ($artworks = $user->artworks)->count() }}</div>
		<div class="list-group-item">Collab count: {{ $artworks->filter(function($i) use($user) { return $i->users->count() > 1; })->count() }}</div>
		<div class="list-group-item">Invited users: {{ $user->invitees->count() }}</div>
	</div>
@endsection