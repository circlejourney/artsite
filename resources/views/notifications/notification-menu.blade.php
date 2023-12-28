@php
	$user = request()->user();
@endphp

<a href="{{ route("notifications.feed") }}" class="sidebar-link {{ isset($active) && $active == "follow-feed" ? "active" : "" }}">Artists You Follow</a>

<a class="sidebar-link {{ isset($active) && $active == "faves" ? "active" : "" }}" href="{{ route("notifications.faves") }}">Favorites
	@if(($favecount = $user->notifications()->where("type", "fave")->get()->count()) > 0)
		<span class="sidebar-unread">{{ $favecount }}</span>
	@endif
</a>

<a class="sidebar-link {{ isset($active) && $active == "follows" ? "active" : "" }}" href="{{ route("notifications.follows") }}">Follows
	@if(($followcount = $user->notifications()->where("type", "follow")->get()->count()) > 0)
		<span class="sidebar-unread">{{ $followcount }}</span>
	@endif
</a>

<a href="{{ route("notifications.invites") }}" class="sidebar-link {{ isset($active) && $active == "invites" ? "active" : "" }}">Requests and Invites
	@if(($artinvitecount = $user->notifications()->where("type", "art-invite")->count()) > 0)
		<span class="sidebar-unread">{{ $artinvitecount }}</span>
	@endif
</a>

<a href="{{ route("notifications.collectives") }}" class="sidebar-link {{ isset($active) && $active == "collectives" ? "active" : "" }}">Collectives
	@if(($collectivecount = $user->collective_notifications()->count() + $user->notifications()->whereNotNull("sender_collective_id")->get()->count()) > 0)
		<span class="sidebar-unread">{{ $collectivecount }}</span>
	@endif
</a>