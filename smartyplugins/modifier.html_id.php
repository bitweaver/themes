<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty modifier |html_id
 *
 * Sanitize a string for use as an HTML id or class token so CSS/jQuery
 * selectors (#id / .class) stay unambiguous. Replaces anything outside
 * [A-Za-z0-9_-] with underscore. Does not change catalog/URL values —
 * only apply when building DOM ids/classes.
 *
 * @param string $pString
 * @return string
 */
function smarty_modifier_html_id( $pString ) {
	$s = preg_replace( '/[^A-Za-z0-9_-]+/', '_', (string)$pString );
	$s = trim( $s, '_' );
	if( $s === '' ) {
		return 'id';
	}
	return $s;
}
?>
