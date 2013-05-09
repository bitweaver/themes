<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * required setup
 */
global $gBitSmarty;
$gBitSmarty->loadPlugin('smarty_modifier_bit_date_format');

/**
 * smarty_modifier_bit_short_date
 */
function smarty_modifier_bit_short_date( $pString ) {
	global $gBitSystem;
	return smarty_modifier_bit_date_format( $pString, $gBitSystem->get_short_date_format(), '%d %b %Y' );
}
?>
