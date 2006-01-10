{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.1.2.10 2006/01/10 19:34:29 squareing Exp $ *}
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

{if $gBitSystemPrefs.disable_jstabs ne 'y'}
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/jscompressor.php?jsfile=libs/tabpane.js"></script>
{/if}

{if $gBitSystemPrefs.disable_fat ne 'y'}
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/jscompressor.php?jsfile=libs/fat.js"></script>
{/if}
{/strip}

{if $browserInfo.browser eq 'ie'}
	<!-- this wierdness fixes png display and CSS driven dropdown menus in GUESS WHAT BROWSER -->
	<!--[if lt IE 7]>
	<script type="text/javascript">
		IE7_PNG_SUFFIX = ".png";
	</script>
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/jscompressor.php?jsfile=fixes/ie7/ie7-standard-p.js"></script>
	<![endif]-->
{/if}
