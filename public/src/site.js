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