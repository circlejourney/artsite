@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>{{ config("app.name") }} User Management</h1>
		@foreach($users as $user)
			<div><a href="{{ route("admin.user.edit", ["user" => $user]) }}">
				{{ $user->name }}
			</div>
		@endforeach
	</div>
@endsection