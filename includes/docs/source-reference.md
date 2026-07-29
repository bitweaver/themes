# Themes source reference

> Generated from the current checkout and then intended for human review.
> Paths are relative to the package root.

## Inventory summary

| Artifact | Count |
|---|---:|
| PHP files | 122 |
| Smarty templates | 25 |
| JavaScript files | 0 |
| CSS files | 50 |

## Bootstrap and schema artifacts

- `admin/schema_inc.php`
- `admin/upgrade_inc.php`
- `admin/upgrades/2.0.0.php`
- `includes/bit_setup_inc.php`

## First-party classes and interfaces

- `css_lib.php:14` — `class cssLib extends BitBase {`
- `includes/classes/BitFeedback.php:15` — `class BitFeedback {`
- `includes/classes/BitSmarty.php:25` — `class PermissionCheck {`
- `includes/classes/BitSmarty.php:38` — `class BitSmarty extends Smarty {`
- `includes/classes/BitThemes.php:16` — `class BitThemes extends BitSingleton {`
- `smartyplugins/resource._custom.php:14` — `class Smarty_Resource__Custom extends Smarty_Resource_Custom {`
- `smartyplugins/resource.bitpackage.php:14` — `class Smarty_Resource_Bitpackage extends Smarty_Resource_Custom {`

## Web-facing PHP controllers

- `admin/admin_columns_inc.php`
- `admin/admin_custom_modules_inc.php`
- `admin/admin_layout_inc.php`
- `admin/admin_layout_overview_inc.php`
- `admin/admin_modules_inc.php`
- `admin/admin_themes_manager.php`
- `admin/index.php`
- `admin/menus.php`
- `admin/schema_inc.php`
- `admin/upgrade_inc.php`
- `admin/upgrades/2.0.0.php`
- `css_lib.php`
- `edit_css.php`
- `icon_browser.php`
- `index.php`
- `modules/index.php`
- `modules/mod_switch_theme.php`
- `switch_theme.php`

## Declared schema tables

- `themes_custom_modules`
- `themes_layouts`

## Plugin and module directories

- `modules/`
- `smartyplugins/`

## Templates

- `modules/help_mod_switch_theme.tpl`
- `modules/mod_switch_theme.tpl`
- `templates/admin_columns.tpl`
- `templates/admin_custom_modules.tpl`
- `templates/admin_layout.tpl`
- `templates/admin_layout_inc.tpl`
- `templates/admin_layout_overview.tpl`
- `templates/admin_modules.tpl`
- `templates/admin_themes.tpl`
- `templates/admin_themes_manager.tpl`
- `templates/admin_themes_menus.tpl`
- `templates/custom_module.tpl`
- `templates/footer_inc.tpl`
- `templates/html_head_inc.tpl`
- `templates/icon_browser.tpl`
- `templates/menu_themes_admin.tpl`
- `templates/module.tpl`
- `templates/module_config_inc.tpl`
- `templates/module_config_role_inc.tpl`
- `templates/null.tpl`
- `templates/popup_footer_inc.tpl`
- `templates/popup_header_inc.tpl`
- `templates/theme_control.tpl`
- `templates/theme_control_objects.tpl`
- `templates/theme_control_sections.tpl`

## Reading cautions

- Presence in this inventory does not make a file a supported public API.
- Bundled third-party libraries must be distinguished from package-owned code.
- Base schema files do not prove the migration state of a deployed database.
- Controllers may rely on include files, globals, services, and template callbacks not visible from their filename alone.
