<form class="notification-item" method="POST">
    <div class="notification-left @if(isset($read) && !$read) unread @endif">
        @csrf
        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
        <i class='fa fa-fw fa-user-group'></i>&emsp;{!! $notification->sender->getNametag() !!}
        invited you to join
        <a href="{{ route("collectives.show", ["collective" => $notification->sender_collective]) }}">{{ $notification->sender_collective->display_name }}</a>
        @if($notification->content)
        <div class="notification-message card">
            {{ $notification->content }}
        </div>
        @endif
    </div>
    
	@include("notifications.accept-reject")
</form>