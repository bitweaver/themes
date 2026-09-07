# Developing Themes

## Start here

1. Read [architecture.md](architecture.md).
2. Locate the relevant controller, class, schema declaration, and template in
   [source-reference.md](source-reference.md).
3. Follow includes from the controller and inspect the parent classes it
   extends.
4. Confirm permissions and input validation before changing behavior.
5. Check upgrade scripts as well as the base schema for persistence changes.

## Change rules

- Preserve package boundaries described in [README.md](README.md).
- Load Bitweaver through Kernel setup; do not reproduce bootstrap logic.
- Use ADOdb and existing bind-variable patterns for database access.
- Use Liberty content APIs for content-bearing records.
- Use Users/Liberty permission APIs before reads that disclose protected data
  and before every mutation.
- Wrap user-visible strings with `tra()`.
- Keep business logic out of Smarty templates.
- Reuse registered package paths and URLs instead of hard-coded deployment
  paths.
- Treat request parameters as untrusted even when a controller is admin-only.
- `BitSmarty` string modifiers coerce null and non-scalars (arrays/objects) to
  `''` so `|strtolower` and similar cannot TypeError on probe query params.

## APCu singleton caching

`BitThemes` is an APCu-cached singleton when `BIT_CACHE_OBJECTS` is enabled.
Site-wide baseline asset lists (`mStyles`, `mAjaxLibs`, `mAuxFiles`,
`mRawFiles`, `mModules`) are intentionally serialized so every page does not
rebuild jquery/package CSS from scratch.

Page-only assets must not enter that baseline. Pass `$pPersistent = FALSE` as
the final argument to `loadCss()`, `loadJavascript()`, and `loadAjax()` from
controllers and page setup includes (for example `designer_setup_inc.php` or
bookstore admin). Those registrations go into request-only overlays that are
merged for the current response and omitted from `__sleep()`.

For Bootstrap container width, `kernel/templates/html.tpl` renders
`container{$gBitSystem->getConfig('layout-body')}`. Per-request full-width
pages must call `$gBitSystem->setRequestConfig('layout-body', '-fluid')`
rather than `setConfig()` or direct `$gBitSystem->mConfig` writes.

## Schema changes

Update both installation and upgrade paths. Define portable schema through the
installer abstraction unless the package explicitly supports only one database.
Document new tables, indexes, constraints, sequences, preferences, and cleanup
behavior.

## Testing checklist

- Exercise anonymous, authenticated, owner, editor, and administrator paths as
  applicable.
- Test missing, malformed, and unauthorized identifiers.
- Test create, load, update, list, and expunge behavior for affected objects.
- Verify templates with empty and large result sets.
- Confirm service callbacks remain safe when optional packages are absent.
- Run syntax checks and package-specific tests where present.
- Review logs without exposing credentials, tokens, or personal data.

## Documentation maintenance

Update these documents in the same package change when a public class,
controller, table, permission, service callback, configuration key, or external
integration changes. Generated-looking inventories must still be checked against
the actual source.
