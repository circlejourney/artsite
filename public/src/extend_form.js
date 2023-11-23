function addImageInput(templateselector, parentselector, limit=false){
	if(limit) {
		if($(templateselector).length > limit) return false;
	}
	const clone = $(templateselector).eq(0).clone().appendTo(parentselector);
	$(clone).find("input").val("");
	$(clone).find("img").removeAttr("src")
}

function addTextInput(templateselector, parentselector, limit=false){
	if(limit) {
		if($(templateselector).length > limit) return false;
	}
	const input = $(templateselector).eq(0).clone().appendTo(parentselector).val("");
}