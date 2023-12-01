@foreach ($tags as $tag)
	<a class="tag" href="{{
		isset($folder) ?
		route('folders.show', ["username" => $tag->user->name, "folder" => $folder->id, "tag" => $tag->name])
		: route('folders.index', ["username"=>$tag->user->name, "tag"=>$tag->name])
	}}">
		{{ $tag->name }}
		@if($tag->tag_highlight)
			&nbsp;<i class="fa fa-info-circle"></i>
		@endif
	</a>
@endforeach