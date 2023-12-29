<form class="notification-item" method="POST">
	@csrf
	<input type="hidden" name="notification_id" value="{{ $notification->id }}">
	
	<div class="notification-left @if(isset($read) && !$read) unread @endif">
		@include("notifications.form-checkbox")

        @isset($notification->sender)
            <img src="{{ $notification->sender->getAvatarURL() }}">
        @endisset
		
		{!! $notification->getDisplayHTML() !!}

		@if($notification->content)
			<div class="notification-message card">
				{{ $notification->content }}
			</div>
		@endif

	</div>
	
	@include("notifications.accept-reject")
</form>