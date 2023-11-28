@foreach ($tags as $tag)
	<a class="tag" href="{{
		!isset($user) || !$user ?
		route('tags.global.show', ["tag"=>$tag->id])
		: (
			isset($folder) ?
			route('folders.show', ["username" => $user->name, "folder" => $folder->id, "tag" => $tag->id])
			: route('folders.index', ["username"=>$user->name, "tag"=>$tag->id])
		)
	}}">
		{{ $tag->id }}</a>
@endforeach