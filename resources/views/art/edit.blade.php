@extends("layouts.site", ["metatitle" => "Edit ".$artwork->title])

@push("head")
	<script src="/src/extend_form.js"></script>
	<script src="/src/ace/ace.js"></script>
	<script src="/src/sortable/Sortable.js"></script>

	<script>
		function toggleImageDelete(element){
			if($(element).siblings(".image_order").val() == "false" && !$(element).siblings("input[type='file']").val()) {
				element.parentElement.remove();
			}

			const newval = $(element).find('input').val() == "false";
			$(element).find('input').val(newval);
			$(element).closest('.image-input-wrapper').toggleClass('deselected', newval);
		}
		$(window).on("load", function(){
			const imageInputs = $("#image-inputs")[0];
			const sortable = new Sortable(imageInputs, {
				animation: 150,
				ghostClass: 'dragging',
				handle: '.image-drag-handle'
			});
			const editor = startAceEditor("#editor", "#text", "#html-preview");
		})
	</script>

@endpush

@section('body')
<div class="page-block">
	<h1>Edit '{{ $artwork->title }}'</h1>

	<form method="POST" enctype="multipart/form-data">
		@method("PUT")
		@csrf
		<div>
			<div id="image-inputs" class="flex-column">
				@foreach($image_urls as $i=>$image_url)
					<div class="image-input-wrapper">
						<div class="image-buttons image-drag-handle">
							<i class="fa fa-arrows"></i>
						</div>

						<input type="file" name="images[]" onchange="updatePreview(this, $(this).siblings('.image-preview')[0])">
						<input type="hidden" class="image_order" name="image_order[]" value="{{$i}}">
						<img class="image-preview" src="{{ $image_url }}">

						<div class="image-buttons image-delete" onclick="toggleImageDelete(this)">
							<input type="hidden" class="delete_image" name="delete_image[]" value="false">
							<i class="fa fa-times fa-fw"></i>
						</div>
					</div>
				@endforeach
			</div>
			<a class='button-pill' onclick="addImageInput('.image-input-wrapper', '#image-inputs')">+</a>

			<input class="form-control" type="text" name="title" placeholder="Title" value="{{ old('title', $artwork->title ) }}">
			<div id="editor"></div>
			<input type="hidden" id="text" name="text" value="{{ old('text', $text) }}">
			@include("components.folder-select", ["folderlist" => $folderlist, "selected" => $selectedfolder])

			<div id="artist-inputs">
				@forelse( $artwork->users()->get() as $i=>$user)
					<input class="form-control artist-input" type="text" name="artist[]" placeholder="Collaborator" value="{{ old("artist.".$i, $user["name"]) }}">
				@empty
					<input class="form-control artist-input" type="text" name="artist[]" placeholder="Collaborator">
				@endforelse
			</div>
			<a class='button-pill' onclick="addTextInput('.artist-input', '#artist-inputs', 5)">+</a>

			<input class="form-control" name="tags" id="tags" placeholder="Tags (comma-separated)" value="{{ old("tags", $artwork->getJoinedTags() ) }}">
			<br>
			<button class='button-pill'>Submit</button>
		</div>
	</form>

	<h2>Preview</h2>
	<div id="html-preview"></div>
	
</div>
@endsection