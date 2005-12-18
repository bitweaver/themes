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
	document.write(tx);
}
