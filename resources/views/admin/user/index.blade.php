@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>Manage Users</h1>
		@foreach($users as $user)
			<div>
				<a href="{{ route("admin.user.edit", ["user" => $user]) }}">
					{!! $user->getFlairHTML() !!} {{ $user->name }}
				</a>
			</div>
		@endforeach
	</div>
@endsection