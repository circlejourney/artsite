
<label for="privacy_level">Privacy level</label>
<select class="form-control" name="privacy_level" id="privacy_level">
	@foreach($privacylevels as $privacylevel)
		<option value="{{ $privacylevel->id }}" @selected( old('active', $attributes->get("selected")==$privacylevel->id) )>{{ $privacylevel->name }}</option>
	@endforeach
</select>