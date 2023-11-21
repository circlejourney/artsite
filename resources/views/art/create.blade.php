@extends("layouts.site")

@push("head")
	<script>
		function addImageInput(){
			$(".image-input-wrapper").eq(0).clone().appendTo("#image-inputs").find("input").val("");
		}
	</script>
@endpush

@section('body')
<div class="p-4">
	<h1>Submit new artwork</h1>
	<form method="POST" enctype="multipart/form-data" class="row">
		@csrf
		<div class="col-auto">
			<div id="image-inputs" class="flex-column">
				<div class="image-input-wrapper">
					<input type="file" name="images[]" onchange="updatePreview(this, $(this).siblings('.image-preview')[0])">
					<img class="image-preview">
				</div>
			</div>
			<a class='button-pill' onclick="addImageInput()">+</a>
		</div>
		<div class="col">
			<input class="form-control" type="text" name="title" placeholder="Title">
			<textarea class="form-control" name="text" placeholder="HTML text"></textarea>
			<input class="form-control" type="text" name="artist[]" placeholder="Collaborator">
			<input class="form-control" type="text" name="artist[]" placeholder="Collaborator">
			<button class='button-pill'>Submit</button>
		</div>
	</form>
</div>
@endsection