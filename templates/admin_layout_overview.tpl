{strip}
{assign var=tablimit value=10}

{formfeedback hash=$feedback}

{form}
	<input type="hidden" name="page" value="{$page}" />
	<input type="hidden" name="nocollapse" value="{$smarty.request.nocollapse}" />

	{foreach name=PkgLayouts from=$layouts item=layout key=module_package}
		{if $smarty.foreach.PkgLayouts.total >= $tablimit}
			<dl>
				<dt>{$smarty.foreach.PkgLayouts.iteration}</dt>
				{if !$module_package || $module_package == 'kernel'}
					<dd>{tr}Default{/tr}</dd>
				{else}
					<dd>{$module_package|capitalize}</dd>
				{/if}
			</dl>
		{/if}
	{/foreach}

	{jstabs}
		{foreach name=PkgLayouts from=$layouts item=layout key=module_package}

			{* if there are too many tabs, we only display numbers *}
			{if $smarty.foreach.PkgLayouts.total lt $tablimit}
				{if !$module_package || $module_package == 'kernel'}
					{assign var=TabTitle value="Default"}
				{else}
					{assign var=TabTitle value=$module_package|capitalize}
				{/if}
			{else}
				{assign var=TabTitle value="&nbsp;"|cat:$smarty.foreach.PkgLayouts.iteration|cat:"&nbsp;"}
			{/if}

			{jstab title=$TabTitle}

				<div class="floaticon">
					{smartlink ititle="Edit this Layout" ibiticon="icons/accessories-text-editor" page=layout module_package=$module_package}
					{smartlink ititle="Remove this Layout" ibiticon="icons/edit-delete" page=$page remove_layout=$module_package ionclick="return confirm('{tr}Are you sure you want to remove this layout? This can not be undone.{/tr}')"}
				</div>

				<h1 id="{$module_package}">
					{tr}Current Layout of {if $TabTitle == 'Default'}Site Default{else}{$TabTitle}{/if}{/tr}
				</h1>

				<table class="layouts_table">
					<tr>
						{cycle values="even,odd" print=0}
						{foreach from=$layoutAreas item=area key=colkey}
							{if $colkey =='top'}
								<td class="{cycle} aligntop" colspan="3">
							{elseif $colkey =='bottom'}
								</tr>
								<tr>
									<td class="{cycle} aligntop" colspan="3">
							{else}
								<td class="{cycle} width33p aligntop">
							{/if}

								<table class="data width100p>
									<tr>
										<th>{tr}{$colkey} area{/tr}</th>
									</tr>
									{section name=ix loop=$layout.$area}
										<tr>
											<td>
												{include file="bitpackage:themes/module_config_inc.tpl" modInfo=$layout.$area[ix]}
											</td>
										</tr>
									{sectionelse}
										<tr>
											<td colspan="3" class="aligncenter">
												{if $colkey eq 'center'}{tr}Default{/tr}{else}{tr}None{/tr}{/if}
											</td>
										</tr>
									{/section}
								</table>
							</td>

							{if $colkey =='top'}
								</tr>
								<tr>
							{/if}
						{/foreach}
					</tr>
				</table>
			{/jstab}
		{/foreach}

		{jstab title="Help"}
			{include file="bitpackage:themes/admin_layout_help.tpl"}
		{/jstab}

	{/jstabs}

	<div class="submit">
		<input type="submit" name="update_modules" value="{tr}Apply module settings{/tr}" />
	</div>
{/form}

{/strip}