@extends('layouts.site')

@section('body')
	<h1>Notifications</h1>
	@component('notifications.notification-menu')
	@endcomponent

	<form method="POST">
		@csrf
		@method("DELETE")
		
		@if(($notifications = $user->notifications()->orderBy("created_at", "desc")->get())->count() > 0)
			<div>
				<button class="button-pill">Delete all</button>
			</div>
		@endif

		@foreach($notifications as $notification)
			<div class="p-2" id="delete-{{ $notification->id }}">
				<input type="hidden" name="notifications[]" value="{{ $notification->id }}">
				<button class="invisible-button" data-action="{{ route("notifications.delete-one", ["notification" => $notification->id])}}" onclick="delete_one()">
					<i class="fa fa-times"></i>
				</button>
				<span class="small text-muted mr-2">
					{{ $notification->created_at->diffForHumans() }}
				</span>
				{!! $notification->getDisplayHTML() !!}
			</div>
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