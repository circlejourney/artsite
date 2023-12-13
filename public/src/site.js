$(window).on("load", function(){
	$('[data-toggle="tooltip"]').tooltip();
	if($(".notifications").length > 0) {
		fetchNotificationCount();
	}

	$(".format-date").each(function(i, element){
		formatDateTime(element);
	})
});

function sanitise_html(string) {
	const blocked = ["script", "style", "link", "meta", "title", "head", "body"].join("|");
	const re = new RegExp("<\/?(" + blocked + ").*?>", "g");
	return string.replace(re, "");
}


function updatePreview(input, target, bg=false) {
	if(!input.files) return false;
	const file = input.files[0];
	const reader = new FileReader();
	reader.readAsDataURL(file);
	reader.onload = function(){
		if(!bg) $(target).attr("src", this.result);
		else $(target).css("background-image", "url("+this.result+")");
	}
}

function startAceEditor(editor, inputSync, previewTarget=null) { // All values are jQuery selectors or DOM elements
	// Init editor if found
	editor = ace.edit(editor.substring(1));
	editor.setTheme("ace/theme/katzenmilch");
	editor.setShowPrintMargin(false);
	editor.session.setMode("ace/mode/html");
	editor.session.setUseWrapMode(true);
	editor.session.on("change", function() {
		const wait = startIdle();
		editor.session.on("change", function cancel(){
			clearTimeout(wait);
			editor.session.off("change", cancel);
		})
	});

	editor.setValue($(inputSync).val());
	if(previewTarget)	$(previewTarget).html( sanitise_html($(inputSync).val()) );

	function startIdle() {
		const timeout = setTimeout(function() {
			const html = editor.getValue();
			if(previewTarget)	$(previewTarget).html(sanitise_html(html));
			$(inputSync).val(html);
		}, 500);
		return timeout;
	}

	return editor;

}

function delete_one() {
	event.preventDefault();
	$.ajax({
		method: "DELETE",
		url: $(event.target).closest("button").data("action"), 
		headers: { "X-CSRF-TOKEN": $("input[name='_token']").val() },
		success: function(response) {
			console.log(response);
			$("#delete-" + response.notification).remove();
		}
	});
}

function follow() {
	event.preventDefault();
	$.ajax({
		method: "POST",
		url: $(event.target).closest("form").prop("action"), 
		headers: { "X-CSRF-TOKEN": $("input[name='_token']").val() },
		success: function(response) {
			$(".follow-button").text( response.action == -1 ? "Follow" : "Unfollow" );
		}
	});
}

function fetchNotificationCount() {
	const form = $(".notifications")[0];
	$.get($(form).prop("action"), {
		"headers": { "X-CSRF-TOKEN": $(form).find("input[name='_token']").val() },
	}).done(function(response){
		const badge = $(form).find(".notification-badge").text(response)[0];
		if(parseInt(response) > 0) $(badge).removeClass("d-none");
		else $(badge).addClass("d-none");
	});
}

function formatDateTime(target) {
	const datetime = new Date(parseInt($(target).data("timestamp") * 1000));
	$(target).text(datetime.toLocaleString());
}