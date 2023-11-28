@extends('layouts.site')

@push('head')
	<script src="/src/notification-delete.js"></script>
@endpush

@section('body')
	<h1>Notifications</h1>
	<form method="POST">
		@csrf
		@method("DELETE")
		@foreach($notifications = $user->notifications as $notification)
			<div id="delete-{{ $notification->id }}">
				<input type="hidden" name="notifications[]" value="{{ $notification->id }}">
				<button nam="delete" value="{{ $notification->id }}" data-action="/notification-ajax/{{ $notification->id }}" onclick="delete_one()">
					<i class="fa fa-times"></i>
				</button>
				{!! $notification->getDisplayHTML() !!}
			</div>
		@endforeach
		@if($notifications->count() > 0)
			<button>Delete all</button>
		@else
			No notifications found.
		@endif
	</form>
@endsection