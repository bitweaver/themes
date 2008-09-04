{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.41 2008/09/04 16:59:12 spiderr Exp $ *}
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

{* annoyingly this is still required here since the liberty attachments plugin
is called before gBitThemes is set and can therefore not call loadJavascript.
if you want to load this from your php file, please use:
$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/libs/multifile.js', TRUE );
this variable here will go as soon as we can work out how to load this from the
plugin *}
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
