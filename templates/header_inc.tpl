{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.37 2008/06/20 04:16:40 spiderr Exp $ *}
{strip}
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
	{jspack ifile=libs/multifile.js}
{/if}
{foreach from=$gBitThemes->mRawJsFiles item=jsFile}
	<script type="text/javascript" src="{$jsFile}"></script>
{/foreach}
{/strip}

{if $gBrowserInfo.browser eq 'ie'}
	<!-- this wierdness fixes png display and CSS driven dropdown menus in GUESS WHAT BROWSER -->
	<!--[if lt IE 7]>
	{if $gBitSystem->isFeatureActive( 'themes_use_msie_png_hack' )}
		<script type="text/javascript">
			IE7_PNG_SUFFIX = ".png";
		</script>
	{/if}
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/fixes/ie7/ie7-standard-p.js"></script>
	<![endif]-->
	<!-- CSS driven dropdown menus are still broken in IE7 -->
	<!--[if IE 7]>
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/fixes/ie7/ie7-core.js"></script>
	<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/fixes/ie7/ie7-css2-selectors.js"></script>
	<![endif]-->
{/if}

