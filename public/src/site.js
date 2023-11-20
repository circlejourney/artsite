function sanitise_html(string) {
	const blocked = ["script", "style", "link", "meta", "title", "head", "body"].join("|");
	const re = new RegExp("<\/?(" + blocked + ").*?>", "g");
	return string.replace(re, "");
}


function updatePreview(input, target) {
	if(!input.files) return false;
	const file = input.files[0];
	const reader = new FileReader();
	reader.readAsDataURL(file);
	reader.onload = function(){
		$(target).attr("src", this.result);
	}
}