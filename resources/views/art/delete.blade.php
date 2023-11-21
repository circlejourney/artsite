@extends("layouts.site")

@section('body')
<div class="p-4">
	<form method="POST">
		@csrf
		@method("DELETE")
		<div>
			Are you sure you want to delete '{{ $artwork->title }}'?
		</div>
		<button class="button-pill bg-danger">
			Yes, I'm sure
		</button>
		<a class="button-pill" href={{ route("art", ["path", $artwork->path]) }}>
			Cancel
		</a>
	</form>
</div>
@endsection