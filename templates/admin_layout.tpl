{strip}
{formfeedback hash=$feedback}

		{form legend="Create Layout for Packages and Sections" method="get"}
			<input type="hidden" name="page" value="{$page}" />
			<div class="form-group">
				{formlabel label="Customized layout" for="module_package"}
				{forminput}
					<select name="module_package" id="module_package" onchange="this.form.submit();">
						{foreach key=layoutName item=layoutDisplay from=$layoutList}
							<option value="{$layoutName}" {if $module_package == $layoutName}selected="selected"{/if}>
								{if $layoutName eq 'kernel'}
										{tr}Site Default{/tr}
									{else}
									{tr}{$layoutDisplay|capitalize}{/tr}
									{/if}
								</option>
						{/foreach}
						<option value="home" {if $module_package == 'home'}selected="selected"{/if}>{tr}User Homepages{/tr}</option>
					</select>

					<noscript>
						{formhelp note="Apply this setting before you customise and assign modules below."}
					</noscript>
				{/forminput}
			</div>

			{if $cloneLayouts and $module_package != kernel}
				<div class="form-group">
					{formlabel label="Copy existing layout" for="clone_layout"}
					{forminput}
						<ul>
							{foreach from=$cloneLayouts item=clone_layout key=clone_package}
								{if $clone_package != $module_package}
									<li><a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;from_layout={$clone_package}&amp;to_layout={$module_package}&amp;module_package={$module_package}">{if $clone_package == kernel}{tr}Site Default{/tr}{else}{tr}{$clone_package|capitalize}{/tr}{/if}</a></li>
								{/if}
							{/foreach}
						</ul>
						{tr}to {if $module_package == kernel}Site Default{else}{$module_package|capitalize}{/if}{/tr}
					{/forminput}
				</div>
			{/if}

			<noscript>
				<div class="form-group submit">
					<input type="submit" class="btn btn-default" name="fSubmitCustomize" value="{tr}Customize{/tr}" />
				</div>
			</noscript>
		{/form}
