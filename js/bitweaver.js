// $Header: /cvsroot/bitweaver/_bit_themes/js/Attic/bitweaver.js,v 1.4 2005/12/18 22:32:27 squareing Exp $
var expires = new Date();
var offset = -(expires.getTimezoneOffset() * 60);
expires.setFullYear(expires.getFullYear() + 1);
setCookie("tz_offset", offset);

/*--- moved here from prototype - start ---*/
function $() {
	var elements = new Array();
	for (var i = 0; i < arguments.length; i++) {
		var element = arguments[i];
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (arguments.length == 1) 
			return element;
		elements.push(element);
	}
	return elements;
}
/*----------- prototype - end -------------*/

function toggle_dynamic_var($name) {
	name1 = 'dyn_'+$name+'_display';
	name2 = 'dyn_'+$name+'_edit';
	if($(name1).style.display == "none") {
		$(name2).style.display = "none";
		$(name1).style.display = "inline";
	} else {
		$(name1).style.display = "none";
		$(name2).style.display = "inline";
	}
}

function genPass(w1, w2, w3) {
	vo = "aeiouAEU";
	co = "bcdfgjklmnprstvwxzBCDFGHJKMNPQRSTVWXYZ0123456789_$%#";
	s = Math.round(Math.random());
	l = 8;
	p = '';

	for (i = 0; i < l; i++) {
		if (s) {
			letter = vo.charAt(Math.round(Math.random() * (vo.length - 1)));
			s = 0;
		} else {
			letter = co.charAt(Math.round(Math.random() * (co.length - 1)));
			s = 1;
		}

		p = p + letter;
	}

	$(w1).value = p;
	$(w2).value = p;
	$(w3).value = p;
}

function setSomeElement(fooel, foo1) {
	$(fooel).value = $(fooel).value + foo1;
}

// used by insertAt()
function setSelectionRange(textarea, selectionStart, selectionEnd) {
	if (textarea.setSelectionRange) {
		textarea.focus();
		textarea.setSelectionRange(selectionStart, selectionEnd);
	} else if (textarea.createTextRange) {
		var range = textarea.createTextRange();
		textarea.collapse(true);
		textarea.moveEnd('character', selectionEnd);
		textarea.moveStart('character', selectionStart);
		textarea.select();
	}
}

// used by insertAt()
function setCaretToPos (textarea, pos) {
	setSelectionRange(textarea, pos, pos);
}

// inserts replaceString in elementId - used for quicktags
function insertAt(elementId, replaceString) {
	//inserts given text at selection or cursor position
	textarea = $(elementId);
	var toBeReplaced = /text|page|textarea_id/;//substrings in replaceString to be replaced by the selection if a selection was done
	if (textarea.setSelectionRange) {
		//Mozilla UserAgent Gecko-1.4
		var selectionStart = textarea.selectionStart;
		var selectionEnd = textarea.selectionEnd;
		if (selectionStart != selectionEnd) { // has there been a selection
			var newString = replaceString.replace(toBeReplaced, textarea.value.substring(selectionStart, selectionEnd));
			textarea.value = textarea.value.substring(0, selectionStart)
				+ newString
				+ textarea.value.substring(selectionEnd);
			setSelectionRange(textarea, selectionStart, selectionStart + newString.length);
		} else {// set caret
			textarea.value = textarea.value.substring(0, selectionStart)
				+ replaceString
				+ textarea.value.substring(selectionEnd);
			setCaretToPos(textarea, selectionStart + replaceString.length);
		}
	} else if (document.selection) {
		//UserAgent IE-6.0
		textarea.focus();
		var range = document.selection.createRange();
		if (range.parentElement() == textarea) {
			var isCollapsed = range.text == '';
			if (! isCollapsed)	{
				range.text = replaceString.replace(toBeReplaced, range.text);
				range.moveStart('character', -range.text.length);
				range.select();
			} else {
				range.text = replaceString;
			}
		}
	}
	else { //UserAgent Gecko-1.0.1 (NN7.0)
		setSomeElement(elementId, replaceString)
		//alert("don't know yet how to handle insert" + document);
	}
}

