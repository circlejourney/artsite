@extends("layouts.site")

@push('head')
	<script>
		function updateFlairPreview() {
			$(".flair-preview").removeClass().addClass("flair-preview fa fa-"+$("#default_flair").val());
		}
		$(window).on("load", updateFlairPreview);
	</script>
@endpush

@section('body')
	<div class="page-block">
		<h1>Role Management</h1>
		<form method="POST">
			@csrf
			@method("PUT")
			<input type="text" id="default_flair" name="default_flair" value="{{ old("default_flair", $role->default_flair) }}" oninput="updateFlairPreview(this)">
			Preview: <i class="flair-preview fa fa-{{$role->default_flair}}"></i>
			<button>Update</button>
		</form>
	</div>
@endsection