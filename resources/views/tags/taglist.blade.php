@isset($tags)
	<div class="tag-list">
		@foreach ($tags as $tag)
			<a class="tag" href="{{
				!isset($user) ?
				route('tags.global.show', ["tag"=>$tag->id])
				: (
					isset($folder) ?
					route('folders.tagged', ["username" => $user->name, "folder" => $folder->id, "tag" => $tag->id])
					: route('tags.user.show', ["username"=>$user->name, "tag"=>$tag->id])
				)
			}}">
				{{ $tag->id }}</a>
		@endforeach
	</div>
@endisset