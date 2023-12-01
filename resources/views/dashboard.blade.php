@extends("layouts.site", [ "metatitle" => "Dashboard" ])

@push("title"){{ "Dashboard" }}@endpush

@section('body')
	<h1>Welcome back to {{ config("app.name") }}, {{ auth()->user()->name }}!</h1>
	<ul>
		<li>
			<a href="/{{ auth()->user()->name }}">View your profile</a>
		</li>
		<li>
		<a href="{{ route("profile.edit") }}">Account settings</a>
		</li>
		<li>
			<a href="{{ route("profile.html.edit") }}">Customise profile</a>
		</li>
		<li>
		<a href="{{ route("art.manage") }}">Manage art</a>
		</li>
		<li>
		<a href="{{ route("folders.manage") }}">Manage art folders</a>
		</li>
		<li>
			<a href="{{ route("invites") }}">Invites</a>
		</li>
		<li>
		@include("components.logout-form")
		</li>
	</ul>
	<br>
	<h2>Admin section</h2>
	@include("components.admin-links", ["roles" => auth()->user()->roles()])
@endsection