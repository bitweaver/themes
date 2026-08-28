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

## Documentation map

- [Architecture](architecture.md) — initialization, components, and request flow.
- [Source reference](source-reference.md) — source-derived files, classes,
  controllers, schema artifacts, plugins, and templates.
- [Development guide](development.md) — safe change workflow, extension points,
  validation, and maintenance guidance.
- [Security](security.md) — trust boundaries and direct-HTTP access requirements.
- [Rendering, layouts, and assets](rendering-layouts-assets.md) — Smarty
  initialization, template resolution, layout/module flow, styles, and scripts.
