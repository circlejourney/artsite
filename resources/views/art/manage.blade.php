@extends("layouts.site", ["metatitle" => "Manage Artworks"])
@section('body')
<h1>Manage Artworks</h1>
<form method="POST">
	@csrf
	@method("PUT")
	<button>Update</button>
	<div class="gallery">
	@foreach($user->artworks as $artwork)
		<div class="gallery-thumbnail">
			<img src="{{ $artwork->getThumbnailURL() }}">
			<a href="{{ route("art", ["path" => $artwork->path]) }}">
                View artwork
			</a>
			<div>
				<input type="hidden" name="set_highlight[{{ $artwork->id }}]" value="0">
				<input type="checkbox" name="set_highlight[{{ $artwork->id }}]" value="1" id="set-highlight-{{ $artwork->id }}"
					@checked(collect($user->highlights)->contains($artwork->id))>
				<label for="set-highlight-{{ $artwork->id }}">
					Set as highlight
				</label>
			</div>
			
			<div>
				<input type="hidden" name="set_searchable[{{ $artwork->id }}]" value="0">
				<input type="checkbox" name="set_searchable[{{ $artwork->id }}]" value="1" id="set-searchable-{{ $artwork->id }}"
					@checked(!$artwork->searchable)>
				<label for="set-searchable-{{ $artwork->id }}">
					Hide from global tag searches
				</label>
			</div>
		</div>
	@endforeach
	</div>
	<button>Update</button>
</form>
@endsection