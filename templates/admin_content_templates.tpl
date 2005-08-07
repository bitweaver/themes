<div class="floaticon">{bithelp}</div>

<div class="admin templates">
	<div class="header">
		<h1>{tr}Admin templates{/tr}</h1>
	</div>

	<div class="body">
	
	{if $preview eq 'y'}
	  <div class="content">{$parsed}</div>
	{/if}
	{if $template_id > 0}
		<h2>{tr}Edit this template:{/tr} {$info.name}</h2>
		<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php">{tr}Create new template{/tr}</a>
	{else}
	<h2>{tr}Create new template{/tr}</h2>
	{/if}
		<form action="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php" method="post">
		<input type="hidden" name="template_id" value="{$template_id|escape}" />
		<div class="panel">
			<div class="row">
				{tr}name{/tr}:
				<input type="text" maxlength="255" size="40" name="name" value="{$info.name|escape}" />
			</div>
			{if $gBitSystem->isFeatureActive( 'feature_cms_templates' )}
		<div class="row">
			{tr}use in cms{/tr}:
			<input type="checkbox" name="section_cms" {if $info.section_cms eq 'y'}checked="checked"{/if} />
		</div>
	{/if}
	{if $gBitSystem->isFeatureActive( 'feature_wiki_templates' )}
		<div class="row">
			{tr}use in wiki{/tr}:
			<input type="checkbox" name="section_wiki" {if $info.section_wiki eq 'y'}checked="checked"{/if} />
		</div>
	{/if}
	{if $gBitSystem->isFeatureActive( 'feature_newsletters' )}
		<div class="row">
			{tr}use in newsletters{/tr}:
			<input type="checkbox" name="section_newsletters" {if $info.section_newsletters eq 'y'}checked="checked"{/if} />
		</div>
	{/if}
		<div class="row">
			{tr}use in HTML pages{/tr}:
			<input type="checkbox" name="section_html" {if $info.section_html eq 'y'}checked="checked"{/if} />
		</div>
		<div class="row">
			{tr}template{/tr}:
			<textarea name="content" rows="25" cols="60">{$info.content|escape}</textarea>
		</div>
		<div class="panelsubmitrow">
			<input type="submit" name="preview" value="{tr}Preview{/tr}" /><input type="submit" name="save" value="{tr}Save{/tr}" />
		</div>
	</div>
	</form>
	
	
	<h2>{tr}Templates{/tr}</h2>
	<table class="find">
	<tr><td>{tr}Find{/tr}</td>
	   <td>
	   <form method="get" action="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php">
		 <input type="text" name="find" value="{$find|escape}" />
		 <input type="submit" value="{tr}find{/tr}" name="search" />
		 <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
	   </form>
	   </td>
	</tr>
	</table>
	
	<table class="data">
	<tr>
	<th><a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}">{tr}name{/tr}</a></th>
	<th><a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'created_desc'}created_asc{else}created_desc{/if}">{tr}last modif{/tr}</a></th>
	<th>{tr}sections{/tr}</th>
	<th>{tr}action{/tr}</th>
	</tr>
	{section name=user loop=$channels}
	{if $smarty.section.user.index % 2}
	<tr class="odd">
	<td>{$channels[user].name}</td>
	<td>{$channels[user].created|bit_short_datetime}</td>
	<td>
	{section name=ix loop=$channels[user].sections}
	{$channels[user].sections[ix]} <a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?removesection={$channels[user].sections[ix]}&amp;rtemplate_id={$channels[user].template_id}" onclick="return confirmTheLink(this,'{tr}Are you sure you want to delete this template?{/tr}')" title="{tr}Click here to delete this template{/tr}">{biticon ipackage=liberty iname="delete_small" iexplain="remove"}</a> |
	{/section}
	</td>
	<td>
	   <a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].template_id}" onclick="return confirmTheLink(this,'{tr}Are you sure you want to delete this template?{/tr}')" title="{tr}Click here to delete this template{/tr}">{biticon ipackage=liberty iname="delete" iexplain="remove"}</a>
	   <a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;template_id={$channels[user].template_id}"><img class="icon" alt="{tr}Edit{/tr}" src="{$smarty.const.LIBERTY_PKG_URL}icons/edit.gif" /></a>
	</td>
	</tr>
	{else}
	<tr class="even">
	<td>{$channels[user].name}</td>
	<td>{$channels[user].created|bit_short_datetime}</td>
	<td>
	{section name=ix loop=$channels[user].sections}
	{$channels[user].sections[ix]} <a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?removesection={$channels[user].sections[ix]}&amp;rtemplate_id={$channels[user].template_id}" onclick="return confirmTheLink(this,'{tr}Are you sure you want to delete this template?{/tr}')" title="{tr}Click here to delete this template{/tr}">{biticon ipackage=liberty iname="delete_small" iexplain="remove"}</a> |
	{/section}
	</td>
	<td>
	   <a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].template_id}" onclick="return confirmTheLink(this,'{tr}Are you sure you want to delete this template?{/tr}')" title="{tr}Click here to delete this template{/tr}">{biticon ipackage=liberty iname="delete" iexplain="remove"}</a>
	   <a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;template_id={$channels[user].template_id}">{biticon ipackage=liberty iname="edit" iexplain="edit"}</a>
	</td>
	</tr>
	{/if}
	{sectionelse}
	<tr class="norecords"><td colspan="4">{tr}No records found{/tr}</td></tr>
	{/section}
	</table>
	
	</div> {* end .body *}
	
	<div class="pagination">
	{if $prev_offset >= 0}
	[<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
	{/if}
	{tr}Page{/tr}: {$actual_page}/{$cant_pages}
	{if $next_offset >= 0}
	&nbsp;[<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
	{/if}
	{if $direct_pagination eq 'y'}
	<br />
	{section loop=$cant_pages name=foo}
	{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
	<a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php?find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">
	{$smarty.section.foo.index_next}</a>&nbsp;
	{/section}
	{/if}
	</div>

</div> {* end .admin *}
