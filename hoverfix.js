/** JavaScript for MSIE **/
function verifyIds(idList) {
	var ret = new Array();
	for(var i=0;i<idList.length;i++){
		var id = document.getElementById(idList[i]);
		if(id && (id.className == 'menu ver' || id.className == 'menu hor' || idList[i] == 'nav')) {ret.push(idList[i]);}
	}
	return ret;
}
ieHover = function() {
	ieIDs = verifyIds(nexusMenus);
	for (k=0;k<ieIDs.length;k++) {
		var ieULs = document.getElementById(ieIDs[k]).getElementsByTagName('ul');
		/** IE script to cover <select> elements with <iframe>s **/
		for (j=0; j<ieULs.length; j++) {
			ieULs[j].innerHTML = ('<iframe src="about:blank" scrolling="no" frameborder="0"></iframe>' + ieULs[j].innerHTML);
			var ieMat = ieULs[j].firstChild;
			ieMat.style.width=ieULs[j].offsetWidth+"px";
			ieMat.style.height=ieULs[j].offsetHeight+"px";
			ieULs[j].style.zIndex="1000000";
		}
		/** IE script to change class on mouseover **/
		var ieLIs = document.getElementById(ieIDs[k]).getElementsByTagName('li');
		for (var i=0; i<ieLIs.length; i++) if (ieLIs[i]) {
			ieLIs[i].onmouseover=function() {this.className+=" ieHover";}
			ieLIs[i].onmouseout=function() {this.className=this.className.replace(' ieHover', '');}
		}
	}
}
if (window.attachEvent) window.attachEvent('onload', ieHover);
