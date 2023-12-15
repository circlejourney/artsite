<form method="POST">
	@csrf
	<input type="hidden" name="notification_id" value="{{ $collective_notification->id }}">
	<i class='fa fa-fw fa-image-plus'></i>&emsp;{!! $collective_notification->sender->getNametag() !!}
    requested to join
	<a href="{{ route("art", ["path" => $collective_notification->recipient_collective]) }}">{{ $collective_notification->recipient_collective->display_name }}</a>
	<button name="action" value="accept"><i class="fa fa-check"></i></button>
	<button name="action" value="reject"><i class="fa fa-times"></i></button>
</form>