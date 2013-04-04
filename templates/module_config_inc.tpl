{capture name=shared}
	{smartlink ititle="Up" booticon="icon-arrow-up" iforce="icon" page=$page move_module=up module_package=$module_package module_id=`$modInfo.module_id`}
	{smartlink ititle="Down" booticon="icon-arrow-down" iforce="icon" page=$page move_module=down module_package=$module_package module_id=`$modInfo.module_id`}
	{if $colkey eq 'left' or $colkey eq 'right'}
		{if $colkey == 'left'}
			{assign var=move value=right}
		{elseif $colkey == 'right'}
			{assign var=move value=left}
		{/if}
		{smartlink ititle="Move module" booticon="icon-arrow-$move" iforce="icon" iexplain="`$move`" page=$page move_module=$move module_package=$module_package module_id=$modInfo.module_id}
	{/if}
	{if $gBitThemes->isCustomModule( $modInfo.module_rsrc )}
		{smartlink ititle="Edit" booticon="icon-edit" iforce=icon iexplain="Edit" page=custom_modules name=$modInfo.module_rsrc|regex_replace:"!.*\/!":"" action=edit}
	{/if}
	{smartlink ititle="Unassign" booticon="icon-trash" iforce=icon iexplain="Delete" ionclick="return confirm('Are you sure you want to remove `$modInfo.name`?');" page=$page move_module=unassign module_package=$module_package module_id=$modInfo.module_id }
{/capture}

{strip}
<h3>
	{if !$smarty.request.nocollapse && !$condensed && $gBitThemes->isJavascriptEnabled()}<a href="javascript:BitBase.flipWithSign('id-{$modInfo.module_id}');"><span id="flipperid-{$modInfo.module_id}" class="monospace">[+]</span> {/if}
		{$modInfo.name}
		<input type="hidden" name="modules[{$modInfo.module_id}][layout_area]" value="{$area}" />
		<input type="hidden" name="modules[{$modInfo.module_id}][layout]" value="{$module_package}" />
	{if !$smarty.request.nocollapse && !$condensed && $gBitThemes->isJavascriptEnabled()}</a>{/if}
	<br />
	{$smarty.capture.shared}
</h3>

{if !$condensed}
	{if !$smarty.request.nocollapse && $gBitThemes->isJavascriptEnabled()}<div id="id-{$modInfo.module_id}" style="display:none;">{/if}
		<table class="data">
			<tr>
				<td class="alignright">{tr}Position{/tr}</td>
				<td>
					<input type="text" size="4" name="modules[{$modInfo.module_id}][pos]" value="{$modInfo.pos}" />
				</td>
			</tr>

			{if !$gBitThemes->isCustomModule( $modInfo.module_rsrc )}
				<tr>
					<td class="alignright">{tr}Title{/tr}</td>
					<td><input type="text" size="15" name="modules[{$modInfo.module_id}][title]" value="{$modInfo.title|escape}" /></td>
				</tr>
				<tr>
					<td class="alignright">{tr}Rows{/tr}</td>
					<td><input type="text" size="15" name="modules[{$modInfo.module_id}][module_rows]" value="{$modInfo.module_rows}" /></td>
				</tr>
				<tr>
					<td class="alignright">{tr}Parameters{/tr}</td>
					<td><input type="text" size="15" name="modules[{$modInfo.module_id}][params]" value="{$modInfo.params}" /></td>
				</tr>
			{/if}

			<tr>
				<td class="alignright">{tr}Cache Time{/tr}</td>
				<td><input type="text" size="15" name="modules[{$modInfo.module_id}][cache_time]" value="{$modInfo.cache_time}" /></td>
			</tr>

			<tr>
				<td class="alignright">{tr}Groups{/tr}</td>
				<td>
					<select multiple="multiple" size="3" name="modules[{$modInfo.module_id}][groups][]">
						{foreach from=$groups key=groupId item=group}
							{assign var=selected value=n}
							{foreach from=$modInfo.module_groups item=module_groups}
								{if $groupId == $module_groups}
									{assign var=selected value=y}
								{/if}
							{/foreach}
							<option value="{$groupId}" {if $selected eq 'y'}selected="selected"{/if}>{$group.group_name}</option>
						{/foreach}
					</select>
				</td>
			</tr>
		</table>
	{if !$smarty.request.nocollapse && $gBitThemes->isJavascriptEnabled()}</div>{/if}
{/if}
{/strip}
