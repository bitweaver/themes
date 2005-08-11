<div class="floaticon">{bithelp}</div>

<div class="admin templates">
	<div class="header">
		<h1>{tr}Admin templates{/tr}</h1>
	</div>

	<div class="body">
		{if $preview eq 'y'}
			<div class="preview">{$parsed}</div>
		{/if}

		{form legend="Create / Edit Templates"}
			<input type="hidden" name="template_id" value="{$template_id}" />

			<div class="row">
				{formlabel label="Title" for="name"}
				{forminput}
					<input type="text" maxlength="255" size="40" name="name" id="name" value="{$info.name|escape}" />
					{formhelp note="This is the title of the template that will identify it when creating a wiki page."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Use In" for=""}
				{forminput}
					{if $gBitSystem->isFeatureActive( 'feature_cms_templates' )}
						<label><input type="checkbox" name="section_cms" {if $info.section_cms eq 'y'}checked="checked"{/if} /> {tr}Articles{/tr}</label><br />
					{/if}

					{if $gBitSystem->isFeatureActive( 'feature_wiki_templates' )}
						<label><input type="checkbox" name="section_wiki" {if $info.section_wiki eq 'y'}checked="checked"{/if} /> {tr}Wiki{/tr}</label><br />
					{/if}

					{if $gBitSystem->isFeatureActive( 'feature_newsletters' )}
						<label><input type="checkbox" name="section_newsletters" {if $info.section_newsletters eq 'y'}checked="checked"{/if} /> {tr}Newsletters{/tr}</label><br />
					{/if}

					<label><input type="checkbox" name="section_html" {if $info.section_html eq 'y'}checked="checked"{/if} /> {tr}HTML Pages{/tr}</label>
					{formhelp note="Select what packages the content templates can be used in."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Template" for="content"}
				{forminput}
					<textarea name="content" id="content" rows="25" cols="60">{$info.content|escape}</textarea>
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="preview" value="{tr}Preview{/tr}" /> <input type="submit" name="save" value="{tr}Save{/tr}" />
				{if $template_id > 0}
					<br /><a href="{$smarty.const.THEMES_PKG_URL}admin/admin_content_templates.php">{tr}Create new template{/tr}</a>
				{/if}
			</div>
		{/form}

		{minifind}

		<table class="data">
			<caption>{tr}Templates{/tr}</caption>
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

		{pagination}
	</div><!-- end .body -->
</div><!-- end .admin -->
