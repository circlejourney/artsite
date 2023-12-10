<script>
	function fave() {
		event.preventDefault();
		$.ajax({
			method: "POST",
			url: event.target.action, 
			headers: { "X-CSRF-TOKEN": $(event.target).find("input[name='_token']").val() },
			success: function(response) {
				if(response.action === 1) {
					$("#fave-" + response.artwork).find(".fave-icon").removeClass("far").addClass("fas");
				} else {
					$("#fave-" + response.artwork).find(".fave-icon").removeClass("fas").addClass("far");
				}
				const newFaves = parseInt($("#fave-" + response.artwork).find(".fave-text").text());
				$("#fave-" + response.artwork).find(".fave-text").text(newFaves + response.action);
			}
		});
	}
</script>
<form method="POST" action="{{ route("fave", ["path" => $artwork->path]) }}" onsubmit="fave()" id="fave-{{ $artwork->id }}">
	@csrf
	<button class="fave-button">
		<i class="fave-icon fa{{ auth()->user()->faves->contains($artwork) ? "s" : "r" }} fa-heart"></i>
		<span class="fave-text">{{ $artwork->faved_by->count() }}</span>
	</button>
</form>