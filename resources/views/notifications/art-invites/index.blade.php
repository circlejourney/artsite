@extends("layouts.site")

@section('body')
	<h1>Requests and Invites</h1>
	@include('notifications.notification-menu')
	@forelse($art_invites as $art_invite)
		@include("notifications.art-invites.form")
	@empty
		No notifications found.
	@endforelse
@endsection