@extends("layouts.site")

@push("head")
	<script>
		function addImageInput(){
			$(".image-input-wrapper").eq(0).clone().appendTo("#image-inputs").find("input").val("");
		}
	</script>
@endpush

@section('body')
<div class="page-block">
	<h1>Submit new artwork</h1>
	<form method="POST" enctype="multipart/form-data">
		@csrf
		<div>
			<div id="image-inputs" class="flex-column">
				<div class="image-input-wrapper">
					<input type="file" name="images[]" onchange="updatePreview(this, $(this).siblings('.image-preview')[0])">
					<img class="image-preview">
				</div>
			</div>
			<a class='button-pill' onclick="addImageInput()">+</a>
			<input class="form-control" type="text" name="title" placeholder="Title" required>
			<textarea class="form-control" name="text" placeholder="HTML text"></textarea>
			<input class="form-control" type="text" name="artist[]" placeholder="Collaborator">
			<input class="form-control" type="text" name="artist[]" placeholder="Collaborator">
			@include("components.folder-select", ["folderlist" => $folderlist])
			<button class='button-pill'>Submit</button>
		</div>
	</form>
</div>
@endsection