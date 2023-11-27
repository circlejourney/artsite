@extends("layouts.site", ["metatitle" => "Manage Artworks"])
@section('body')
<h1>Manage Artworks</h1>
<form method="POST">
	@csrf
	@method("PUT")
	<select name="transformation">
		<option value="set_highlight">Set as highlights</option>
	</select>
	<button>Update</button>
	<br>
	@foreach($user->artworks as $artwork)
		<input type="checkbox" name="update_art[]" value="{{ $artwork->id }}" id="update-art-{{ $artwork->id }}"
			@checked(collect($user->highlights)->contains($artwork->id))>
		
		<label for="update-art-{{ $artwork->id }}">
			<img src="{{ $artwork->getThumbnailURL() }}">
		</label>
	@endforeach

</form>
@endsection