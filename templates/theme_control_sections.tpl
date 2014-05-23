<div class="floaticon">{bithelp}</div>

<div class="admin themecontrol">
<div class="header">
<h1>{tr}Theme Control Center: sections{/tr}</h1>
</div>

<div class="body">

<h2>{tr}Assign themes to sections{/tr}</h2>

<div class="navbar above">
  <a href="{$smarty.const.THEMES_PKG_URL}theme_control_objects.php">{tr}Control by Object{/tr}</a>
  <a href="{$smarty.const.THEMES_PKG_URL}theme_control.php">{tr}Control by Categories{/tr}</a>
</div>

<form action="{$smarty.const.THEMES_PKG_URL}theme_control_sections.php" method="post">
<table class="panel">
<tr>
  <th>{tr}Section{/tr}</th>
  <th>{tr}Theme{/tr}</th>
  <th>&nbsp;</th>
</tr>
<tr>
  <td>
    <select name="section">
      {section name=ix loop=$sections}
        <option value="{$sections[ix]|escape}">{$sections[ix]}</option>
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
    <input type="submit" class="btn btn-default" name="assign" value="{tr}assign{/tr}" />
  </td>
</tr>
</table>
</form>

<h2>{tr}Assigned sections{/tr}</h2>
<form action="{$smarty.const.THEMES_PKG_URL}theme_control_sections.php" method="post">
<table class="table data">
<tr>
<th>&nbsp;</th>
<th><a href="{$smarty.const.THEMES_PKG_URL}theme_control_sections.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'section_desc'}section_asc{else}section_desc{/if}">{tr}section{/tr}</a></th>
<th><a href="{$smarty.const.THEMES_PKG_URL}theme_control_sections.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'theme_desc'}theme_asc{else}theme_desc{/if}">{tr}theme{/tr}</a></th>
</tr>
{cycle values="even,odd" print=false}
{section name=user loop=$channels}
<tr class="{cycle}">
<td>
<input type="checkbox" name="sec[{$channels[user].section}]" />
</td>
<td>{$channels[user].section}</td>
<td>{$channels[user].theme}</td>
</tr>
{sectionelse}
<tr class="norecords"><td colspan="3">{tr}No records found{/tr}</td></tr>
{/section}
<tr><td colspan="3"><input type="submit" class="btn btn-default" name="delete" value="{tr}Delete{/tr}" /></td></tr>
</table>
</form>

</div> {* end .body *}

{include file="bitpackage:themes/theme_control_help.tpl"}

</div> {* end .themecontrol *}
