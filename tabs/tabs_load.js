if( tabSystem && tabSystem.addEventHandler && window && window.unescape ) {
	tabSystem.prototype.switchTabByHeadingText = function ( HeadingString ) {
		// beware that if two tabs share same name, first will get favour
		String.prototype.removeHTML=function( e ) { // nested funcs are good
			return this.replace(/(<.+>)/g,"");
		}
		
		var HeadingString=window.unescape(HeadingString); // remove %x
	
		for(var x=0;x<this.tabs.length;x++) { 
			// loop through all tabs
			if( this.tabs[x].hClone.innerHTML.removeHTML()==HeadingString) {
			
				// got one
				this.switchtab( x );
				return; // stick with first match
			}
		}
	}
	tabSystem.addEventHandler( "onload", function( e ) {
		if( window && window.location ) {
			function getIdentifyerFromURL( e ) { // get the #id
				var m=window.location.href.match(/#(.+)$/);
				if( m && m.length && m.length>0 ) return m[1];
			}
		
			var id=getIdentifyerFromURL();
			// switch the *first* instance to this text (you can modify this)
			if( this.instances[0] ) this.instances[0].switchTabByHeadingText( id );
		}
	}, tabSystem );
}