/*--- show - hide - flip - toggle ---*/
function show(foo,f) {
	$(foo).style.display = "block";
	if (f) { setCookie(foo, "o"); }
}

function hide(foo,f) {
	$(foo).style.display = "none";
	if (f) { setCookie(foo, "c"); }
}

function flip(foo) {
	if ($(foo).style.display == "none") {
		show(foo);
	} else {
		if ($(foo).style.display == "block") {
			hide(foo);
		} else {
			show(foo);
		}
	}
}

function toggle(foo) {
	if ($(foo).style.display == "none") {
		show(foo);
		setCookie(foo, "o");
	} else {
		if ($(foo).style.display == "block") {
			hide(foo,1);
		} else {
			show(foo,1);
		}
	}
}

function settogglestate(foo) {
	if (getCookie(foo) == "o") {
		show(foo);
	} else {
		hide(foo);
	}
}

function setfoldericonstate(foo) {
	pic = new Image();
	cookie_value = getCookie(foo);
	if (cookie_value == "o") {
		pic.src = bitIconDir + "expanded.gif";
	} else if (cookie_value == "c") {
		pic.src = bitIconDir + "collapsed.gif";
	} else {
		return;
	}
	$(foo+"img").src = pic.src;
}

function icntoggle(foo) {
	if ($(foo).style.display == "none") {
		show(foo);
		setCookie(foo, "o");
	} else {
		hide(foo);
		setCookie(foo, "c");
	}
	setfoldericonstate(foo);
}

function flipWithSign(foo) {
	if ($(foo).style.display == "none") {
		show(foo);
		collapseSign("flipper" + foo);
		setCookie(foo, "o");
	} else {
		hide(foo);
		expandSign("flipper" + foo);
		setCookie(foo, "c");
	}
}

function setFlipWithSign(foo) {
	if (getCookie(foo) == "o") {
		collapseSign("flipper" + foo);
		show(foo);
	} else {
		expandSign("flipper" + foo);
		hide(foo);
	}
}

function expandSign(foo) {
	$(foo).firstChild.nodeValue = "[+]";
}

function collapseSign(foo) {
	$(foo).firstChild.nodeValue = "[-]";
}

function toggleBlockDisplay(item) {
	if (document.layers) {
		current = (document.layers[item].display == 'none') ? 'block' : 'none';
		document.layers[item].display = current;
	} else if (document.all) {
		current = (document.all[item].style.display == 'none') ? 'block' : 'none';
		document.all[item].style.display = current;
	} else if (document.getElementById) {
		vista = ($(item).style.display == 'none') ? 'block' : 'none';
		$(item).style.display = vista;
	}
}

function setBlockDisplay(item,vizFlag) {
	current = (vizFlag) ? 'block' : 'none';
	if (document.layers) {
		document.layers[item].display = current;
	} else if (document.all) {
		document.all[item].style.display = current;
	} else if (document.getElementById) {
		$(item).style.display = current;
	}
}
/*--- show - hide - flip - toggle ---*/

// name - name of the cookie
// value - value of the cookie
// [expires] - expiration date of the cookie (defaults to end of current session)
// [path] - path for which the cookie is valid (defaults to path of calling document)
// [domain] - domain for which the cookie is valid (defaults to domain of calling document)
// [secure] - Boolean value indicating if the cookie transmission requires a secure transmission
// * an argument defaults when it is assigned null as a placeholder
// * a null placeholder is not required for trailing omitted arguments
function setCookie(name, value, expire, path, domain, secure) {
	var cookie_path = (path) ? path : bitCookiePath;
	var cookie_domain = escape((domain) ? domain : bitCookieDomain);
	var cookie_expire = (expire) ? expire.toGMTString() : expires.toGMTString();
	var curCookie = name + "=" + escape(value)
			+ "; path=" +  cookie_path
			+ "; domain=" + cookie_domain
			+ "; expires=" + cookie_expire
			+ ((secure) ? "; secure" : "");
//alert(curCookie);
	document.cookie = curCookie;
}

