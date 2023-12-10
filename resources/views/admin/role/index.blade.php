@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>Role Management</h1>
		@foreach($roles as $role)
			<div>
				<a href="{{ route("admin.role.edit", ["role" => $role]) }}">
					{!! $role->getDefaultFlairHTML() !!} {{ Str::ucfirst($role->name) }}
				</a>
			</div>
		@endforeach
	</div>
@endsection