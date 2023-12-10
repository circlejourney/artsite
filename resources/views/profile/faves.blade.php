@extends("layouts.profile", ["user" => $user, "metatitle" => $user->name . "'s Favorites"]);

@section('profile-body')
	<h1>{{ $user->name }}'s Favorites</h1>
	<div class="gallery">
		@foreach($user->faves()->orderBy("created_at", "desc")->get() as $fave)
		<a class="gallery-thumbnail" href="{{ route("art", ["path" => $fave->path ]) }}">
			<img src="{{ $fave->getThumbnailURL() }}">
			{{ $fave->users()->pluck("name")->join(", ") }} - {{ $fave->title }}
		</a>
		@endforeach
	</div>
@endsection