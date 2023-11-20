@extends("layouts.site")

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
	<h1>Submit new artwork</h1>
	<form method="POST" enctype="multipart/form-data" class="row">
		@method("PUT")
		@csrf
		<div class="col-auto">
			<div id="image-inputs" class="flex-column">
				<input type="file" name="images[]" onchange="updatePreview(this, $(this).siblings('.image-preview')[0])">
				<img class="image-preview">
			</div>
			<a class='button-pill' onclick="addImageInput()">+</a>
		</div>
		<div class="col">
			<input class="form-control" type="text" name="title" placeholder="Title">
			<textarea class="form-control" name="text" placeholder="HTML text"></textarea>
			<input class="form-control" type="text" name="artist" placeholder="Collaborator 1">
			<input class="form-control" type="text" name="artist" placeholder="Collaborator 2">
			<button class='button-pill'>Submit</button>
		</div>
	</form>
</div>
@endsection