# Themes rendering, layouts, and assets

## Runtime objects

Themes setup loads `BitSmarty` and `BitThemes`, verifies the Smarty compile
directory, selects the current style, assigns `$gBitThemes`, and loads baseline
JavaScript/AJAX support.

`BitSmarty` extends Smarty and implements Bitweaver resource/plugin behavior.
Registered PHP string modifiers coerce null and non-scalars to an empty string
before calling the native function.
`BitThemes` owns styles, layouts, modules, asset queues, display mode, and
response format.

## Template resolution

Use the `bitpackage:` resource:

```text
bitpackage:<package>/<template>.tpl
```

Resolution allows an active theme to override a package template while falling
back to the package's own `templates/` directory. This preserves pristine
package templates and keeps branding/presentation overrides in themes.

Install-wide overlays live at `config/themes/<package>/<template>` (for
example `config/themes/kernel/html.tpl`). Those apply to every style in this
checkout, after `config/themes/force/` and before a per-style copy under
`config/themes/<stylename>/<package>/`.

Never build a template filesystem path from request input. Pass a known
resource name to Smarty.

## Display flow

Kernel `BitSystem::display()` and Themes cooperate:

1. The controller assigns domain state and names a center template.
2. `BitSystem::preDisplay()` asks Themes to load layout/style state.
3. Themes resolves columns/modules and queued assets.
4. The center template is assigned as `mid`.
5. Kernel's outer HTML template renders the complete page.
6. Alternate formats can bypass the outer HTML shell.

Templates render state; controllers and domain classes perform validation,
authorization, and mutation.

## Layouts and modules

Layouts determine page regions and module placement. Modules pair a PHP loader
with a Smarty template. The loader must:

- Run within normal setup.
- Check permissions before assigning protected data.
- Avoid expensive work when the module is not displayed.
- Namespace variables when collision is possible.
- Treat module parameters as untrusted configuration/input.

Module placement/order is presentation state. Package code should register
menus/modules through framework APIs rather than editing a theme layout.

## Styles and icons

`BitThemes::setStyle()` selects the active style. Preload and final load phases
collect style resources and overrides. Icons resolve through icon-set/theme
conventions; packages should use icon APIs/templates instead of fixed image
paths.

Theme overrides can mask package template changes. Test both the package
fallback template and representative overriding themes.

## JavaScript and CSS

Queue assets through `BitThemes::loadJavascript()` and related APIs. Ordering
arguments are part of dependency behavior. Avoid duplicate direct `<script>`
tags in templates.

When `BIT_CACHE_OBJECTS` is enabled, baseline queues are kept in the APCu
`BitThemes` singleton. Controllers and page-only setup files must pass
`$pPersistent = FALSE` on `loadCss()` / `loadJavascript()` / `loadAjax()` so
those assets stay request-local and cannot leak to other pages on the same
worker. Package `bit_setup_inc.php` site-wide loads may keep the default
(persistent) behavior.

Pass server data using established encoded bootstrap/assignment patterns.
Never concatenate unescaped user data into executable JavaScript.

## Smarty extensions

Bitweaver supplies Smarty resource handlers and registers selected PHP classes,
functions, modifiers, blocks, and compiler hooks. `BitBase::registerForSmarty()`
is the canonical class-registration helper in this checkout.

When adding a template-callable API:

- Expose the narrowest method set possible.
- Keep authorization in PHP domain/controller code.
- Register explicitly for current Smarty versions.
- Test missing/null values under strict PHP behavior.
- Do not rely on arbitrary PHP function access.

## Response formats

Themes tracks format headers such as HTML, JSON, and text plus display modes.
Use Kernel output helpers for non-HTML responses so status and content handling
remain consistent. A JSON endpoint should not render an HTML error template.

## Caches and compiled templates

Smarty compile/cache directories are runtime data. Template or plugin changes
may require cache invalidation. Never commit generated compiled templates or
make documentation depend on their contents.

## Files that change together

- Template contract: controller assignments, package template, theme overrides.
- New module: PHP loader, module template, registration/admin metadata.
- New Smarty-callable class: class method and explicit registration.
- New asset: loader call, dependency order, CSP/security review, template use.
