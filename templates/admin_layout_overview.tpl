{strip}
{formfeedback hash=$feedback}

{form}
	<input type="hidden" name="page" value="{$page}" />

	{foreach from=$layouts item=layout key=module_package}
		<h1 id="{$module_package}">
			{tr}Current Layout of '{if !$module_package || $module_package=='kernel'}Site Default{else}{$module_package|capitalize}{/if}'{/tr}
			&nbsp; {smartlink ititle="Edit this Layout" ibiticon="icons/accessories-text-editor" page=layout module_package=$module_package}
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

	<div class="row">
		{formlabel label="Adjust module postitions" for=""}
		{forminput}
			{smartlink ititle="Adjust module positions" ibiticon=icons/emblem-symbolic-link page=$page fixpos=1}
			{formhelp note="This will reset the position numbers of all modules using increments of 5."}
		{/forminput}
	</div>

	<div class="submit">
		<input type="submit" name="update_modules" value="{tr}Apply module settings{/tr}" />
	</div>
{/form}

<h1>{tr}Modules Help{/tr}</h1>
{formhelp note="Below you can find information on what modules do and what parameters they take. If a module is not listed, the module probably doesn't take any special parameters." page="ModuleParameters"}
<noscript><div>{smartlink ititle="Expand Help" page=$page expand_all=1}</div></noscript>
{foreach from=$allModulesHelp key=package item=help}
	<h2><a href="javascript:flip('id{$package}')">{$package}</a></h2>
	<div id="id{$package}" {if !$smarty.request.expand_all}style="display:none;"{/if}>
		{foreach from=$help key=file item=title}
			{box title=$title}
				{include file=$file}
			{/box}
		{/foreach}
	</div>
{/foreach}
{/strip}
