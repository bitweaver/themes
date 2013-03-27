{* $Header$ *}
{strip}

{if $smarty.request.preview}
	<h2>{tr}Preview{/tr}</h2>
	<div class="preview">
		<h3>{$smarty.request.title|escape}</h3>
		{$smarty.request.data}
	</div>
{/if}

{form legend="Edit/Create module" id="editusr"}
	{formfeedback hash=$feedback}
	<input type="hidden" name="page" value="{$page}" />

	<div class="control-group">
		{formlabel label="Name" for="name"}
		{forminput}
			<input type="text" name="name" id="name" value="{$module.name|escape}" />
			{formhelp note="You will see this name show up<ul><li>when you want to assign the module</li><li>in the 'div' surrounding the module (for css customisation)</li></ul>"}
		{/forminput}
	</div>

	<div class="control-group">
		{formlabel label="Title" for="title"}
		{forminput}
			<input type="text" name="title" id="title" value="{$module.title|escape}" />
			{formhelp note="This is the name that will appear as the title of your module."}
		{/forminput}
	</div>

	<div class="control-group">
		{formlabel label="Data" for="usermoduledata"}
		{forminput}
			<textarea id="usermoduledata" name="data" rows="10" cols="50">{$module.data|escape}</textarea>
			{formhelp note="Simply insert any HTML in this textarea."}
		{/forminput}
	</div>

	<div class="control-group submit">
		<input type="submit" name="preview" value="{tr}Preview{/tr}" />
		<input type="submit" name="save" value="{tr}Save{/tr}" />
	</div>
{/form}

<table class="data">
	<caption>{tr}Custom Modules{/tr}</caption>
	<tr>
		<th>{tr}Name{/tr}</th>
		<th>{tr}Title{/tr}</th>
		<th>{tr}Action{/tr}</th>
	</tr>

	{section name=user loop=$customModules}
		<tr class="{cycle values="odd,even"}">
			<td>{$customModules[user].name|escape}</td>
			<td>{$customModules[user].title|escape}</td>
			<td class="alignright">
				<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=layout&amp;module_name=_custom%3Acustom%2F{$customModules[user].name}">{biticon ipackage="icons" iname="mail-attachment" iexplain=assign}</a>
				<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=custom_modules&amp;name={$customModules[user].name}&amp;action=edit">{biticon ipackage="icons" iname="accessories-text-editor" iexplain=edit}</a>
				<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=custom_modules&amp;name={$customModules[user].name}&amp;action=remove">{biticon ipackage="icons" iname="edit-delete" iexplain=delete}</a>
			</td>
		</tr>
	{sectionelse}
		<tr class="norecords"><td colspan="3">{tr}No records found{/tr}</td></tr>
	{/section}
</table>
{/strip}
