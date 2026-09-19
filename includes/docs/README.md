# Themes package documentation

> Engineering documentation derived from the source in this package. The
> package's `includes/` directory must be denied to direct HTTP requests.

## Purpose

Themes manages Smarty, package template resolution, layouts, modules, styles, icons, and presentation feedback.

## Responsibility

Owns the rendering layer and the rules that resolve package and theme templates.

## Dependencies

kernel, languages, util, Smarty.

Dependency direction matters: this package may depend on the packages above;
the dependencies do not thereby depend on this package.

## Boundary

Does not own business objects, persistence, or authorization policy.

## Caching gotcha

With `BIT_CACHE_OBJECTS`, the `BitThemes` singleton is stored in APCu and keeps
site-wide CSS/JS baselines. Page-only `loadCss()` / `loadJavascript()` /
`loadAjax()` calls must pass `$pPersistent = FALSE`; see
[development.md](development.md). Site body width (`layout-body`) is a Kernel
config read from `html.tpl` — use `setRequestConfig()` for per-request fluid
layout overrides.

## Color input

`{colorinput}` (`smartyplugins/function.colorinput.php`) is the shared
compact swatch + hex text + native `<input type="color">` control
(eyedropper). Both controls are `form-control` in one `input-group` so
they share Bootstrap height. **`format` defaults to `hex`** for the text
field (`#RRGGBB`); picker changes are normalized to hex and fire
`change`. Native OS dialogs may open in RGB — that cannot be forced to
HEX. Layout CSS is in `css/base.css` (picker **2.5em**, hex **8em**).
Typical use:

```smarty
{colorinput id="bg-color" value=$bg size="sm" format="hex" title="Background"}
```

Optional params: `id`, `name`, `class`, `size` (`sm`/`lg`), `format`
(`hex`), `title`, `aria-label`, `disabled`.

## HTML id / class tokens

`|html_id` (`smartyplugins/modifier.html_id.php`) sanitizes a string for
use as an HTML `id` or class token: non `[A-Za-z0-9_-]` → `_`. Use when
building DOM hooks from catalog values (e.g. part numbers with `.` like
`11X8.5`). Do **not** apply to URL query values or displayed labels.

```smarty
{assign var=coverPreviewId value="cover-`$printerKey`-`$opId`-`$partNumber`"|html_id}
```

## Documentation map

- [Architecture](architecture.md) — initialization, components, and request flow.
- [Source reference](source-reference.md) — source-derived files, classes,
  controllers, schema artifacts, plugins, and templates.
- [Development guide](development.md) — safe change workflow, extension points,
  validation, and maintenance guidance.
- [Security](security.md) — trust boundaries and direct-HTTP access requirements.
- [Rendering, layouts, and assets](rendering-layouts-assets.md) — Smarty
  initialization, template resolution, layout/module flow, styles, and scripts.
