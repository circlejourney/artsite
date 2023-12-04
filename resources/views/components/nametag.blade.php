<a href="{{ route("user", ["username" => $user->name]) }}">
	<i class="fa fa-{{ $user->getFlair() }}"></i> {{ $user->name }}</a>