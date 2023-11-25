@extends("layouts.site")

@push("metatitle"){{ "Edit ".$artwork->title }}@endpush

@push("head")
	<script src="/src/extend_form.js"></script>
	<script>
		function toggleImageDelete(element){
			const newval = $(element).find('input').val() == "false";
			$(element).find('input').val(newval);
			$(element).closest('.image-input-wrapper').toggleClass('deselected', newval);
		}
	</script>
@endpush

@section('body')
<div class="p-4">
	<h1>Edit '{{ $artwork->title }}'</h1>

	<form method="POST" enctype="multipart/form-data" class="row">
		@method("PUT")
		@csrf
			<div id="image-inputs" class="flex-column">
				@foreach($image_urls as $i=>$image_url)
					<div class="image-input-wrapper">

						<input type="file" name="images[]" onchange="updatePreview(this, $(this).siblings('.image-preview')[0])">
						<img class="image-preview" src="{{ $image_url }}">
						
						<a onclick="toggleImageDelete(this)">
							<input type="hidden" name="delete_image[{{$i}}]" value="false">
							x
						</a>

					</div>
				@endforeach
			</div>
			<a class='button-pill' onclick="addImageInput('.image-input-wrapper', '#image-inputs')">+</a>

			<input class="form-control" type="text" name="title" placeholder="Title" value="{{ old('title', $artwork->title ) }}">
			<textarea class="form-control" name="text" placeholder="HTML text">{{ old('text', $text ) }}</textarea>
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

</div>
@endsection