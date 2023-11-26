@extends("layouts.site")

@push('head')
	<script>
		function updateFlairPreview() {
			if($("#custom_flair").length) $(".flair-preview").removeClass().addClass("flair-preview fa fa-"+$("#custom_flair").val());
		}
		$(window).on("load", updateFlairPreview);
	</script>
@endpush

@section('body')
	<div class="page-block">
		<h1>Manage <a href="{{ route("user", $user->name) }}">{!! $user->getFlairHTML() !!} {{ $user->name }}</a></h1>
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

			<div>
				<label for="custom_flair">Custom icon flair: <b>fa-</b></label>
				<input id="custom_flair" name="custom_flair" type="text" class="mt-1" value="{{ old('custom_flair', $user->custom_flair ?? $user->getTopRole()->default_flair) }}" required autofocus oninput="updateFlairPreview()">
				<a href="https://fontawesome.com/search?o=r&m=free">Click here</a> for a list of available Font Awesome icon names.
				<x-input-error class="mt-2" :messages="$errors->get('custom_flair')" />
			</div>
			
			<div>
				Preview: <a href="#"><i class="flair-preview fa fa-{{ old('custom_flair', $user->custom_flair ?? $user->getTopRole()->default_flair) }}"></i> <span class="username-preview">{{ $user->name }}</span></a>
			</div>

			<button>Update</button>
		</form>
	</div>
@endsection