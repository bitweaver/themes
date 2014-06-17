{strip}
{if $packageMenuTitle}<a href="#" onclick="return(false);" tabindex="-1" class="sub-menu-root">{tr}{$smarty.const.THEMES_PKG_DIR|capitalize}{/tr}</a>{/if}
<ul class="{$packageMenuClass}">
	<li><a class="item" href="{$smarty.const.THEMES_PKG_URL}admin/admin_themes_manager.php">{tr}Change Site Theme{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=columns" >{tr}Column{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=layout" >{tr}Module Layouts{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=layout_overview" >{tr}Module Options{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=themes" >{tr}Theme Settings{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=custom_modules">{tr}Custom Modules{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.THEMES_PKG_URL}admin/menus.php">{tr}Top Menu{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.THEMES_PKG_URL}icon_browser.php">{tr}Icon Browser{/tr}</a></li>
</ul>
{/strip}
