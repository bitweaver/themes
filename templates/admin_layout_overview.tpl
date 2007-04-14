{strip}
{formfeedback hash=$feedback}

{form}
	<input type="hidden" name="page" value="{$page}" />

	{foreach from=$layouts item=layout key=module_package}
		<h1 id="{$module_package}">
			{tr}Current Layout of '{if !$module_package || $module_package=='kernel'}Site Default{else}{$module_package|capitalize}{/if}'{/tr}
			&nbsp; {smartlink ititle="Remove this Layout" ibiticon="icons/edit-delete" page=$page remove_layout=$module_package ionclick="return confirm('{tr}Are you sure you want to remove this layout? This can not be undone.{/tr}')"}
		</h1>

		<table style="width:100%" cellpadding="5" cellspacing="0" border="0">
			<tr>
				{cycle values="even,odd" print=0}
				{foreach from=$layoutAreas item=area key=colkey}
					{if $colkey =='top'}
						<td class="{cycle}" colspan="3" style="vertical-align:top;">
					{elseif $colkey =='bottom'}
						</tr>
						<tr>
							<td class="{cycle}" colspan="3" style="vertical-align:top;">
					{else}
						<td class="{cycle}" style="width:33%; vertical-align:top;">
					{/if}

						<table class="data" style="width:100%">
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
									<td colspan="3" align="center">
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
		<hr />
	{/foreach}

	<div class="submit">
		<input type="submit" name="update_modules" value="{tr}Apply module settings{/tr}" />
		<input type="submit" name="fix_pos" value="{tr}Adjust module positions{/tr}" />
	</div>
{/form}
{/strip}
