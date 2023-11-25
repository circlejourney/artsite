@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>
			<a href="{{ route("user", ["username" => $user->name]) }}">
				{!! $user->getFlairHTML() !!} {{$user->name}}
			</a>'s Gallery
		</h1>

		@include("folders.folderlist-display", ["user"=>$user, "folderlist" => $folderlist, "selected" => $user->top_folder_id])
		@include("tags.taglist-display", ["user" => $user, "tags" => $tags])
		
		@foreach($artworks as $artwork)
			<a href="{{ route("art", ["path" => $artwork->path]) }}">
				<img src="{{ $artwork->getThumbnailURL() }}">
				{{ $artwork->title }}
			</a>
		@endforeach
	</div>
@endsection