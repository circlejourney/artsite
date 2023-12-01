@foreach ($tags as $tag)
	@php
		$isSelected = isset($selected) && $selected->id == $tag->id;
	@endphp
	<a class="tag {{ $isSelected ? "selected" : "" }}"
		@if(!$isSelected)
		href="{{
			route(
				'folders.index',
				["username" => $tag->user->name, "folder" => isset($folder) ? $folder->id : null, "tag" => $tag->name]
			)
		}}"
		@endif
	>
		{{ $tag->name }}
		@if($tag->tag_highlight)
			<i class="fa fa-info-circle pl-2 py-2 fake-link" data-toggle="tooltip" title="This tag meta information" @if($isSelected) onclick="$('#tag-info-{{ $tag->id }}').collapse('toggle');" @endif></i>
		@endif
	</a>
@endforeach