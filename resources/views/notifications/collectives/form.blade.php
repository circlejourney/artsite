<form class="notification-item" method="POST">
	<div class="notification-left">
		@csrf
		<input type="hidden" name="notification_id" value="{{ $collective_notification->id }}">
		
		<div class="dummy-checkbox-spacer"></div>

        @isset($collective_notification->sender)
            <img src="{{ $collective_notification->sender->getAvatarURL() }}">
        @endisset

		<i class='fa fa-fw fa-user-group'></i>&emsp;{!! $collective_notification->sender->getNametag() !!}
		requested to join
		<a href="{{ route("collectives.show", ["collective" => $collective_notification->recipient_collective]) }}">{{ $collective_notification->recipient_collective->display_name }}</a>

		@if($collective_notification->content)
			<div class="notification-message card">
				{{ $collective_notification->content }}
			</div>
		@endif

	</div>
	
	@include("notifications.accept-reject")
</form>