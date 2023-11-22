@extends("layouts.site")

@section('body')
	<div class="page-block">
		<h1>Manage <a href="{{ route("user", $user->name) }}">{{ $user->name }}</a></h1>
		<form method="POST">
			@csrf
			@method("PUT")
			@foreach($roles as $role)
				<div>
					<input id="role-{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->id }}"
						@checked( old('active', $user_roles->contains($role->id)) )>
					<label for="role-{{ $role->id }}">
						{{ $role->name }}
					</label>
				</div>
			@endforeach
			<button>Update</button>
		</form>
	</div>
@endsection