function ajax_updater(target, url, data) {
	var myAjax = new Ajax.Updater(
		{success: target},
		url,
		{parameters: data, onFailure: ajax_error}
	);
}
function ajax_error( request ) {
	alert( 'Sorry, there was a problem getting the requested data.' );
}
// in preparation of prototype 1.4
function show_loader(id) {
	Ajax.Responders.register({
		onCreate: function() {
			if (Ajax.activeRequestCount > 0)
			Element.show(id);
		},
		onComplete: function() {
			if (Ajax.activeRequestCount == 0)
			Element.hide(id);
		}
	});
}
