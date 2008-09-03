{legend legend="Layout help"}
	{if $page eq "layout_overview"}
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
				{formhelp note="Reset the position numbers of <em>all modules</em> using increments of 5."}
			{/forminput}
		</div>
	{else}
		<dl>
			<dt>
				{smartlink ititle="Adjust module positions" page=$page fixpos=1 module_package=$module_package}
				{formhelp note="This will reset the position numbers of all modules using increments of 5."}
			</dt>
			<dt>
				{smartlink ititle="Configure Layout Details" page=layout_overview}
				{formhelp note="On this page you can configure all modules in all layouts."}
			</dt>
		</dl>
	{/if}
        
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