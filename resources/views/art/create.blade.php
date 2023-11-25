@extends("layouts.site", ["metatitle" => "Submit New Artwork"])
@push("head")
	<script src="/src/extend_form.js"></script>
@endpush

@section('body')
<div class="page-block">
	<h1>Submit New Artwork</h1>
	<form method="POST" enctype="multipart/form-data">
		@csrf
		<div>
			<div id="image-inputs" class="flex-column">
				<div class="image-input-wrapper">
					<input type="file" name="images[]" onchange="updatePreview(this, $(this).siblings('.image-preview')[0])" value="{{ old('images.0') }}">
					<img class="image-preview" src="{{ old('images.0') }}">
					<a onclick="this.closest('.image-input-wrapper').remove()">x</a>
				</div>
			</div>
			<a class='button-pill' onclick="addImageInput('.image-input-wrapper', '#image-inputs', 5)">+</a>
			<input class="form-control" type="text" name="title" placeholder="Title" value="{{ old('title') }}" required>
			<textarea class="form-control" name="text" placeholder="HTML text">{{ old('text') }}</textarea>
			
			@include("components.folder-select", ["folderlist" => $folderlist])

			<div id="artist-inputs">
				<input class="artist-input form-control" type="text" name="artist[]" value="{{ old('artist.0') }}" placeholder="Collaborator">
			</div>
			<a class='button-pill' onclick="addTextInput('.artist-input', '#artist-inputs', 5)">+</a>
			<input class="form-control" name="tags" id="tags" placeholder="Tags (comma-separated)" value="{{ old("tags" ) }}">
			<br>
			<button class='button-pill'>Submit</button>
		</div>
	</form>
</div>
@endsection