{capture name=shared}
	{smartlink ititle="Up" booticon="fa-arrow-up" page=$page move_module=up module_package=$module_package module_id=$modInfo.module_id}
	{smartlink ititle="Down" booticon="fa-arrow-down" page=$page move_module=down module_package=$module_package module_id=$modInfo.module_id}
	{if $colkey eq 'left' or $colkey eq 'right'}
		{if $colkey == 'left'}
			{assign var=icon value=next}
			{assign var=move value=right}
		{elseif $colkey == 'right'}
			{assign var=icon value=previous}
			{assign var=move value=left}
		{/if}
		{smartlink ititle="Move module" booticon="fa-arrow-$icon" iexplain="`$move`" page=$page move_module=$move module_package=$module_package module_id=$modInfo.module_id}
	{/if}
	{if $gBitThemes->isCustomModule( $modInfo.module_rsrc )}
		{smartlink ititle="Edit" booticon="fa-edit" iexplain="Edit" page=custom_modules name=$modInfo.module_rsrc|regex_replace:"!.*\/!":"" action=edit}
	{/if}
	{smartlink ititle="Unassign" booticon="fa-trash" iexplain="Delete" ionclick="return confirm('Are you sure you want to remove `$modInfo.name`?');" page=$page move_module=unassign module_package=$module_package module_id=$modInfo.module_id }
{/capture}

{strip}
<strong>
	{if !$smarty.request.nocollapse && !$condensed && $gBitThemes->isJavascriptEnabled()}<a href="javascript:BitBase.flipWithSign('id-{$modInfo.module_id}');"><span id="flipperid-{$modInfo.module_id}" class="monospace">[+]</span> {/if}
		{$modInfo.name}
		<input type="hidden" name="modules[{$modInfo.module_id}][layout_area]" value="{$area}" />
		<input type="hidden" name="modules[{$modInfo.module_id}][layout]" value="{$module_package}" />
	{if !$smarty.request.nocollapse && !$condensed && $gBitThemes->isJavascriptEnabled()}</a>{/if}
	<br />
	{$smarty.capture.shared}
</strong>

{if !$condensed}
	{if !$smarty.request.nocollapse && $gBitThemes->isJavascriptEnabled()}<div id="id-{$modInfo.module_id}" style="display:none;">{/if}
		<table class="table data">
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
				<td class="alignright">{tr}Roles{/tr}</td>
				<td>
					<select multiple="multiple" size="3" name="modules[{$modInfo.module_id}][roles][]">
						{foreach from=$roles key=roleId item=role}
							{assign var=selected value=n}
							{foreach from=$modInfo.module_roles item=module_roles}
								{if $roleId == $module_roles}
									{assign var=selected value=y}
								{/if}
							{/foreach}
							<option value="{$roleId}" {if $selected eq 'y'}selected="selected"{/if}>{$role.role_name}</option>
						{/foreach}
					</select>
				</td>
			</tr>
		</table>
	{if !$smarty.request.nocollapse && $gBitThemes->isJavascriptEnabled()}</div>{/if}
{/if}
{/strip}
