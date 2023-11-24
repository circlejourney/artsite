
<label for="privacy_level_id">Privacy level</label>
<select class="form-control" name="privacy_level_id" id="privacy_level_id">
	@foreach($privacylevels as $privacylevel)
		<option value="{{ $privacylevel->id }}" @selected( old('active', $attributes->get("selected")==$privacylevel->id) )>{{ $privacylevel->name }}</option>
	@endforeach
</select>