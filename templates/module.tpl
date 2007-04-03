{* $Header: /cvsroot/bitweaver/_bit_themes/templates/module.tpl,v 1.2 2007/04/03 14:10:54 squareing Exp $ *}
{strip}
<div class="module box {$modInfo.name}">
	{if $modInfo.title}
		<h3>
			{if $gBitSystem->isFeatureActive( 'themes_module_controls' )}
				<div class="control">
					<a title="{tr}Move module up{/tr}" href="{$smarty.const.KERNEL_PKG_URL}module_controls_inc.php?fMove=up&fPackage={$module_layout}&fModule={$module_id}">
						{biticon ipackage=liberty iname="move_up" iexplain="up"}</a>
					<a title="{tr}Move module down{/tr}" href="{$smarty.const.KERNEL_PKG_URL}module_controls_inc.php?fMove=down&fPackage={$module_layout}&fModule={$module_id}">
						{biticon ipackage=liberty iname="move_down" iexplain="down"}</a>
					<a title="{tr}Move module to opposite side{/tr}" href="{$smarty.const.KERNEL_PKG_URL}module_controls_inc.php?fMove={$colkey}&fPackage={$module_layout}&fModule={$module_id}">
						{biticon ipackage=liberty iname="move_left_right" iexplain="move left right"}</a>
					<a title="{tr}Unassign this module{/tr}" href="{$smarty.const.KERNEL_PKG_URL}module_controls_inc.php?fMove=unassign&fPackage={$module_layout}&fModule={$module_id}" onclick="return confirm('{tr}Are you sure you want to unassign this module?{/tr}')">
						{biticon ipackage="icons" iname="edit-delete" iexplain="remove"}</a>
				</div>
			{/if}
			{if $gBitSystem->isFeatureActive( 'themes_collapsible_modules' )}<a href="javascript:toggle('{$modInfo.name}');">{/if}
				{tr}{$modInfo.title}{/tr}
			{if $gBitSystem->isFeatureActive( 'themes_collapsible_modules' )}</a>{/if}
		</h3>
	{/if}
	<div class="boxcontent" id="{$modInfo.name}"{if $gBitSystem->isFeatureActive( 'themes_collapsible_modules' )} style="display:{$moduleParams.toggle_state};"{/if}>
		{$modInfo.data}
	</div>
</div>
{/strip}
