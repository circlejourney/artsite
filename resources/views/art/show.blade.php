@extends("layouts.site", ["metatitle" => $artwork->title])

@push('head')
@endpush

@section('body')
	<div class="page-block">
		<h1>{{ $artwork->title }} 	
		</h1>
		
		<div class="art-info">
			
			@include("components.fave-button")

			<div>
				Posted:
				<script>
					const posted = new Date({{ $artwork->created_at->timestamp }}*1000);
					document.write(posted.toDateString());
				</script>
			</div>
			{{ sizeof($owner_ids) > 1 ? "Artists:" : "Artist:" }}
			
			@foreach($artwork->users()->get() as $i => $owner)
					<a href="{{ route("user", ["username" => $owner->name]) }}">
						{!! $owner->getFlairHTML() !!}
						{{ $owner->name }}</a><!--
						-->@if(!$loop->last), @endif
			@endforeach

			@if(sizeof($folders) > 0)
			<div>
				@if(sizeof($folders) > 1)
				Folders:
				@else 
				Folder:
				@endif

				@foreach($folders as $folder)
					<a href="{{ route("user", ["username" => $folder->user()->first()->name]) }}">
						{{$folder->user()->first()->name}}</a>'s
					<a href="{{ route("folders.show", ["username" => $folder->user()->first()->name, "folder"=>$folder]) }}">
						{{ $folder->title }}</a><!--
					-->@if(!$loop->last), @endif
				@endforeach
			</div>
			@endif
			
			@if(isset($artwork->tags))
				<div class="tag-list">
					Tags: @component("tags.taglist", ["tags"=>$artwork->tags, "user" => $user ?? null]) @endcomponent
				</div>
			@endif

			@if($text)
			<div class="artwork-text">Description: {!! $text !!}</div>
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
	</div>
	
	<div class="art-display-container">
		@foreach($image_urls as $image_url)
			<a href="{{$image_url}}" target="_blank"><img class="art-display" src="{{$image_url}}"></a>
		@endforeach
	</div>
@endsection