{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.52 2009/05/28 20:16:45 spiderr Exp $ *}
{strip}
{foreach from=$gBitThemes->mRawFiles.css item=cssFile}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$cssFile}" media="all" />
{/foreach}
{if $gBitThemes->mStyles.joined_css}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitThemes->mStyles.joined_css}" media="all" />
{/if}
{/strip}

{if $gBrowserInfo.browser eq 'ie'}
       {if $gBrowserInfo.maj_ver lt '8'}
			<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javscript/fixes/ie7/IE8.js"></script>
       {/if}
{/if}
