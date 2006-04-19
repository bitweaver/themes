<div class="floaticon">{bithelp}</div>

<div class="admin themecontrol">
<div class="header">
<h1>{tr}Theme Control Center: categories{/tr}</h1>
</div>

<div class="body">

<h2>{tr}Assign themes to categories{/tr}</h2>

<div class="navbar above">
  <a href="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php">{tr}Control by Object{/tr}</a>
  <a href="{$smarty.const.THEMES_PKG_URL}theme_control_sections.php">{tr}Control by Sections{/tr}</a>
</div>

<form action="{$smarty.const.THEMES_PKG_URL}theme_control.php" method="post">
<table class="panel">
<tr>
  <th>{tr}Category{/tr}</th>
  <th>{tr}Theme{/tr}</th>
  <th>&nbsp;</th>
</tr>
<tr>
  <td>
    <select name="category_id">
      {section name=ix loop=$categories}
        <option value="{$categories[ix].category_id|escape}">{$categories[ix].name}</option>
      {sectionelse}
        <option>{tr}No records found{/tr}</option>
      {/section}
    </select>
  </td>
  <td>
    <select name="theme">
      {section name=ix loop=$styles}
        <option value="{$styles[ix]|escape}">{$styles[ix]}</option>
      {sectionelse}
        <option>{tr}No records found{/tr}</option>
      {/section}
    </select>
  </td>
  <td>
    <input type="submit" name="assigcat" value="{tr}assign{/tr}" />
  </td>
</tr>
</table>
</form> 

<h2>{tr}Assigned categories{/tr}</h2>
<form method="get" action="{$smarty.const.THEMES_PKG_URL}theme_control.php">
<table class="find">
<tr><td>{tr}Find{/tr}</td>
   <td>
     <input type="text" name="find" value="{$find|escape}" />
   </td><td>
     <input type="submit" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
   </td>
</tr>
</table>
</form>


<form action="{$smarty.const.THEMES_PKG_URL}theme_control.php" method="post">
<table class="data">
<tr>
<th>&nbsp;</th>
<th><a href="{$smarty.const.THEMES_PKG_URL}theme_control.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}">{tr}category{/tr}</a></th>
<th><a href="{$smarty.const.THEMES_PKG_URL}theme_control.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'theme_desc'}theme_asc{else}theme_desc{/if}">{tr}theme{/tr}</a></th>
</tr>
{cycle values="even,odd" print=false}
{section name=user loop=$channels}
<tr class="{cycle}">
<td><input type="checkbox" name="categ[{$channels[user].category_id}]" /></td>
<td>{$channels[user].name}</td>
<td>{$channels[user].theme}</td>
</tr>
{sectionelse}
<tr class="norecords"><td colspan="3">{tr}No records found{/tr}</td></tr>
{/section}
<tr><td colspan="3"><input type="submit" name="delete" value="{tr}Delete{/tr}" /></td></tr>
</table>
</form>

</div> {* end .body *}

<div class="pagination">
{if $prev_offset >= 0}
[<a href="{$smarty.const.THEMES_PKG_URL}theme_control.php?find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a href="{$smarty.const.THEMES_PKG_URL}theme_control.php?find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
{if $gBitSystem->isFeatureActive( 'site_direct_pagination' )}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:"$gBitSystem->getConfig('max_records')"}
<a href="{$smarty.const.THEMES_PKG_URL}theme_control.php?tasks_use_dates={$tasks_use_dates}&amp;find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>

{include file="bitpackage:themes/theme_control_help.tpl"}

</div> {* end .themecontrol *}
