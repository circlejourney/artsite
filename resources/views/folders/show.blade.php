@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>
			<a href="{{ route("user", ["username" => $user->name]) }}">
				{!! $user->getFlairHTML() !!} {{$user->name}}
			</a>'s Gallery: {{$folder->getDisplayName() }}
		</h1>
		
		@include("folders.folderlist", ["user" => $user, "folderlist" => $folderlist, "selected" => $folder->id, "tag" => $tag])
		@include("tags.taglist-display", ["user" => $user, "folder" => $folder, "tags" => $tags])
		
		@include("layouts.gallery", ["artworks" => $artworks])
	</div>
@endsection