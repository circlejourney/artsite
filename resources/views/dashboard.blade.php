@extends("layouts.site", [ "metatitle" => "Dashboard" ])

@push("title"){{ "Dashboard" }}@endpush

@section('body')
	<h1>Hello <x-nametag :user="auth()->user()" />!</h1>

	<h2>Dashboard</h2>
	<ul>
		<li>
		<a href="{{ route("profile.edit") }}">Account settings</a>
		</li>
		<li>
			<a href="/{{ auth()->user()->name }}">View your profile</a>
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
		<a href="{{ route("tags.manage") }}">Manage tags</a>
		</li>
		<li>
			<a href="{{ route("invites") }}">Invites</a>
		</li>
		<li>
		@include("components.logout-form")
		</li>
	</ul>
	<h2>Admin section</h2>
	@include("components.admin-links", ["roles" => auth()->user()->roles()])
@endsection