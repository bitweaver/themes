{* $Header: /cvsroot/bitweaver/_bit_themes/templates/custom_module.tpl,v 1.4 2009/05/28 17:48:45 tekimaki_admin Exp $ *}
{bitmodule title=$moduleParams.title name=$moduleParams.name}
	{if $moduleParams.data}{eval var=$moduleParams.data}{/if}
{/bitmodule}
