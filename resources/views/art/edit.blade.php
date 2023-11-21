@extends("layouts.site")

@push("head")
	<script>
		function addImageInput() {
			if($("input-wrapper").length < 9) $("#image-inputs").append("<div class=\"input-wrapper\"><input type=\"file\" name=\"images[]\"></input></div>");
		}
	</script>
@endpush

@section('body')
<div class="p-4">
	<h1>Edit '{{ $artwork->title }}'</h1>

	<form method="POST" enctype="multipart/form-data" class="row">
		@method("PUT")
		@csrf
		<div class="col-auto">
			<div id="image-inputs" class="flex-column">
				@foreach($image_urls as $image_url)
					<div class="image-input-wrapper">
						<input type="file" name="images[]" onchange="updatePreview(this, $(this).siblings('.image-preview')[0])">
						<img class="image-preview" src="{{ $image_url }}">
					</div>
				@endforeach
			</div>
			<a class='button-pill' onclick="addImageInput()">+</a>
		</div>
		<div class="col">
			<input class="form-control" type="text" name="title" placeholder="Title" value="{{ old('title', $artwork->title ) }}">
			<textarea class="form-control" name="text" placeholder="HTML text">{{ old('title', $artwork->text ) }}</textarea>
			@foreach( $artwork->users()->get()->filter(function($user) {
				return Auth::check() && $user->id !== Auth::user()->id;
			}) as $user)
				<input class="form-control" type="text" name="artist[]" placeholder="Collaborator" value="{{ $user["name"] }}">
			@endforeach
			<button class='button-pill'>Submit</button>
		</div>
	</form>

</div>
@endsection