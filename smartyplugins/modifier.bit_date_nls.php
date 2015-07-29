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
function smarty_modifier_bit_date_nls( $pString ) {
	global $gBitSystem;
	return smarty_modifier_bit_date_format( $pString, '%Y-%m-%d %H:%M', '%Y%m%d %H%M' );
}
?>
