<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_required
 */
function smarty_function_required( $pParams, &$pSmarty=NULL ) {
	global $gBitSmarty;
	$gBitSmarty->loadPlugin( 'smarty_function_biticon' );
	$biticon = array(
		'ipackage' => 'icons',
		'iname'    => 'emblem-important',
		'iexplain' => 'Required',
	);
	$ret = smarty_function_biticon( $biticon );

	if( !empty( $pParams['legend'] )) {
		$ret = "<p>$ret ".tra( "Elements marked with this symbol are required." )."</p>";
	}
	return $ret;
}
?>
