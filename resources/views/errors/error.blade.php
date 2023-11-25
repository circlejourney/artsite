@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>{{ $statusCode }}: {{ $name }}</h1>
		<p>
			Server returned status code {{ $statusCode }} {{ $name }}: {{ $message }}
		</p>
		@if(URL::previous() !== URL::current())
			<p>
				<a href="{{ URL::previous() }}">Go back</a>
			</p>
		@endif
	</div>
@endsection