{strip}
{foreach from=$gBitThemes->getRawFileUrls('css') item=cssFile}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$cssFile}" media="all">
{/foreach}

{foreach from=$gBitThemes->getRawFileUrls('js') item=jsFile}
	<script src="{$jsFile}"></script>
{/foreach}
{if $gBitThemes->mStyles.joined_css}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitThemes->mStyles.joined_css}" media="all">
{/if}
{/strip}
