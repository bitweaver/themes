{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.13 2006/03/01 18:35:18 spiderr Exp $ *}
{strip}
{if $gBitSystem->mStyles.styleSheet}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitSystem->mStyles.styleSheet}" media="all" />
{/if}
{if $gBitSystem->mStyles.browserStyleSheet}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitSystem->mStyles.browserStyleSheet}" media="all" />
{/if}
{if $gBitSystem->mStyles.customStyleSheet}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitSystem->mStyles.custumStyleSheet}" media="all" />
{/if}
{foreach from=$gBitSystem->mStyles.altStyleSheets item=alt_path key=alt_name}
	<link rel="alternate stylesheet" title="{$alt_name}" type="text/css" href="{$alt_path}" media="screen" />
{/foreach}

{if $gBitSystem->getConfig('disable_jstabs') ne 'y'}
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/libs/tabpane.js"></script>
{/if}
{/strip}

{* drag and drop javascript doesn't work with fat loaded - hardcode css for now *}
{if $loadDragDrop}
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/libs/drag/dragdrop.js"></script>

	{literal}
	<style type="text/css">
		ul.boxy,
		ul.boxy li			{min-height:2em; list-style-type:none; border:#ccc solid 1px; padding:4px 4px 0 4px; margin:0px; border:#ccc 1px solid;}
		ul.boxy				{background:#fff; margin:0px;}
		ul.boxy li			{background:#edf; cursor:move; margin:0 0 4px 0; padding:2px;}
		ul.boxy li			{position:absolute; width:250px;}
		.layout ul.boxy li	{position:relative !important; width:auto !important;}
		ul#center li		{background:#def;}
	</style>

	<script type="text/javascript">//<![CDATA[
		window.onload = function() {
			var list = $( "left" );
			DragDrop.makeListContainer( list, 'side_columns' );
			list.onDragOver = function() { this.style["background"] = "#feb"; };
			list.onDragOut = function() {this.style["background"] = "none"; };

			list = $( "center" );
			DragDrop.makeListContainer( list, 'center_column' );
			list.onDragOver = function() { this.style["background"] = "#dfe"; };
			list.onDragOut = function() {this.style["background"] = "none"; };

			list = $( "right" );
			DragDrop.makeListContainer( list, 'side_columns' );
			list.onDragOver = function() { this.style["background"] = "#feb"; };
			list.onDragOut = function() {this.style["background"] = "none"; };
		};

		function getSort( id ) {
			order = $( id );
			order.value = DragDrop.serData( id, null );
		}
	//]]></script>
	{/literal}
{elseif !$gBitSystem->getConfig('disable_fat')}
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/libs/fat.js"></script>
{/if}

{if $gBrowserInfo.browser eq 'ie'}
	<!-- this wierdness fixes png display and CSS driven dropdown menus in GUESS WHAT BROWSER -->
	<!--[if lt IE 7]>
	<script type="text/javascript">
		IE7_PNG_SUFFIX = ".png";
	</script>
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/fixes/ie7/ie7-standard-p.js"></script>
	<![endif]-->
{/if}
