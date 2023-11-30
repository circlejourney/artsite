@foreach ($tags as $tag)
	<a class="tag" href="{{
		!isset($user) || !$user ?
		route('tags.global.show', ["tag"=>$tag->id])
		: (
			isset($folder) ?
			route('folders.show', ["username" => $user->name, "folder" => $folder->id, "tag" => $tag->name])
			: route('folders.index', ["username"=>$user->name, "tag"=>$tag->name])
		)
	}}">
		{{ $tag->name }}</a>
@endforeach