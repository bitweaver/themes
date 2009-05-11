{* $Header: /cvsroot/bitweaver/_bit_themes/templates/header_inc.tpl,v 1.49 2009/05/11 19:32:28 bitweaver Exp $ *}
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
               {jspack ifile=fixes/ie7/IE6.js}
       {/if}
{/if}
