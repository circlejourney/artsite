@extends('layouts.site')

@section('body')
	<h1>Notifications</h1>
	@component('notifications.notification-menu')
	@endcomponent

	<form method="POST">
		@csrf
		@method("DELETE")
		
		@if($notifications->count() > 0)
			<div>
				<button class="button-pill">Delete all</button>
			</div>
		@endif

		@foreach($notifications as $notification)
			@include("notifications.notification-item")
		@endforeach

		@if($notifications->count() > 0)
			<div>
				<button class="button-pill">Delete all</button>
			</div>
		@else
			No notifications found.
		@endif
	</form>
@endsection