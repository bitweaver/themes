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
 * smarty_modifier_bit_long_datetime
 */
function smarty_modifier_bit_long_datetime( $pString ) {
	global $gBitSystem;
	return smarty_modifier_bit_date_format( $pString, $gBitSystem->get_long_datetime_format(), '%A %d of %B, %Y (%H:%M:%S %Z)' );
}
?>
