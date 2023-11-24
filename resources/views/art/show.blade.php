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
		
		<?php $folders = $artwork->folders->reject(function($i){ return $i->id == $i->user()->first()->top_folder_id; }) ?>
		@if(sizeof($folders) > 0)
		<div>
			Inside folder(s):
			@foreach($folders as $folder)
				<a href="{{ route("user", ["username" => $folder->user()->first()->name]) }}">
					{!! $folder->user()->first()->getFlairHTML() !!} {{$folder->user()->first()->name}}</a>'s
				<a href="{{ route("folders.show", ["username" => $folder->user()->first()->name, "folder"=>$folder]) }}">
					{{ $folder->title }}
				</a><!--
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
		
		@if(auth()->check() && ($owner_ids->contains(auth()->user()->id) || auth()->user()->hasPermissions("manage_artworks")))
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