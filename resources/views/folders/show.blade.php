@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>
				<a href="{{ route("user", ["username" => $user->name]) }}">
					{!! $user->getFlairHTML() !!} {{$user->name}}
				</a>'s Gallery<!--
			-->@if(!$folder->isTopFolder()): {{$folder->title}} @endif
		</h1>
		
		<div class="row">
		
		<div class="col-12 col-md-3">
			@include("folders.folderlist-display", ["user" => $user, "folderlist" => $folderlist, "selected" => $folder->id])
		</div>
		
		<div class="col">
			@forelse($folder->artworks as $artwork) 
				<div>
					<a href="{{ route("art", ["path" => $artwork->path]) }}">
						<img src="{{ $artwork->getThumbnailURL() }}">
						{{ $artwork->title }}
					</a>
				</div>
			@empty
				Folder is empty.
			@endforelse
		</div>
	</div>
@endsection