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

$(window).on("load", function(){
	$('[data-toggle="tooltip"]').tooltip();
});

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