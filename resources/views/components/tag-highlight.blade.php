
<div class="tag-highlight">
	@if($tag->tag_highlight_thumbnail)
		<img class="tag-highlight-image" src="{{ $tag->tag_highlight->getThumbnailURL() }}">
	@endif
	<div class="tag-highlight-text">
	{!! $tag->tag_highlight->text !!}
	</div>
</div>