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
 * Purpose:  show an integer in a human readable Byte size
 *           Mode is the second argument: toggle (default), plain, or raw.
 *           A numeric second argument is decimal places and keeps toggle.
 * Example:  {$someFile|filesize|display_bytes}
 *           {$someFile|filesize|display_bytes:plain}
 *           {$someFile|filesize|display_bytes:raw}
 *           {$someFile|filesize|display_bytes:plain:2}
 * -------------------------------------------------------------
 */
function smarty_modifier_display_bytes( $pSize, $pMode = 'toggle', $pDecimalPlaces = 1 ) {
	if( is_numeric( $pMode ) ) {
		$pDecimalPlaces = $pMode;
		$pMode = 'toggle';
	}
	$pMode = strtolower( trim( (string)$pMode ) );
	if( $pMode !== 'plain' && $pMode !== 'raw' ) {
		$pMode = 'toggle';
	}
	$raw = $pSize;
	$i = 0;
	$iec = array( "B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB" );
	while( ( $pSize / 1024 ) > 1 ) {
		$pSize = $pSize / 1024;
		$i++;
	}
	$human = round( $pSize, $pDecimalPlaces )." ".$iec[$i];
	$rawLabel = number_format( (float)$raw ).' bytes';
	if( $pMode === 'raw' ) {
		return $rawLabel;
	}
	if( $pMode === 'plain' ) {
		return $human;
	}
	$humanAttr = htmlspecialchars( $human, ENT_QUOTES, 'UTF-8' );
	$rawAttr = htmlspecialchars( $rawLabel, ENT_QUOTES, 'UTF-8' );
	return '<span class="link" data-human="'.$humanAttr.'" data-raw="'.$rawAttr.'" onclick="event.stopPropagation();this.textContent=(this.textContent==this.getAttribute(\'data-raw\')?this.getAttribute(\'data-human\'):this.getAttribute(\'data-raw\'));return false;">'.$humanAttr.'</span>';
}
?>
