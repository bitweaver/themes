<!DOCTYPE html 	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>{$browserTitle} - {$gBitSystem->getConfig('site_title')}</title>

	<link rel="stylesheet" title="{$style}" type="text/css" href="{$gBitThemes->getStyleCssFile($smarty.request.site_style,1)}" media="all" />
	{include file="bitpackage:kernel/header_inc.tpl"}
	{include file="bitpackage:themes/header_inc.tpl"}
</head>
<body id="jspopup">
	<div class="display jspopup">
		<div class="header">
			<title>{$browserTitle} - {$gBitSystem->getConfig('site_title')}</title>
		</div>

		<div class="body">
