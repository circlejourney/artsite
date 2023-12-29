@php
	$user = request()->user();
	$unread = $user->notifications()->where("notification_recipient.read", 0)->get();
@endphp

<a href="{{ route("notifications.feed") }}" class="sidebar-link {{ isset($active) && $active == "follow-feed" ? "active" : "" }}">Artists You Follow</a>

<a class="sidebar-link {{ isset($active) && $active == "favorites" ? "active" : "" }}" href="{{ route("notifications.faves") }}">Favorites
	@if(($favecount = $unread->where("type", "fave")->count()) > 0)
		<span class="sidebar-unread">{{ $favecount }}</span>
	@endif
</a>

<a class="sidebar-link {{ isset($active) && $active == "follows" ? "active" : "" }}" href="{{ route("notifications.follows") }}">Follows
	@if(($followcount = $unread->where("type", "follow")->count()) > 0)
		<span class="sidebar-unread">{{ $followcount }}</span>
	@endif
</a>

<a href="{{ route("notifications.invites") }}" class="sidebar-link {{ isset($active) && $active == "art-invites" ? "active" : "" }}">Art Invites
	@if(($artinvitecount = $unread->whereIn("type", ["art-invite", "art-inv-reject"])->count()) > 0)
		<span class="sidebar-unread">{{ $artinvitecount }}</span>
	@endif
</a>

<a href="{{ route("notifications.collectives") }}" class="sidebar-link {{ isset($active) && $active == "collectives" ? "active" : "" }}">Collectives
	@php
		$collectivecount = $unread->whereIn("type", ["co-join", "co-invite", "co-reject", "co-accept", "co-inv-reject", "co-inv-accept"])->count();
	@endphp
	@if($collectivecount > 0)
		<span class="sidebar-unread">{{ $collectivecount }}</span>
	@endif
</a>