<div class="row">
	<div class="col-md-7">
		<table class="width100p">
			<caption>{tr}Current Layout of '{if !$module_package || $module_package=='kernel'}Site Default{else}{$module_package|capitalize}{/if}'{/tr}</caption>
			<tr>
				{foreach from=$layoutAreas item=area key=colkey}
					{if $colkey =='top'}
						<td class="{cycle values="even,odd"} aligntop" colspan="3">
					{elseif $colkey =='bottom'}
						</tr>
						<tr>
							<td class="{cycle values="even,odd"} aligntop" colspan="3">
					{else}
						<td class="{cycle values="even,odd"} width33p aligntop">
					{/if}

						<table class="table data width100p">
							<tr>
								<th>{tr}{$colkey} area{/tr}</th>
							</tr>
							{section name=ix loop=$editLayout.$area}
								<tr>
									<td>
										{include file="bitpackage:themes/module_config_inc.tpl" modInfo=$editLayout.$area[ix] condensed=1}
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
	</div>
	<div class="col-md-5">
{jstabs}
	{jstab title="Modules"}
		{form action=$smarty.server.SCRIPT_NAME legend="Assign modules to areas"}
			<input type="hidden" name="page" value="{$page}" />
			<input type="hidden" name="module_package" value="{$module_package}" />
			<div class="form-group">
				{formlabel label="Package"}
				{forminput}
					<span class="highlight">{tr}{if !$module_package || $module_package eq 'kernel'}Site Default{else}{$module_package|capitalize}{/if}{/tr}</span>
					{formhelp note="This is the package you are currently editing."}
				{/forminput}
			</div>

			{if $fEdit && $fAssign.name}
				<input type="hidden" name="assign_name" value="{$fAssign.name}" />
			{else}
				<div class="form-group">
					{formlabel label="Module" for="module_rsrc"}
					{forminput}
						{*html_options name="fAssign[module_rsrc]" id="module_rsrc" options=$allModules selected=$fAssign.name *}
						<select name="fAssign[module_rsrc]" id="module_rsrc" onchange="javascript:BitThemes.viewModuleParamsHelp( this.options[this.selectedIndex].value )">
						{foreach key=pkg item=modules from=$allModules}
							<optgroup label="{$pkg}">
								{foreach key=value item=module from=$modules}
									<option value="{$value}" {if $fAssign.name eq $value}selected="selected"{/if}>{$module.title}</option>
								{/foreach}
							</optgroup>
						{/foreach}
						</select>
						{formhelp note="Extended help can be found at the end of this page."}
					{/forminput}
				</div>
			{/if}

			<div class="form-group">
				{formlabel label="Position" for="layout_area"}
				{forminput}
					<select name="fAssign[layout_area]" id="layout_area">
						{if $gBitSystem->isFeatureActive('site_top_column')}
							<option value="t" {if $fAssign.layout_area eq 't'}selected="selected"{/if}>{tr}Top{/tr}</option>
						{/if}
						<option value="l" {if $fAssign.layout_area eq 'l'}selected="selected"{/if}>{tr}Left column{/tr}</option>
						<option value="r" {if $fAssign.layout_area eq 'r'}selected="selected"{/if}>{tr}Right column{/tr}</option>
						{if $gBitSystem->isFeatureActive('site_bottom_column')}
							<option value="b" {if $fAssign.layout_area eq 'b'}selected="selected"{/if}>{tr}Bottom{/tr}</option>
						{/if}
					</select>
					{formhelp note="Select the column this module should be displayed in."}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Title" for="title"}
				{forminput}
					<input type="text" size="48" name="fAssign[title]" id="title" value="{$fAssign.title|escape}" />
					{formhelp note="Here you can override the default title used by the module. This is global for layouts in all sections. If you want to add a title just for one section, enter a module parameter below such as: title=My Title"}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Order" for="pos"}
				{forminput}
					<select name="fAssign[pos]" id="pos">
						{section name=ix loop=$orders}
							<option value="{$orders[ix]|escape}" {if $fAssign.pos eq $orders[ix]}selected="selected"{/if}>{$orders[ix]}</option>
						{/section}
					</select>
					{formhelp note="Select where within the column the module should be displayed."}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Cache Time" for="cache_time"}
				{forminput}
					<input type="text" size="5" name="fAssign[cache_time]" id="cache_time" value="{$fAssign.cache_time|escape}" /> seconds
					{formhelp note="This is the number of seconds the module is cached before the content is refreshed. The higher the value, the less load there is on the server. (optional)"}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Rows" for="module_rows"}
				{forminput}
					<input type="text" size="5" name="fAssign[module_rows]" id="module_rows" value="{$fAssign.module_rows|escape}" />
					{formhelp note="Select what the maximum number of items are displayed. (optional - default is 10)"}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Parameters" for="params"}
				{forminput}
					<input type="text" size="48" name="fAssign[params]" id="params" value="{$fAssign.params|escape}" />
					{formhelp note="Here you can enter any additional parameters the module might need. Use the http query string form, e.g. foo=123&amp;bar=ABC (optional)"}
					{foreach key=pkg item=modules from=$allModules}
						{foreach key=value item=module from=$modules}
						{if $module.params}
							<table id="themes_params_help_{$value}" class="themes_params_help" style="display:none">
								<tr>
									<th colspan=2 style="text-align:left">Options for {$module.title}</th>
								</tr>
								{foreach key=param item=data from=$module.params}
								{if $data.help}
									<tr>
										<td style="font-weight:bold; padding-right:4px">{$param}</td>
										<td>{$data.help}</td>
									</tr>
								{/if}
								{/foreach}
							</table>
						{/if}
						{/foreach}
					{/foreach}
				{/forminput}
			</div>

			<div class="form-group">
				{if $roles }
					{formlabel label="Roles" for="roles"}
					{forminput}
						<select multiple="multiple" size="5" name="roles[]" id="roles">
							{foreach from=$roles key=roleId item=role}
								<option value="{$roleId}" {if $role.selected eq 'y'}selected="selected"{/if}>{$role.role_name}</option>
							{/foreach}
						</select>
						{formhelp note="Select the roles of users who can see this module. If you select no role, the module will be visible to all users."}
					{/forminput}
				{else}
					{formlabel label="Groups" for="groups"}
					{forminput}
						<select multiple="multiple" size="5" name="groups[]" id="groups">
							{foreach from=$groups key=groupId item=group}
								<option value="{$groupId}" {if $group.selected eq 'y'}selected="selected"{/if}>{$group.group_name}</option>
							{/foreach}
						</select>
						{formhelp note="Select the groups of users who can see this module. If you select no group, the module will be visible to all users."}
					{/forminput}
				{/if}
			</div>

			<div class="form-group">
				<label class="checkbox">
					<input type="checkbox" value="y" id="add_to_all" name="fAssign[add_to_all]" />Add to all Layouts
					{formhelp note="If you check this, the module will be added to all custom layouts."}
				</label>
			</div>

			<div class="form-group submit">
				<input type="submit" class="btn btn-default" name="ColumnTabSubmit" value="{tr}Assign{/tr}" />
			</div>
		{/form}
	{/jstab}

	{jstab title="Center"}
		{form action=$smarty.server.SCRIPT_NAME legend="Assign content to the center area"}
			<input type="hidden" name="page" value="{$page}" />
			<input type="hidden" name="module_package" value="{$module_package}" />
			<input type="hidden" name="fAssign[layout_area]" value="c" />

			<div class="form-group">
				{formlabel label="Package"}
				{forminput}
					<span class="highlight">{tr}{if !$module_package || $module_package eq 'kernel'}Site Default{else}{$module_package|capitalize}{/if}{/tr}</span>
					{formhelp note="This is the package you are currently editing."}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Center Piece" for="module"}
				{forminput}
					{if $fEdit && $fAssign.name}
						<input type="hidden" name="fAssign[module]" value="{$fAssign.module}" id="module" />{$fAssign.module}
					{else}
						{* html_options name="fAssign[module_rsrc]" id="module" values=$allCenters options=$allCenters selected=$mod *}
						<select name="fAssign[module_rsrc]" id="module" {*onchange="javascript:BitThemes.viewModuleParamsHelp( this.options[this.selectedIndex].value )"*}>
						{foreach key=pkg item=modules from=$allCenters}
							<optgroup label="{$pkg}">
								{foreach key=value item=module from=$modules}
									<option value="{$value}" {if $mod eq $value}selected="selected"{/if}>{$module.title}</option>
								{/foreach}
							</optgroup>
						{/foreach}
						</select>
					{/if}
					{formhelp note="Pick the center bit you want to display when accessing this package."}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Position"}
				{forminput}
					{tr}Center{/tr}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Order" for="c_ord"}
				{forminput}
					<select name="fAssign[pos]" id="c_ord">
						{section name=ix loop=$orders}
							<option value="{$orders[ix]|escape}" {if $assign_order eq $orders[ix]}selected="selected"{/if}>{$orders[ix]}</option>
						{/section}
					</select>
					{formhelp note="Select where within the column the module should be displayed."}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Cache Time" for="c_cache_time"}
				{forminput}
					<input type="text" name="fAssign[cache_time]" id="c_cache_time" size="5" value="{$fAssign.cache_time|escape}" /> seconds
					{formhelp note="This is the number of seconds the module is cached before the content is refreshed. The higher the value, the less load there is on the server. (optional)"}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Rows" for="c_rows"}
				{forminput}
					<input type="text" size="5" name="fAssign[module_rows]" id="c_rows" value="{$fAssign.module_rows|escape}" />
					{formhelp note="Select what the maximum number of items are displayed. (optional - default is 10)"}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Parameters" for="c_params"}
				{forminput}
					<input type="text" size="48" name="fAssign[params]" id="c_params" value="{$fAssign.params|escape}" />
					{formhelp note="Here you can enter any additional parameters the module might need. (optional)"}
				{/forminput}
			</div>

			<div class="form-group">
				{if $roles }
					{formlabel label="Roles" for="c_roles"}
					{forminput}
						<select multiple="multiple" size="5" name="roles[]" id="c_roles">
							{foreach from=$roles key=roleId item=role}
								<option value="{$roleId}" {if $role.selected eq 'y'}selected="selected"{/if}>{$role.role_name}</option>
							{/foreach}
						</select>
						{formhelp note="Select the roles of users who can see this module. If you select no role, the module will be visible to all users."}
					{/forminput}
				{else}
					{formlabel label="Groups" for="c_groups"}
					{forminput}
						<select multiple="multiple" size="5" name="groups[]" id="c_groups">
							{foreach from=$groups key=groupId item=group}
								<option value="{$groupId}" {if $group.selected eq 'y'}selected="selected"{/if}>{$group.group_name}</option>
							{/foreach}
						</select>
						{formhelp note="Select the groups of users who can see this module. If you select no group, the module will be visible to all users."}
					{/forminput}
				{/if}
			</div>

			<div class="form-group submit">
				<input type="submit" class="btn btn-default" name="CenterTabSubmit" value="{tr}Assign{/tr}" />
			</div>
		{/form}
	{/jstab}

	{include file="bitpackage:themes/admin_layout_inc.tpl"}
{/jstabs}
	</div>
</div>

{/strip}
