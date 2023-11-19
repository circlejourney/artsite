function sanitise_html(string) {
	const blocked = ["script", "style", "link", "meta", "title", "head", "body"].join("|");
	const re = new RegExp("<\/?(" + blocked + ").*?>", "g");
	return string.replace(re, "");
}