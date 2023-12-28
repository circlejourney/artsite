<form class="notification-item" method="POST">
	<div class="notification-left @if(isset($read) && !$read) unread @endif">
		@csrf
		<input type="hidden" name="notification_id" value="{{ $notification->id }}">
		
		<div class="dummy-checkbox-spacer"></div>

        @isset($notification->sender)
            <img src="{{ $notification->sender->getAvatarURL() }}">
        @endisset

		<i class='fa fa-fw fa-user-group'></i>&emsp;{!! $notification->sender->getNametag() !!}
		requested to join
		<a href="{{ route("collectives.show", ["collective" => $notification->recipient_collective]) }}">{{ $notification->recipient_collective->display_name }}</a>

		@if($notification->content)
			<div class="notification-message card">
				{{ $notification->content }}
			</div>
		@endif

	</div>
	
	@include("notifications.accept-reject")
</form>