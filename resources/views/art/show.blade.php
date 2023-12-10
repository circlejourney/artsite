@extends("layouts.site", ["metatitle" => $artwork->title])

@push('head')
	<meta property="og:image" content="{{ $artwork->getThumbnailURL() }}">
	<meta property="og:description" content="{{ $artwork->title }} by {{ $artwork->users->pluck("name")->join(", ") }} on {{ config("app.name") }}">
@endpush

@section('body')
	<h1>{{ $artwork->title }} 	
	</h1>
	<div class="art-info row mb-2">
		<div class="col-12 col-md-6">
			@auth
				@include("components.fave-button")
			@endauth

			<div>
				Posted: <span class="format-date" data-timestamp="{{ $artwork->created_at->timestamp}}"></span>
			</div>
			{{ sizeof($owner_ids) > 1 ? "Artists:" : "Artist:" }}
			
			@foreach($artwork->users()->get() as $i => $owner)
				{!! $owner->getNametag() !!}<!--
				-->@if(!$loop->last), @endif
			@endforeach

			@if(sizeof($folders) > 0)
			<div>
				@if(sizeof($folders) > 1)
				Folders:
				@else 
				Folder:
				@endif

				@foreach($folders as $listfolder)
					<a href="{{ route("user", ["username" => $listfolder->user()->first()->name]) }}">
						{{$listfolder->user()->first()->name}}</a>'s
					<a href="{{ route("folders.show", ["username" => $listfolder->user()->first()->name, "folder"=>$listfolder]) }}">
						{{ $listfolder->title }}</a><!--
					-->@if(!$loop->last), @endif
				@endforeach
			</div>
			@endif
			
			@if($artwork->tags)
			<div class="tag-list">
				Tags -
					@foreach($artwork->users as $user) 
						@if(($thistags = $artwork->tags->where("user_id", $user->id))->count() > 0)
							{{$user->name}}: @include("tags.taglist", ["tags"=>$thistags])
						@endif
					@endforeach
				</div>
			@endif
		</div>

		<div class="col-12 col-md-6 text-right">
			@if(auth()->check() && ($owner_ids->contains(auth()->user()->id)))
			<a class="button-pill" href="{{ route('art.edit', ['path' => $artwork->path]) }}">
				Edit artwork
			</a>
			<a class="button-pill bg-danger" href="{{ route('art.delete', ['path' => $artwork->path]) }}">
				Delete artwork
			</a>
			@endif
		</div>
	</div>
	
	<div class="art-display-container">
		@if($text)
		<div class="artwork-text w-100 large">
			{!! $text !!}
		</div>
		@endif

		@foreach($image_urls as $image_url)
			<a href="{{$image_url}}" target="_blank"><img class="art-display" src="{{$image_url}}"></a>
		@endforeach
	</div>
@endsection