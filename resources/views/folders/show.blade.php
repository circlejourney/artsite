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
			Back to {{ $folder->parent()->first()->getDisplayName() }}
		</a>
		@endif
		
		<div class="row">
		
		<div class="col-12 col-md-3">
			<h2>Folder tree</h2>
			<div>
				<div>
					<a href="{{ route("folders.index", ["username"=>$user->name]) }}">
						{{ $user->getTopFolder()->getDisplayName() }}
					</a>
				</div>
				@foreach($folderlist as $listfolder)
					<div>
						<a style="margin-left: {{ ($listfolder["depth"])*1.2 }}rem" href="{{ route("folders.show", ["username" => $user->name, "folder" => $listfolder["id"]]) }}"
							@if($listfolder["id"] == $folder->id) class="font-weight-bold" @endif>
							&#x2937;{{ $listfolder["title"] }}
						</a>
					</div>
				@endforeach
			</div>
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