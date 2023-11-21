@extends("layouts.site")

@push("metatitle"){{ $artwork->title }}@endpush

@section('body')
	<div class="page-block">
		<h1>{{ $artwork->title }}</h1>
		
		@if(sizeof($owner_ids) > 1) Artists: @else Artist: @endif
		
		@foreach($artwork->users()->get() as $i=>$user)
			@if(!$loop->last)
				<a href="{{ route("user", ["username" => $user->name]) }}">{{ $user->name }}</a>,
			@else
				<a href="{{ route("user", ["username" => $user->name]) }}">{{ $user->name }}</a>
			@endif
		@endforeach
		
	</div>
	<div class="art-display-container">
		@foreach($image_urls as $image_url)
			<img class="art-display" src="{{$image_url}}">
		@endforeach
	</div>
	
	<div class="art-info page-block">
		@if($artwork->text)
		<div class="artwork-text">{!! $artwork->text !!}</div>
		@endif
		
		<br>
		
		@if(Auth::check() && $owner_ids->contains(Auth::user()->id))
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