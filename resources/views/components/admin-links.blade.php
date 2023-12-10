<ul>
	@if($roles->get()->pluck("manage_users")->contains(true))
		<li><a href="{{ route("admin.user.index") }}">Manage users</a></li>
	@endif
	@if($roles->get()->pluck("manage_roles")->contains(true))
		<li><a href="{{ route("admin.role.index") }}">Manage roles</a></li>
	@endif
	@if($roles->get()->pluck("manage_artworks")->contains(true))
	<li><a href="{{ route("admin.art.index") }}">Manage artworks</a></li>
@endif
</ul>