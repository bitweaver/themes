{if $gBitSystem->mAjax == 'prototype'}
	{foreach from=$gBitSystem->mAjaxLibs item=ajaxLib}
		{if $ajaxLib == 'debugger.js'}
			<div id="jsdebug" style="padding:1em; margin:1em; border:0.5em solid #900; background:#fff; color:#000;">Prototype Debugger:<br /></div>
		{/if}
	{/foreach}
	<div id="spinner" style="z-index:1500; position:absolute; top:50%; left:50%; margin-left:-125px; margin-top:-35px; width:250px; line-height:50px; padding:25px 0; border:3px solid #ccc; background:#fff; font-weight:bold; color:#900; text-align:center; display:none;">{biticon ipackage=liberty iname=busy iexplain=Loading style="vertical-align:middle;"}&nbsp;&nbsp;&nbsp;&nbsp;{tr}Loading{/tr}&hellip;</div>
{/if}
