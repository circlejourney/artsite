<ul class="nav nav-pill">
	@php
		$user = request()->user();
	@endphp
	<a class="nav-link" href="{{ route("notifications") }}">Notifications
		<span class="badge badge-secondary">{{ $user->notifications()->whereNull("sender_collective_id")->get()->count() }}</span>
	</a>
	<a href="{{ route("notifications.feed") }}" class="nav-link">Artists You Follow</a>
	<a href="{{ route("notifications.invites") }}" class="nav-link">Requests and Invites
		<span class="badge badge-secondary">{{ $user->art_invites->count() }}</span>
	</a>
	<a href="{{ route("notifications.collectives") }}" class="nav-link">Collectives
		<span class="badge badge-secondary">{{ $user->collective_notifications()->count() + $user->notifications()->whereNotNull("sender_collective_id")->get()->count() }}</span>
	</a>
</ul>