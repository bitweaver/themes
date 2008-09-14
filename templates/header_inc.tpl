{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.43 2008/09/14 19:23:39 wjames5 Exp $ *}
{strip}
{foreach from=$gBitThemes->mRawFiles.css item=cssFile}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$cssFile}" media="all" />
{/foreach}
{if $gBitThemes->mStyles.joined_css}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitThemes->mStyles.joined_css}" media="all" />
{/if}
{if $gBitThemes->mStyles.styleSheet}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitThemes->mStyles.styleSheet}" media="all" />
{/if}
{if $gBitThemes->mStyles.browser_css}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitThemes->mStyles.browser_css}" media="all" />
{/if}
{/strip}

{if $gBrowserInfo.browser eq 'ie' and $gBitSystem->isFeatureActive( 'themes_use_msie_png_hack' )}
	<!--[if lt IE 8]>
		{jspack ifile=fixes/ie7/IE8.js}
	<![endif]-->
{/if}
