@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>{{$user->name}}'s Gallery: All</h1>
		<div class="row">
			<div class="col-12 col-md-3">
				@include("folders.folderlist-display", ["user"=>$user, "folderlist" => $folderlist, "selected" => $user->top_folder_id])
			</div>
			<div class="col">
				@foreach($artworks as $artwork)
					<a href="{{ route("art", ["path" => $artwork->path]) }}">
						<img src="{{ $artwork->getThumbnailURL() }}">
						{{ $artwork->title }}
					</a>
				@endforeach
			</div>
	</div>
@endsection