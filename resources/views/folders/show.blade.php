@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>
				<a href="{{ route("user", ["username" => $user->name]) }}">
					{!! $user->getFlairHTML() !!} {{$user->name}}
				</a>'s Gallery<!--
			-->@if(!$folder->isTopFolder()): {{$folder->title}} @endif
		</h1>
		
		@if($folder->parent)
		<a href="{{
			$folder->parent()->first()->isTopFolder() ?
				route("folders.index", ["username" => $user->name])
				:
				route("folders.show", ["username" => $user->name, "folder" => $folder->parent])
		}}">
			Back to {{ $folder->parent()->first()->getFolderDisplayName() }}
		</a>
		@endif
		
		@if($folder->children)
		<h2>Subfolders</h2>
		<ul>
			@foreach($folder->children as $child)
				<li>
					<a href="{{ route("folders.show", ["username" => $user->name, "folder" => $child->id]) }}">
					{{ $child->title }}
					</a>
				</li>
			@endforeach
		</ul>
		@endif
		
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
@endsection