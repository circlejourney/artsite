@extends('layouts.site')

@section('body')
	<h1>Notifications</h1>
	<form method="POST">
		@csrf
		@method("DELETE")
		
		@if(($notifications = $user->notifications)->count() > 0)
			<button class="button-pill">Delete all</button>
		@endif

		@foreach($notifications as $notification)
			<div class="p-2" id="delete-{{ $notification->id }}">
				<input type="hidden" name="notifications[]" value="{{ $notification->id }}">
				<button class="invisible-button" data-action="/notification-ajax/{{ $notification->id }}" onclick="delete_one()">
					<i class="fa fa-times"></i>
				</button>
				{!! $notification->getDisplayHTML() !!}
			</div>
		@endforeach

		@if($notifications->count() > 0)
			<button class="button-pill">Delete all</button>
		@else
			No notifications found.
		@endif
	</form>
@endsection