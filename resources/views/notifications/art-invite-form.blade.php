<form class="notification-item" method="POST">
	@csrf
	<input type="hidden" name="notification_id" value="{{ $notification->id }}">
	
    <div class="notification-left @if(isset($read) && !$read) unread @endif">
		@include("notifications.form-checkbox")
		
        @isset($notification->sender)
            <img src="{{ $notification->sender->getAvatarURL() }}">
        @endisset

		<i class='fa fa-fw fa-image-plus'></i>&emsp;{!! $notification->sender->getNametag() !!} wants to add you as a collaborator on
		<a href="{{ route("art", ["path" => $notification->artwork->path]) }}">{{ $notification->artwork->title }}</a>
	</div>

	@include("notifications.accept-reject")
</form>