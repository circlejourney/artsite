@extends("layouts.site")

@push("metatitle"){{ "Dashboard" }}@endpush
@push("title"){{ "Dashboard" }}@endpush

@section('body')
<div class="p-4">
	<h1>Welcome back to {{ config("app.name") }}, {{ auth()->user()->name }}!</h1>
	<ul>
		<li>
			<a href="/{{ auth()->user()->name }}">View your profile</a>
		</li>
		<li>
			<a href="{{ route("profile.html.edit") }}">Customise your profile</a>
		</li>
		<li>
		<a href="{{ route("folders.manage") }}">Manage art folders</a>
		</li>
		<li>
		<a href="{{ route("profile.edit") }}">Update your settings</a>
		</li>
		<li>
		@include("components.logout-form")
		</li>
	</ul>
	<br>
	<h2>Admin section</h2>
	@include("components.admin-links", ["roles" => auth()->user()->roles()])

</div>
@endsection