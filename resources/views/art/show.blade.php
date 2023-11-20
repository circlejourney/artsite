@extends("layouts.site")

@push("metatitle"){{ $artwork->title }}@endpush

@section('body')
	@foreach($image_urls as $image_url)
		<img src="{{$image_url}}">
	@endforeach
	
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
@endsection