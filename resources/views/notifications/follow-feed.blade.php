@extends("layouts.site")

@section('sidebar')
	<div class="sidebar">
		@include('notifications.notification-menu', ["active" => "follow-feed"])
	</div>
@endsection

@section('body')
	<h1>Artists You Follow</h1>

	<div class="wide-gallery row">
		@foreach($artworks as $artwork)
		<div class="d-flex col-12 col-md-6 p-2">
			<a class="gallery-thumbnail" href="{{ route("art", $artwork->path) }}">
				<img src="{{ $artwork->getThumbnailURL() }}">
			</a>
			<div class="gallery-description p-2 d-flex flex-column justify-content-center">
				<a href="{{ route("art", $artwork->path) }}">
					<h2 class="no-margin">{{ $artwork->title }}</h2>
				</a>
				<div class="small text-muted">{{ $artwork->created_at->diffForHumans() }}</div>
				<div>
					@foreach($artwork->users as $artist)
						{!! $artist->getNametag() !!}
					@endforeach
				</div>
				<div>
					{!! Str::of($artwork->getText())->words(20) !!}
				</div>
			</div>
		</div>
		@endforeach
	</div>
@endsection