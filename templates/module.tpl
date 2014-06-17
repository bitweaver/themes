{strip}
{if empty($moduleArea)}
	{if $moduleParams.layout_area == "l"}
		{assign var=area value="navig"}
	{elseif $moduleParams.layout_area == "r"}
		{assign var=area value="extra"}
	{elseif $moduleParams.layout_area == "t"}
		{assign var=area value="header"}
	{elseif $moduleParams.layout_area == "b"}
		{assign var=area value="footer"}
	{else}
		{assign var=area value=0}
	{/if}
{else}
	{assign var=area value=$moduleArea}
{/if}

<div class="panel panel-default module{if !empty($modInfo.class)} {$modInfo.class}{/if} {$modInfo.name|replace:'_':'-'}" {if !empty($area)}id="{$area}{$moduleParams.pos}"{/if}>
	{if $moduleParams.title}
		<div class="panel-heading">
			{if $gBitSystem->isFeatureActive( 'themes_module_controls' )}
				<div class="control">
					<a title="{tr}Move module up{/tr}" href="{$smarty.const.THEMES_PKG_URL}module_controls_inc.php?move=up&module_id={$module_id}">
						{biticon ipackage=liberty iname="move_up" iexplain="up"}</a>
					<a title="{tr}Move module down{/tr}" href="{$smarty.const.THEMES_PKG_URL}module_controls_inc.php?move=down&module_id={$module_id}">
						{biticon ipackage=liberty iname="move_down" iexplain="down"}</a>
					<a title="{tr}Move module to opposite side{/tr}" href="{$smarty.const.THEMES_PKG_URL}module_controls_inc.php?move={$moduleParams.layout_area}&module_id={$module_id}">
						{biticon ipackage=liberty iname="move_left_right" iexplain="move left right"}</a>
				</div>
			{/if}
			{if $gBitSystem->isFeatureActive( 'themes_collapsible_modules' )}<a href="javascript:BitBase.toggle('module{$area}{$moduleParams.pos}','block',true);">{/if}
				{if $modInfo.notra}
					{$moduleParams.title}
				{else}
					{tr}{$moduleParams.title}{/tr}
				{/if}
			{if $gBitSystem->isFeatureActive( 'themes_collapsible_modules' )}</a>{/if}
		</div>
	{/if}
	<div class="panel-body"{if $gBitSystem->isFeatureActive( 'themes_collapsible_modules' )} style="display:{$moduleParams.toggle_state|default:block};" id="module{$area}{$moduleParams.pos}"{/if}>
		{$modInfo.data}
	</div>
</div>
{/strip}
