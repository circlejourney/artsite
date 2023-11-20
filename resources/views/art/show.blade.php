@extends("layouts.site")

@push("metatitle"){{ $artwork->title }}@endpush

@section('body')
	<div class="page-block">
		<h1>{{ $artwork->title }}</h1>
	</div>
	<div class="art-display-container">
		@foreach($image_urls as $image_url)
			<img class="art-display" src="{{$image_url}}">
		@endforeach
	</div>
	
	<div class="art-info page-block">
		@if(sizeof($owner_ids) > 1) Artists:
		@else Artist:
		@endif
		
		@foreach($artwork->users()->get() as $user)
			<a href="{{ route("user", ["username" => $user->name]) }}">{{ $user->name }}</a>
		@endforeach

		@if($owner_ids->contains(Auth::user()->id))
		<a class="button-pill bg-danger" href="{{ route('art.delete', ['path' => $artwork->path]) }}">
			Delete artwork
		</a>
		@endif
	</div>
@endsection