<!DOCTYPE html 	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>{tr}{$gPageTitle}{/tr}</title>

	{if $smarty.request.site_style}
		<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitSystem->getStyleCss($smarty.request.site_style)}" media="all" />
	{/if}
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

	<script type="text/javascript" src="{$smarty.const.UTILL_PKG_URL}javascript/bitweaver.js"></script>

	{literal}
		<script type="text/javascript"><!--
		function returnAttachmentId(attachmentId) {
			self.opener.document.getElementById("existing_attachment_id_input").value = attachmentId;
			self.close();
		}
		--></script>
	{/literal}

	<!--[if gte IE 5.5000]>
		<script type="text/javascript" src="{$smarty.const.UTIL_PKG_URL}javascript/fixes/pngfix.js"></script>
	<![endif]-->

</head>
<body>
	<div id="attbrowser">
		<div class="display attbrowser">
			<div class="header">
				<h1>{$gPageTitle}</h1>
			</div>

			<div class="body">
