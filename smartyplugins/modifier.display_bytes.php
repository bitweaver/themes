<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     display_bytes
 * Purpose:  show an integer in a human readable Byte size with optional resolution
 * Example:  {$someFile|filesize|display_bytes:2}
 *           {$someFile|filesize|display_bytes:1:1}
 *           The third argument (1) emits a span. Click toggles the exact byte count.
 * -------------------------------------------------------------
 */
function smarty_modifier_display_bytes( $pSize, $pDecimalPlaces = 1, $pToggleRaw = false ) {
	$raw = $pSize;
	$i = 0;
	$iec = array( "B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB" );
	while( ( $pSize / 1024 ) > 1 ) {
		$pSize = $pSize / 1024;
		$i++;
	}
	$human = round( $pSize, $pDecimalPlaces )." ".$iec[$i];
	if( empty( $pToggleRaw ) || $pToggleRaw === '0' || $pToggleRaw === 'false' ) {
		return $human;
	}
	$rawLabel = number_format( (float)$raw ).' bytes';
	$humanAttr = htmlspecialchars( $human, ENT_QUOTES, 'UTF-8' );
	$rawAttr = htmlspecialchars( $rawLabel, ENT_QUOTES, 'UTF-8' );
	return '<span class="link" data-human="'.$humanAttr.'" data-raw="'.$rawAttr.'" onclick="this.textContent=(this.textContent==this.getAttribute(\'data-raw\')?this.getAttribute(\'data-human\'):this.getAttribute(\'data-raw\'))">'.$humanAttr.'</span>';
}
?>
