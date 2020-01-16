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
$gBitSmarty->loadPlugin( 'smarty_modifier_bit_date_format' );

/**
 * smarty_modifier_bit_long_date
 */
function smarty_modifier_bit_long_date( $pString ) {
	global $gBitSystem;
	return smarty_modifier_bit_date_format( $pString, $gBitSystem->get_long_date_format(), '%A, %B %d, %Y' );
}
?>
