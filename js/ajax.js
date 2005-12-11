function ajax_updater(target, url, data) {
	var myAjax = new Ajax.Updater(
		target,
		url,
		{parameters: data}
	);
}
