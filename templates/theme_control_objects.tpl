<div class="floaticon">{bithelp}</div>

<div class="admin themecontrol">
<div class="header">
<h1>{tr}Theme Control Center: Objects{/tr}</h1>
</div>

<div class="body">

<h2>{tr}Assign themes to objects{/tr}</h2>

<div class="navbar above">
  <a href="{$smarty.const.THEMES_PKG_URL}theme_control.php">{tr}Control by category{/tr}</a>
  <a href="{$smarty.const.THEMES_PKG_URL}theme_control_sections.php">{tr}Control by Sections{/tr}</a>
</div>

<form id="objform" action="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php" method="post">
<!--<input type="submit" name="settype" value="{tr}set{/tr}" />-->
<table class="panel">
<tr>
  <th>{tr}Section{/tr}</th>
  <th>{tr}Object{/tr}</th>
  <th>{tr}Theme{/tr}</th>
  <th>&nbsp;</th>
</tr>
<tr>
  <td>
    <select name="type" onchange="javascript:document.getElementById('objform').submit();">
    {section name=ix loop=$types}
      <option value="{$types[ix]|escape}" {if $type eq $types[ix]}selected="selected"{/if}>{$types[ix]}</option>
      {sectionelse}
        <option>{tr}No records found{/tr}</option>
    {/section}
    </select>
  </td>
  <td>
    <select name="objdata">
      {section name=ix loop=$objects}
      <option value="{$objects[ix].obj_id|escape}|{$objects[ix].objName}">{$objects[ix].objName}</option>
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
    <input type="submit" name="assign" value="{tr}assign{/tr}" />
  </td>
</tr>
</table>
</form> 

<h2>{tr}Assigned objects{/tr}</h2>
<table class="find">
<tr><td>{tr}Find{/tr}</td>
   <td>
   <form method="get" action="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php">
     <input type="text" name="find" value="{$find|escape}" />
     <input type="submit" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
   </form>
   </td>
</tr>
</table>
<form action="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php" method="post">
<input type="hidden" name="type" value="{$type|escape}" />


<table class="data">
<tr>
<th>&nbsp;</th>
<th><a href="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'type_desc'}type_asc{else}type_desc{/if}">{tr}type{/tr}</a></th>
<th><a href="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}">{tr}name{/tr}</a></th>
<th><a href="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'theme_desc'}theme_asc{else}theme_desc{/if}">{tr}theme{/tr}</a></th>
</tr>
{cycle values="even,odd" print=false}
{section name=user loop=$channels}
<tr class="{cycle}">
<td>
<input type="checkbox" name="obj[{$channels[user].obj_id}]" />
</td>
<td>{$channels[user].type}</td>
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
[<a href="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php?find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a href="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php?find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
{if $gBitSystem->isFeatureActive( 'direct_pagination' )}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:"$gBitSystem->getConfig('max_recor')"}
<a href="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php?tasks_use_dates={$tasks_use_dates}&amp;find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div> 

{include file="bitpackage:themes/theme_control_help.tpl"}

</div> {* end .themecontrol *}
