// based on http://rajshekhar.net/blog/archives/85-Rasmus-30-second-AJAX-Tutorial.html
function createXMLHttpRequest() {
	var ua;
	if(window.XMLHttpRequest) {
		try {
			ua = new XMLHttpRequest();
		} catch(e) {
			ua = false;
		}
	} else if(window.ActiveXObject) {
		try {
			ua = new ActiveXObject("Microsoft.XMLHTTP");
		} catch(e) {
			ua = false;
		}
	}
	return ua;
}
var req = createXMLHttpRequest();
function sendRequest(id,params) {
	if(req) {
		document.getElementById(id).innerHTML = '<img src="'+bitIconDir+'busy.gif" alt="Loading" title="Loading" style="vertical-align:middle; position:absolute;" /> '+document.getElementById(id).innerHTML;
	} else {
		document.getElementById(id).innerHTML = 'Your browser cannot display the content. Please upgrade your browser to a more recent version.';
	}
	if(params) {
		params = '&'+params;
	} else { 
		var params = '';
	}
	req.open('get', bitRootUrl+'themes/ajax.php?ajaxid='+id+params);
	req.onreadystatechange = handleResponse;
	req.send(null);
}
function handleResponse() {
	if(req.readyState == 4){
		var response = req.responseText;
		var update = new Array();

		if(response.indexOf('||' != -1)) {
			update = response.split('||');
			document.getElementById(update[0]).innerHTML = update[1];
		}
	}
}
