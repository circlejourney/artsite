function delete_one() {
	event.preventDefault();
	$.ajax({
		method: "DELETE",
		url: $(event.target).closest("button").data("action"), 
		headers: { "X-CSRF-TOKEN": $("input[name='_token']").val() },
		success: function(response) {
			$("#delete-" + response.notification).remove();
		}
	});
}