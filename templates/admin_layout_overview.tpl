{strip}
{formfeedback hash=$feedback}

{form}
	<input type="hidden" name="page" value="{$page}" />
	<input type="hidden" name="nocollapse" value="{$smarty.request.nocollapse}" />

	{jstabs}
	
		{* if $smarty.foreach.PkgLayouts.iteration gt 1}
			{jstab title=Layouts:}
				{legend legend=Caption}
					<dl>
						{foreach name=PkgLayoutCaption from=$layouts item=layout key=module_package}
							<dt>{$smarty.foreach.PkgLayoutCaption.iteration}</dt>
							<dd>{$module_package|capitalize}</dd>
						{/foreach}
					</dl>
				{/legend}
			{/jstab}
		{/if *}
	
		{foreach name=PkgLayouts from=$layouts item=layout key=module_package}

			{* save layout's name in $PkgLayoutTitle *}

			{if !$module_package || $module_package == 'kernel'}
				{assign var=PkgLayoutTitle value="Default"}
			{else}
				{assign var=PkgLayoutTitle value=$module_package|capitalize}
			{/if}

			{* save full tab title OR truncated tab title OR number to $TabTitle *}
			
			{if $smarty.foreach.PkgLayouts.total lt 5}
				{assign var=TabTitle value=$PkgLayoutTitle}
			{elseif $smarty.foreach.PkgLayouts.total gt 4 and $smarty.foreach.PkgLayouts.total lt 8} {* ... *}
				{assign var=TabTitle value=$PkgLayoutTitle|truncate:7:".":true}
			{else}
				{assign var=TabTitle value="&nbsp;"|cat:$smarty.foreach.PkgLayouts.iteration|cat:"&nbsp;"}
			{/if}
			
			{jstab title=$TabTitle}

				<h1 id="{$module_package}">
					{tr}Current Layout of {if $PkgLayoutTitle == 'Default'}Site Default{else}{$PkgLayoutTitle}{/if}{/tr}
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
			{/jstab}
		{/foreach}

		{jstab title="Help"}
			{legend legend="Layout overview help"}
				<div class="row">
					{formlabel label="Adjust display" for=""}
					{forminput}
						{if !$smarty.request.nocollapse}
							<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;nocollapse=1">{biticon iname="list-add" iforce=icon_text iexplain="Expand all modules"}</a>
						{else}
							<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}">{biticon iname="list-remove" iforce=icon_text iexplain="Collapse all modules"}</a>
						{/if}
						{formhelp note="Toggle the state of <em>all modules</em> (expanded/collapsed). This reloads the page without saving changes made prior."}
					{/forminput}
				</div>
				<div class="row">
					{formlabel label="Adjust modules" for=""}
					{forminput}
						<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;fixpos=1">{biticon iname="emblem-symbolic-link" iforce=icon_text iexplain="Adjust module postitions"}</a>
						{* smartlink ititle="Adjust modules" ibiticon=icons/emblem-symbolic-link page=$page fixpos=1 *}
						{formhelp note="Reset the position numbers of <em>all modules</em> using increments of 5."}
					{/forminput}
				</div>
			{/legend}
			{legend legend="Modules Help"}
				{formhelp note="List of what modules do and what parameters they take. If a module is not listed, the module probably doesn't take any special parameters." page="ModuleParameters"}
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
			{/legend}
		{/jstab}

	{/jstabs}
	<div class="submit">
		<input type="submit" name="update_modules" value="{tr}Apply module settings{/tr}" />
	</div>
{/form}

{/strip}