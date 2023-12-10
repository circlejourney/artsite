@foreach ($tags as $tag)
	@php
		$isSelected = isset($selected) && $selected->id == $tag->id;
	@endphp
	<a class="tag {{ $isSelected ? "selected" : "" }}"
		@isset($folder)
			href="{{
				route(
					'folders.show',
					["username" => $tag->user->name, "folder" => $folder->id, "tag" => !$isSelected ? $tag->name : null]
				)
			}}"
		@else 
			href="{{
				route(
					'folders.index',
					["username" => $tag->user->name, "tag" => !$isSelected ? $tag->name : null]
				)
			}}"
		@endif
		>
		{{ $tag->name }}
		@if($tag->tag_highlight)
			<i class="fa fa-info-circle pl-2 py-2 fake-link" data-toggle="tooltip" title="This tag has meta information" @if($isSelected) onclick="event.preventDefault(); $('#tag-info-{{ $tag->id }}').collapse('toggle');" @endif></i>
		@endif
	</a>
@endforeach