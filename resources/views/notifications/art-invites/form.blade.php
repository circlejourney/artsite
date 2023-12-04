<form method="POST">
	@csrf
	<input type="hidden" name="art_invite_id" value="{{ $art_invite->id }}">
	<i class='fa fa-fw fa-image-plus'></i>&emsp;{!! $art_invite->sender->getNametag() !!} wants to add you as a collaborator on
	<a href="{{ route("art", ["path" => $art_invite->artwork->path]) }}">{{ $art_invite->artwork->title }}</a>
	<button name="action" value="accept"><i class="fa fa-check"></i></button>
	<button name="action" value="reject"><i class="fa fa-times"></i></button>
</form>