@extends("layouts.site", ["metatitle" => "Submit New Artwork"])
@push("head")
	<script src="/src/extend_form.js"></script>
	<script src="/src/sortable/Sortable.js"></script>
	<script>
		$(window).on("load", function(){
			const imageInputs = $("#image-inputs")[0];
			const sortable = new Sortable(imageInputs, {
				animation: 150,
				ghostClass: 'dragging',
				handle: '.image-drag-handle'
			});
		});

		function addImageWithDelete() {
			$( addImageInput('.image-input-wrapper', '#image-inputs', 5) )
				.find(".image-delete").html('<a onclick=\'this.closest(".image-input-wrapper").remove();\'><i class="fa fa-fw fa-times"></i></a>');
		}
	</script>
@endpush

@section('body')
<div class="page-block">
	<h1>Submit New Artwork</h1>
	<form method="POST" enctype="multipart/form-data">
		@csrf
		<div>
			<div id="image-inputs" class="flex-column">
				<div class="image-input-wrapper">
					<div class="image-buttons image-drag-handle">
						<i class="fa fa-arrows"></i>
					</div>

					<input type="file" name="images[]" onchange="updatePreview(this, $(this).siblings('.image-preview')[0])" value="{{ old('images.0') }}">
					<img class="image-preview"
						@if(old('images.0'))
							src="{{ old('images.0') }}"
						@endif>
						
					<div class="image-buttons image-delete">
						<i class="fa fa-empty fa-fw"></i>
					</div>
				</div>
			</div>
			<a class='button-pill' onclick="addImageWithDelete()">+</a>
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