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

Themes setup loads package `css/base.css` (utilities and `{colorinput}`)
as core CSS. Do not add a second colorinput stylesheet.

When `BIT_CACHE_OBJECTS` is enabled, baseline queues are kept in the APCu
`BitThemes` singleton. Controllers and page-only setup files must pass
`$pPersistent = FALSE` on `loadCss()` / `loadJavascript()` / `loadAjax()` so
those assets stay request-local and cannot leak to other pages on the same
worker. Package `bit_setup_inc.php` site-wide loads may keep the default
(persistent) behavior. Site body width (`layout-body`) is a Kernel config
read from `html.tpl`. Per-request fluid overrides use `setRequestConfig()`,
not `setConfig()`.

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

### Color input

`{colorinput}` (`smartyplugins/function.colorinput.php`) is the shared
compact swatch + hex text + native `<input type="color">` control
(eyedropper). Both controls are `form-control` in one `input-group` so
they share Bootstrap height. **`format` defaults to `hex`** for the text
field (`#RRGGBB`); picker changes are normalized to hex and fire
`change`. Native OS dialogs may open in RGB — that cannot be forced to
HEX. Layout CSS is in `css/base.css` (picker **2.5em**, hex **8em**),
queued from package setup as core Themes CSS — not a separate file.
Typical use:

```smarty
{colorinput id="bg-color" value=$bg size="sm" format="hex" title="Background"}
```

Optional params: `id`, `name`, `class`, `size` (`sm`/`lg`), `format`
(`hex`), `title`, `aria-label`, `disabled`.

### Byte sizes

`|display_bytes` (`smartyplugins/modifier.display_bytes.php`) turns a byte
count into a short label (`12.4 MB`). Division is by 1024; the unit list is
`B` through `YB`. The default is a `<span class="link">`. A click swaps the
label with the exact count (`number_format` plus ` bytes`); a second click
restores the short label. The click does not follow a parent link.

The second argument is the mode:

| Mode | Result |
|------|--------|
| `toggle` (default) | Short label; click shows the exact count |
| `plain` | Short label only |
| `raw` | Exact count only (`1,234 bytes`) |

A numeric second argument is decimal places and still toggles. Pass decimals
as the third argument when the mode is named (`plain:2`).

```smarty
{$someFile|filesize|display_bytes}
{$someFile|filesize|display_bytes:plain}
{$someFile|filesize|display_bytes:raw}
```

### HTML id and class tokens

`|html_id` (`smartyplugins/modifier.html_id.php`) sanitizes a string for
use as an HTML `id` or class token: non `[A-Za-z0-9_-]` → `_`. Use when
building DOM hooks from catalog values (for example a part number that
contains `.`). Do not apply it to URL query values or displayed labels.

```smarty
{assign var=coverPreviewId value="cover-`$printerKey`-`$opId`-`$partNumber`"|html_id}
```

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
