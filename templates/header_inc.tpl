{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.1.2.7 2005/12/29 22:04:26 squareing Exp $ *}
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
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/libs/tabpane.js"></script>
{/if}

{if $gBitSystemPrefs.disable_fat ne 'y'}
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/libs/fat.js"></script>
{/if}
{/strip}

{if $browserInfo.browser eq 'ie'}
	<!-- this wierdness fixes png display and CSS driven dropdown menus in GUESS WHAT BROWSER -->
	{if !$quicktags}
		<!--[if gte IE 5.5000]>
			<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/fixes/sleight.js"></script>
		<![endif]-->
	{/if}
	<!--[if gte IE 5.0]>
		<script type="text/javascript">
			var nexusMenus = new Array(1)
			nexusMenus[0] = 'nav'
			{if $hoverfix}
				{include file=$hoverfix}
			{/if}
		</script>
		<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/fixes/hoverfix.js"></script>
	<![endif]-->
{/if}
