{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.44 2008/09/15 17:08:43 squareing Exp $ *}
{strip}
{foreach from=$gBitThemes->mRawFiles.css item=cssFile}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$cssFile}" media="all" />
{/foreach}
{if $gBitThemes->mStyles.joined_css}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitThemes->mStyles.joined_css}" media="all" />
{/if}
{/strip}

{if $gBrowserInfo.browser eq 'ie' and $gBitSystem->isFeatureActive( 'themes_use_msie_png_hack' )}
	<!--[if lt IE 8]>
		{jspack ifile=fixes/ie7/IE8.js}
	<![endif]-->
{/if}
