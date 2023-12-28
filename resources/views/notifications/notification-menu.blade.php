@php
	$user = request()->user();
	$unread = $user->notifications()->where("notification_recipient.read", 0);
@endphp

<a href="{{ route("notifications.feed") }}" class="sidebar-link {{ isset($active) && $active == "follow-feed" ? "active" : "" }}">Artists You Follow</a>

<a class="sidebar-link {{ isset($active) && $active == "favorites" ? "active" : "" }}" href="{{ route("notifications.faves") }}">Favorites
	@if(($favecount = $user->notifications()->where("notification_recipient.read", 0)->where("type", "fave")->get()->count()) > 0)
		<span class="sidebar-unread">{{ $favecount }}</span>
	@endif
</a>

<a class="sidebar-link {{ isset($active) && $active == "follows" ? "active" : "" }}" href="{{ route("notifications.follows") }}">Follows
	@if(($followcount = $user->notifications()->where("notification_recipient.read", 0)->where("type", "follow")->get()->count()) > 0)
		<span class="sidebar-unread">{{ $followcount }}</span>
	@endif
</a>

<a href="{{ route("notifications.invites") }}" class="sidebar-link {{ isset($active) && $active == "art-invites" ? "active" : "" }}">Art Invites
	@if(($artinvitecount = $user->notifications()->where("notification_recipient.read", 0)->where("type", "art-invite")->count()) > 0)
		<span class="sidebar-unread">{{ $artinvitecount }}</span>
	@endif
</a>

<a href="{{ route("notifications.collectives") }}" class="sidebar-link {{ isset($active) && $active == "collectives" ? "active" : "" }}">Collectives
	@php
		$collectivecount = $unread->whereNotNull("sender_collective_id")->get()->count()
			+ $unread->whereNotNull("recipient_collective_id")->get()->count()
	@endphp
	@if(($user->notifications()->where("notification_recipient.read", 0)->whereNotNull("sender_collective_id")->get()->count()) > 0)
		<span class="sidebar-unread">{{ $collectivecount }}</span>
	@endif
</a>