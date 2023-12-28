@extends('layouts.site')

@section('sidebar')
	<div class="sidebar">
		@include('notifications.notification-menu', ["active" => $active])
	</div>
@endsection

@section('body')
	<h1>Notifications</h1>

	@if(!isset($mass_delete) || $mass_delete)
		<form method="POST">
			@csrf
			@method("DELETE")
			
			@if($notifications->count() > 0)
				<div>
					<button class="button-pill">Delete all</button>
				</div>
			@endif

			@foreach($notifications as $notification)
				@include("notifications.notification-item", ["read" => $notification->read])
			@endforeach

			@if($notifications->count() > 0)
				<div>
					<button class="button-pill">Delete all</button>
				</div>
			@else
			<p>
				No notifications found.
			</p>
			@endif
		</form>
		
	@else
		@forelse($notifications as $notification)
			@if($notification->type == "art-invite")
				@include("notifications.art-invite-form", ["read" => $notification->read])
			@else
				@include("notifications.notification-item", ["read" => $notification->read])
			@endif
		@empty
			<p>
				No notifications found.
			</p>
		@endforelse
	@endif

	<form class="read" action="{{ route("notifications.put-read") }}">
		@csrf
		@foreach($notifications as $notification)
			<input type="hidden" name="read[]" value="{{ $notification->id }}">
		@endforeach
	</form>

@endsection