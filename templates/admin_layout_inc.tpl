{jstab title="Actions"}
	{legend legend="Layout help"}
		{if $page eq "layout_overview"}
			<div class="form-group">
				{formlabel label="Adjust display" for=""}
				{forminput}
					{if !$smarty.request.nocollapse}
						<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;nocollapse=1">{booticon iname="icon-plus-sign"   iforce=icon_text iexplain="Expand all modules"}</a>
					{else}
						<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}">{booticon iname="icon-minus-sign"   iforce=icon_text iexplain="Collapse all modules"}</a>
					{/if}
					{formhelp note="Toggle the state of <em>all modules</em> (expanded/collapsed). This reloads the page without saving changes made prior."}
				{/forminput}
			</div>
			<div class="form-group">
				{formlabel label="Adjust modules" for=""}
				{forminput}
					<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page={$page}&amp;fixpos=1">{booticon iname="icon-circle-arrow-right"   iforce=icon_text iexplain="Adjust module postitions"}</a>
					{formhelp note="Reset the position numbers of <em>all modules</em> using increments of 5."}
				{/forminput}
			</div>
		{else}
			<div class="form-group">
				{formlabel label="Module Positions" for=""}
				{forminput}
					{smartlink ititle="Adjust module positions" page=$page fixpos=1 module_package=$module_package}
					{formhelp note="This will reset the position numbers of all modules using increments of 5."}
				{/forminput}
			</div>

			<div class="form-group">
				{formlabel label="Layout Details" for=""}
				{forminput}
					{smartlink ititle="Configure Layout Details" page=layout_overview}
					{formhelp note="On this page you can configure all modules in all layouts."}
				{/forminput}
			</div>
		{/if}
	{/legend}
{/jstab}

{jstab title="Help"}
	{legend legend="Modules Help"}
		{formhelp note="List of available modules and their parameters. If a module is not listed, it might not take any parameters." page="ModuleParameters"}
		<noscript><div>{smartlink ititle="Expand Help" page=$page expand_all=1}</div></noscript>
		{foreach from=$allModulesHelp key=package item=help}
			<h2><a href="javascript:BitBase.toggleElementDisplay('id{$package}','block')">{$package}</a></h2>
			<div class="modulehelp" id="id{$package}" {if !$smarty.request.expand_all}style="display:none;"{/if}>
				{foreach from=$help key=helpFile item=module}
					<h3>{$module.title|capitalize}</h3>
					{include file=$helpFile}
				{/foreach}
				<hr />
			</div>
		{/foreach}
	{/legend}
{/jstab}
