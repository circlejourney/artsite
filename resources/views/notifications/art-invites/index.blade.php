@extends("layouts.site")

@section('sidebar')
	<div class="sidebar">
		@include('notifications.notification-menu', ["active" => "invites"])
	</div>
@endsection

@section('body')
	<h1>Requests and Invites</h1>
	@forelse($art_invites as $art_invite)
		@include("notifications.art-invites.form")
	@empty
		<p>No notifications found.</p>
	@endforelse
@endsection