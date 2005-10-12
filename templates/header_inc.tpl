{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.6 2005/10/12 15:13:58 spiderr Exp $ *}
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
	<script type="text/javascript" src="{$smarty.const.THEMES_PKG_URL}js/tabs/tabpane.js"></script>
{/if}

{if $gBitSystemPrefs.disable_fat ne 'y'}
	<script type="text/javascript" src="{$smarty.const.THEMES_PKG_URL}js/fat.js"></script>
{/if}
{/strip}

{if $browserInfo.browser eq 'ie'}
	<!-- this wierdness fixes png display and CSS driven dropdown menus in GUESS WHAT BROWSER -->
	<!--[if gte IE 5.5000]>
		<script type="text/javascript" src="{$smarty.const.THEMES_PKG_URL}js/sleight.js"></script>
	<![endif]-->
	<!--[if gte IE 5.0]>
		<script type="text/javascript">
			var nexusMenus = new Array(1)
			nexusMenus[0] = 'nav'
			{if $hoverfix}
				{include file=$hoverfix}
			{/if}
		</script>
		<script type="text/javascript" src="{$smarty.const.THEMES_PKG_URL}js/hoverfix.js"></script>
	<![endif]-->
{/if}
