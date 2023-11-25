@extends("layouts.site")

@push("metatitle"){{ $artwork->title }}@endpush

@section('body')
	<div class="page-block">
		<h1>{{ $artwork->title }}</h1>
		
		{{ sizeof($owner_ids) > 1 ? "Artists:" : "Artist:" }}
		@foreach($artwork->users()->get() as $i=>$user)
				<a href="{{ route("user", ["username" => $user->name]) }}">
					{!! $user->getFlairHTML() !!}
					{{ $user->name }}</a><!--
					-->@if(!$loop->last), @endif
		@endforeach
		
		<div>
		@if($artwork->tags) Tags: @endif
		@forelse($artwork->tags as $tag)
			{{ $tag->pivot->tag_id }}<!--
		-->@if(!$loop->last), @endif
		@empty
			No tags found
		@endforelse
		</div>
		
		@if(sizeof($folders) > 0)
		<div>
			Inside folder(s):
			@foreach($folders as $folder)
				<a href="{{ route("user", ["username" => $folder->user()->first()->name]) }}">
					{{$folder->user()->first()->name}}</a>'s
				<a href="{{ route("folders.show", ["username" => $folder->user()->first()->name, "folder"=>$folder]) }}">
					{{ $folder->title }}</a><!--
				-->@if(!$loop->last), @endif
			@endforeach
		</div>
		@endif
		
	</div>
	<div class="art-display-container">
		@foreach($image_urls as $image_url)
			<img class="art-display" src="{{$image_url}}">
		@endforeach
	</div>
	
	<div class="art-info page-block">
		@if($text)
		<div class="artwork-text">{!! $text !!}</div>
		@endif
		
		<br>
		
		@if(auth()->check() && ($owner_ids->contains(auth()->user()->id)))
		<a class="button-pill" href="{{ route('art.edit', ['path' => $artwork->path]) }}">
			Edit artwork
		</a>
		<br>
		<a class="button-pill bg-danger" href="{{ route('art.delete', ['path' => $artwork->path]) }}">
			Delete artwork
		</a>
		@endif
	</div>
@endsection