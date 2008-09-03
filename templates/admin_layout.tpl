{strip}
{formfeedback hash=$feedback}

{jstabs}
	{jstab title="Layouts"}
		{form legend="Create Layout for Packages and Sections"}
			<input type="hidden" name="page" value="{$page}" />
			<div class="row">
				{formlabel label="Customized layout" for="module_package"}
				{forminput}
					<select name="module_package" id="module_package" onchange="this.form.submit();">
						{foreach key=name item=package from=$gBitSystem->mPackages}
							{if $package.installed and ($package.activatable or $package.tables)}
								<option value="{$name}" {if $module_package == $name}selected="selected"{/if}>
									{if $name eq 'kernel'}
										{tr}Site Default{/tr}
									{else}
										{tr}{$name|capitalize}{/tr}
									{/if}
								</option>
							{/if}
						{/foreach}
						<option value="home" {if $module_package == 'home'}selected="selected"{/if}>{tr}User Homepages{/tr}</option>
					</select>

					<noscript>
						{formhelp note="Apply this setting before you customise and assign modules below."}
					</noscript>
				{/forminput}
			</div>

			{if $cloneLayouts and $module_package != kernel}
				<div class="row">
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
				<div class="row submit">
					<input type="submit" name="fSubmitCustomize" value="{tr}Customize{/tr}" />
				</div>
			</noscript>
		{/form}

		<table style="width:100%" cellpadding="5" cellspacing="0" border="0">
			<caption>{tr}Current Layout of '{if !$module_package || $module_package=='kernel'}Site Default{else}{$module_package|capitalize}{/if}'{/tr}</caption>
			<tr>
				{foreach from=$layoutAreas item=area key=colkey}
					{if $colkey =='top'}
						<td class="{cycle values="even,odd"}" colspan="3" style="vertical-align:top;">
					{elseif $colkey =='bottom'}
						</tr>
						<tr>
							<td class="{cycle values="even,odd"}" colspan="3" style="vertical-align:top;">
					{else}
						<td class="{cycle values="even,odd"}" style="width:33%; vertical-align:top;">
					{/if}

						<table class="data" style="width:100%">
							<tr>
								<th>{tr}{$colkey} area{/tr}</th>
							</tr>
							{section name=ix loop=$layout.$area}
								<tr>
									<td>
										{include file="bitpackage:themes/module_config_inc.tpl" modInfo=$layout.$area[ix] condensed=1}
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


	{jstab title="Modules"}
		{form action=$smarty.server.PHP_SELF legend="Assign modules to columns and areas"}
			<input type="hidden" name="page" value="{$page}" />
			<input type="hidden" name="module_package" value="{$module_package}" />
			<div class="row">
				{formlabel label="Package"}
				{forminput}
					<span class="highlight">{tr}{if !$module_package || $module_package eq 'kernel'}Site Default{else}{$module_package|capitalize}{/if}{/tr}</span>
					{formhelp note="This is the package you are currently editing."}
				{/forminput}
			</div>

			{if $fEdit && $fAssign.name}
				<input type="hidden" name="assign_name" value="{$fAssign.name}" />
			{else}
				<div class="row">
					{formlabel label="Module" for="module_rsrc"}
					{forminput}
						{html_options name="fAssign[module_rsrc]" id="module_rsrc" options=$allModules selected=`$fAssign.name`}
						{formhelp note="Extended help can be found at the end of this page."}
					{/forminput}
				</div>
			{/if}

			<div class="row">
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

			<div class="row">
				{formlabel label="Title" for="title"}
				{forminput}
					<input type="text" size="48" name="fAssign[title]" id="title" value="{$fAssign.title|escape}" />
					{formhelp note="Here you can override the default title used by the module. This is global for layouts in all sections. If you want to add a title just for one section, enter a module parameter below such as: title=My Title"}
				{/forminput}
			</div>

			<div class="row">
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

			<div class="row">
				{formlabel label="Cache Time" for="cache_time"}
				{forminput}
					<input type="text" size="5" name="fAssign[cache_time]" id="cache_time" value="{$fAssign.cache_time|escape}" /> seconds
					{formhelp note="This is the number of seconds the module is cached before the content is refreshed. The higher the value, the less load there is on the server. (optional)"}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Rows" for="module_rows"}
				{forminput}
					<input type="text" size="5" name="fAssign[module_rows]" id="module_rows" value="{$fAssign.module_rows|escape}" />
					{formhelp note="Select what the maximum number of items are displayed. (optional - default is 10)"}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Parameters" for="params"}
				{forminput}
					<input type="text" size="48" name="fAssign[params]" id="params" value="{$fAssign.params|escape}" />
					{formhelp note="Here you can enter any additional parameters the module might need. Use the http query string form, e.g. foo=123&amp;bar=ABC (optional)"}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Groups" for="groups"}
				{forminput}
					<select multiple="multiple" size="5" name="groups[]" id="groups">
						{foreach from=$groups key=groupId item=group}
							<option value="{$groupId}" {if $group.selected eq 'y'}selected="selected"{/if}>{$group.group_name}</option>
						{/foreach}
					</select>
					{formhelp note="Select the groups of users who can see this module. If you select no group, the module will be visible to all users."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Add to all Layouts" for="add_to_all"}
				{forminput}
					<input type="checkbox" value="y" id="add_to_all" name="fAssign[add_to_all]" />
					{formhelp note="If you check this, the module will be added to all custom layouts."}
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="ColumnTabSubmit" value="{tr}Assign{/tr}" />
			</div>
		{/form}
	{/jstab}

	{jstab title="Center"}
		{form action=$smarty.server.PHP_SELF legend="Assign content to the center area"}
			<input type="hidden" name="page" value="{$page}" />
			<input type="hidden" name="module_package" value="{$module_package}" />
			<input type="hidden" name="fAssign[layout_area]" value="c" />

			<div class="row">
				{formlabel label="Package"}
				{forminput}
					<span class="highlight">{tr}{if !$module_package || $module_package eq 'kernel'}Site Default{else}{$module_package|capitalize}{/if}{/tr}</span>
					{formhelp note="This is the package you are currently editing."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Center Piece" for="module"}
				{forminput}
					{if $fEdit && $fAssign.name}
						<input type="hidden" name="fAssign[module]" value="{$fAssign.module}" id="module" />{$fAssign.module}
					{else}
						{html_options name="fAssign[module_rsrc]" id="module" values=$allCenters options=$allCenters selected=`$mod`}
					{/if}
					{formhelp note="Pick the center bit you want to display when accessing this package."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Position"}
				{forminput}
					{tr}Center{/tr}
				{/forminput}
			</div>

			<div class="row">
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

			<div class="row">
				{formlabel label="Cache Time" for="c_cache_time"}
				{forminput}
					<input type="text" name="fAssign[cache_time]" id="c_cache_time" size="5" value="{$fAssign.cache_time|escape}" /> seconds
					{formhelp note="This is the number of seconds the module is cached before the content is refreshed. The higher the value, the less load there is on the server. (optional)"}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Rows" for="c_rows"}
				{forminput}
					<input type="text" size="5" name="fAssign[module_rows]" id="c_rows" value="{$fAssign.module_rows|escape}" />
					{formhelp note="Select what the maximum number of items are displayed. (optional - default is 10)"}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Parameters" for="c_params"}
				{forminput}
					<input type="text" size="48" name="fAssign[params]" id="c_params" value="{$fAssign.params|escape}" />
					{formhelp note="Here you can enter any additional parameters the module might need. (optional)"}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Groups" for="c_groups"}
				{forminput}
					<select multiple="multiple" size="5" name="groups[]" id="c_groups">
						{foreach from=$groups key=groupId item=group}
							<option value="{$groupId}" {if $group.selected eq 'y'}selected="selected"{/if}>{$group.group_name}</option>
						{/foreach}
					</select>
					{formhelp note="Select the groups of users who can see this module. If you select no group, the module will be visible to all users."}
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="CenterTabSubmit" value="{tr}Assign{/tr}" />
			</div>
		{/form}
	{/jstab}

	{jstab title="Help"}
		{include file="bitpackage:themes/admin_layout_help.tpl"}
	{/jstab}


{/jstabs}

{/strip}
