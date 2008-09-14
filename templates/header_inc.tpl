{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.42 2008/09/14 16:08:04 squareing Exp $ *}
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

{* This is only kept here for legacy code and will be removed once we've fully
   weened bitweaver off the old storage plugins. this is required by the old
   bitstorage plugin. users with regular installs of bitweaver > 2.1 can remove
   this. *}
{if $loadMultiFile}
	{jspack ifile=libs/multifile.js defer='defer'}
{/if}

{foreach from=$gBitThemes->mRawFiles.javascript item=jsFile}
	<script type="text/javascript" src="{$jsFile}"></script>
{/foreach}
{/strip}

{if $gBrowserInfo.browser eq 'ie' and $gBitSystem->isFeatureActive( 'themes_use_msie_png_hack' )}
	<!--[if lt IE 8]>
		{jspack ifile=fixes/ie7/IE8.js}
	<![endif]-->
{/if}
