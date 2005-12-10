function ajax_updater(target, url, data) {
	var myAjax = new Ajax.Updater(
		target,
        url,
        {method: 'post', parameters: data, onComplete: ajax_updater}
    );
}
