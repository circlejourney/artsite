@extends("layouts.site")

@section('body')
	<h1>Delete {{ $collective->display_name }}</h1>	
	<form method="post">
		@csrf
		@method("DELETE")
		<div>
			Are you sure you want to leave '{{ $collective->display_name }}'?
		</div>
		<button class="button-pill bg-danger">
			Yes, I'm sure
		</button>
		<a class="button-pill" href={{ route("collectives.show", ["collective", $collective]) }}>
			Cancel
		</a>
	</form>
@endsection