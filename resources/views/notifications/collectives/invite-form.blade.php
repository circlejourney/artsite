<form class="notification-item" method="POST">
    <div class="notification-left">
        @csrf
        <input type="hidden" name="notification_id" value="{{ $collective_notification->id }}">
        <i class='fa fa-fw fa-user-group'></i>&emsp;{!! $collective_notification->sender->getNametag() !!}
        invited you to join
        <a href="{{ route("collectives.show", ["collective" => $collective_notification->sender_collective]) }}">{{ $collective_notification->sender_collective->display_name }}</a>
        @if($collective_notification->content)
        <div class="notification-message card">
            {{ $collective_notification->content }}
        </div>
        @endif
    </div>
    
	@include("components.accept-reject")
</form>