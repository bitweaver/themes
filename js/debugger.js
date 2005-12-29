// this will dump the contents of an object into the page
function dump( theObj ) {
	var tx="<table width='600'>";
	var props = new Array();
	for( var i in theObj ) { props.push(i); }
	props.sort();
	for( var i=0; i<props.length; i++ ) {
		var style = 'background:#fff;';
		j = i % 2;
		if( j == 0 ) { style = 'background:#ccc;'; }
		tx+= "<tr style='"+style+";'><td style='padding:5px;'>"+props[i]+"</td><td>"+theObj[props[i]]+"</td></tr>";
	}
	tx+="</table>"
	document.write( tx );
}
// from http://rails.techno-weenie.net/tip/2005/12/20/debugging_your_rjs_calls
// this debugger works only with prototype 1.4, which is currently in beta
// when you enable the debugger, prototype 1.4 is automatically enabled...
Ajax.Responders.register({
	// log the beginning of the requests
	onCreate: function( request, transport ) {
		new Insertion.Bottom( 'jsdebug',
		'<p><strong>[' + new Date().toString() + '] accessing ' + request.url + '</strong></p>')
	},
	// log the completion of the requests
	onComplete: function( request, transport ) {
		new Insertion.Bottom( 'jsdebug', 
		'<p><strong>http status: ' + transport.status + '</strong></p>' + '&lt;pre>' + transport.responseText.escapeHTML() + '&lt;/pre>')
	}
});
