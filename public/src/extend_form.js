function addImageInput(templateselector, parentselector, limit=false){
	if(limit && $(templateselector).length > limit) return false;
	const next = $(templateselector).length;
	const clone = $(templateselector).eq(0).clone().appendTo(parentselector);
	$(clone).find("input[type='file']").val("");
	$(clone).find("img").removeAttr("src");
	$(clone).find(".delete_image").val("false");
	$(clone).find(".image_order").val("false");
	return clone;
}

function addTextInput(templateselector, parentselector, limit=false){
	if(limit) {
		if($(templateselector).length > limit) return false;
	}
	const input = $(templateselector).eq(0).clone().appendTo(parentselector).val("");
}