// name - name of the desired cookie
// * return string containing value of specified cookie or null if cookie does not exist
function getCookie(name) {
	var dc = document.cookie;
	var prefix = name + "=";
	var begin = dc.indexOf("; " + prefix);

	if (begin == -1) {
		begin = dc.indexOf(prefix);
		if (begin != 0)
			return null;
	} else begin += 2;

	var end = document.cookie.indexOf(";", begin);

	if (end == -1)
		end = dc.length;

	return unescape(dc.substring(begin + prefix.length, end));
}

// name - name of the cookie
// [path] - path of the cookie (must be same as path used to create cookie)
// [domain] - domain of the cookie (must be same as domain used to create cookie)
// * path and domain default if assigned null or omitted if no explicit argument proceeds
function deleteCookie(name, path, domain) {
	var cookie_path = (path) ? path : bitCookiePath;
	var cookie_domain = escape((domain) ? domain : bitCookieDomain);
	if (getCookie(name)) {
		document.cookie = name + "="
			+ "; path=" +  cookie_path + "; domain=" + cookie_domain + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
}

// Check / Uncheck all Checkboxes
// checkboxes need to have the same name elements_name
// e.g. <input type="checkbox" name="my_ename[]">, will arrive as Array in php.
function switchCheckboxes(the_form, elements_name, switcher_name) {
	var elements = $(the_form).elements[elements_name];
	var elements_cnt = ( typeof (elements.length) != 'undefined') ? elements.length : 0;

	if (elements_cnt) {
		for (var i = 0; i < elements_cnt; i++) {
			elements[i].checked = document.forms[the_form].elements[switcher_name].checked;
		}
	} else {
		elements.checked = document.forms[the_form].elements[switcher_name].checked;
	}
	return true;
}


// function added for use in navigation dropdown
// example :
// <select name="anything" onchange="go(this);">
// <option value="http://bitweaver.org">bitweaver.org</option>
// </select>
function go(o) {
	if (o.options[o.selectedIndex].value != "") {
		location = o.options[o.selectedIndex].value;
		o.options[o.selectedIndex] = 1;
	}
	return false;
}

/** \brief: modif a textarea dimension
 * \elementId = textarea idea
 * \height = nb pixels to add to the height (the number can be negative)
 * \width = nb pixels to add to the width
 * \formid = form id (needs to have 2 input rows and cols
 **/
function textareasize(elementId, height, width, formId) {
	textarea = $(elementId);
	form = $(formId);
	if (textarea && height != 0 && textarea.rows + height > 5) {
		textarea.rows += height;
		if (form.rows)
			form.rows.value = textarea.rows;
	}
	if (textarea && width != 0 && textarea.cols + width > 10) {
		 textarea.cols += width;
		if (form.cols)
			form.cols.value = textarea.cols;
	}
}

// function:	popUpWin
// desc:		span a new window which is XHTML 1.0 Strict compliant and in accordance with WCAG
// params:		url:		the url for the new window
//				type:		standard or fullscreen
//				strWidth:	width of the window
//				strHeight:	height of the spawned window
// usage:		<a href="<URL>" title="{tr}Opens link in new window{/tr}" onkeypress="popUpWin(this.href,'standard',600,400);" onclick="popUpWin(this.href,'standard',600,400);return false;">{tr}FooBar{/tr}</a>
var newWindow = null;
function closeWin(){
	if (newWindow != null){
		if(!newWindow.closed)
			newWindow.close();
	}
}

function popUpWin(url, type, strWidth, strHeight) {
	closeWin();
	if (type == "fullScreen"){
		strWidth = screen.availWidth - 10;
		strHeight = screen.availHeight - 160;
	}
	var tools="";
	if (type == "standard" || type == "fullScreen") tools = "resizable,toolbar=no,location=no,scrollbars=yes,menubar=no,width="+strWidth+",height="+strHeight+",top=0,left=0";
	if (type == "console") tools = "resizable,toolbar=no,location=no,scrollbars=yes,width="+strWidth+",height="+strHeight+",left=0,top=0";
	newWindow = window.open(url, 'newWin', tools);
	newWindow.focus();
}

/*----------- functions that might not be in use anymore -----------*/
function setUserModuleFromCombo(id) {
	$('usermoduledata').value = $('usermoduledata').value
		+ $(id).options[$(id).selectedIndex].value;
//$('usermoduledata').value='das';
}
