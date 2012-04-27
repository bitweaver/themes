{* $Header$ *}
{strip}
{foreach from=$gBitThemes->mRawFiles.css item=cssFile}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$cssFile}" media="all" />
{/foreach}
{foreach from=$gBitThemes->mRawFiles.js item=jsFile}
	<script src="{$jsFile}" type="text/javascript"></script>
{/foreach}
{if $gBitThemes->mStyles.joined_css}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitThemes->mStyles.joined_css}" media="all" />
{/if}
{/strip}

{if $gBrowserInfo.browser eq 'ie' && $gBitSystem->getConfig('themes_use_msie_js_fix') && $gBrowserInfo.maj_ver lt '8'}
		<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/fixes/ie7/IE{$gBitSystem->getConfig('themes_use_msie_js_fix')}.js"></script>
{/if}
