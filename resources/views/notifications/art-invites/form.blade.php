<form class="notification-item" method="POST">
    <div class="notification-left @if(isset($read) && !$read) unread @endif">
		@csrf
		<input type="hidden" name="notification_id" value="{{ $notification->id }}">
		<i class='fa fa-fw fa-image-plus'></i>&emsp;{!! $notification->sender->getNametag() !!} wants to add you as a collaborator on
		<a href="{{ route("art", ["path" => $notification->artwork->path]) }}">{{ $notification->artwork->title }}</a>
	</div>

	@include("components.accept-reject")
</form>