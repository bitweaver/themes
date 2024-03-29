{strip}
{assign var=tablimit value=10}

{formfeedback hash=$feedback}

{form}
	<input type="hidden" name="page" value="{$page}" />
	<input type="hidden" name="nocollapse" value="{$smarty.request.nocollapse}" />

	{jstabs}
		{foreach name=PkgLayouts from=$layouts item=layout key=module_package}

			{if !$module_package || $module_package == 'kernel'}
				{assign var=tabTitle value="Default"|tra}
			{else}
				{assign var=tabTitle value=$module_package|capitalize}
			{/if}


			{jstab title=$tabTitle}

				<div class="floaticon">
					{smartlink ititle="Edit this Layout" booticon="fa-edit" page=layout module_package=$module_package}
					{smartlink ititle="Remove this Layout" booticon="fa-trash" page=$page remove_layout=$module_package onclick="return confirm('{tr}Are you sure you want to remove this layout? This can not be undone.{/tr}')"}
				</div>

				<h1 id="{$module_package}">
					{tr}Current Layout{/tr} <strong>{$tabTitle}</strong>
				</h1>

				<table class="width100p">
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

								<table class="table data width100p">
									<tr>
										<th>{tr}{$colkey} area{/tr}</th>
									</tr>
									{section name=ix loop=$layout.$area}
										<tr>
											<td>
												{if $roles }	
													{include file="bitpackage:themes/module_config_role_inc.tpl" modInfo=$layout.$area[ix]}
												{else}
													{include file="bitpackage:themes/module_config_inc.tpl" modInfo=$layout.$area[ix]}
												{/if}	
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

		{include file="bitpackage:themes/admin_layout_inc.tpl"}

	{/jstabs}

	<div class="submit">
		<input type="submit" class="btn btn-default" name="update_modules" value="{tr}Apply module settings{/tr}" />
	</div>
{/form}

{/strip}
