{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.1.2.1 2005/07/10 08:06:38 squareing Exp $ *}
{strip}
{if $gBitLoc.styleSheet}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitLoc.styleSheet}" media="all" />
{/if}
{if $gBitLoc.browserStyleSheet}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitLoc.browserStyleSheet}" media="all" />
{/if}
{if $gBitLoc.customStyleSheet}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitLoc.custumStyleSheet}" media="all" />
{/if}
{foreach from=$gBitLoc.altStyleSheets item=alt_path key=alt_name}
	<link rel="alternate stylesheet" title="{$alt_name}" type="text/css" href="{$alt_path}" media="screen" />
{/foreach}

{if $gBitSystemPrefs.disable_jstabs ne 'y'}
	<script type="text/javascript" src="{$gBitLoc.THEMES_PKG_URL}js/tabs/tabpane.js"></script>
{/if}
{/strip}

{if $gBitLoc.browser.client eq 'ie'}
	<!-- this wierdness fixes png display and CSS driven dropdown menus in GUESS WHAT BROWSER -->
	<!--[if gte IE 5.5000]>
		<script type="text/javascript" src="{$gBitLoc.THEMES_PKG_URL}js/pngfix.js"></script>
	<![endif]-->
	<!--[if gte IE 5.0]>
		<script type="text/javascript">
			var nexusMenus = new Array(1)
			nexusMenus[0] = 'nav'
			{if $hoverfix}
				{include file=$hoverfix}
			{/if}
		</script>
		<script type="text/javascript" src="{$gBitLoc.THEMES_PKG_URL}js/hoverfix.js"></script>
	<![endif]-->
{/if}
