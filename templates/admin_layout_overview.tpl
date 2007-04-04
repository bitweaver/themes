{strip}
{formfeedback hash=$feedback}

{form}
	<input type="hidden" name="page" value="{$page}" />

	{foreach from=$layouts item=layout key=module_package}
		<h1>{tr}Current Layout of '{if !$module_package || $module_package=='kernel'}Site Default{else}{$module_package|capitalize}{/if}'{/tr}</h1>

		<table style="width:100%" cellpadding="5" cellspacing="0" border="0">
			<tr>
				{foreach from=$layoutAreas item=area key=colkey }
					<td style="width:33%" valign="top">
						<table class="data" style="width:100%">
							<tr>
								<th>{tr}{$colkey} column{/tr}</th>
							</tr>
							{section name=ix loop=$layout.$area}
								<tr class="{cycle values="even,odd"}">
									<td>
										{include file="bitpackage:themes/module_config_inc.tpl" modInfo=$layout.$area[ix]}
									</td>
								</tr>
							{sectionelse}
								<tr class="{cycle values="even,odd"}" >
									<td colspan="3" align="center">
										{if $colkey eq 'center'}{tr}Default{/tr}{else}{tr}None{/tr}{/if}
									</td>
								</tr>
							{/section}
						</table>
					</td>
				{/foreach}
			</tr>
		</table>
		<hr />
	{/foreach}

	<div class="submit">
		<input type="submit" name="update_modules" value="{tr}Update Module Settings{/tr}" />
	</div>
{/form}
{/strip}
