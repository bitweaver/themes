<?php 
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {forminput} block plugin
 *
 * Type:     block
 * Name:     forminput
 */
function smarty_block_forminput($params, $content, &$gBitSmarty) {
	// defaults
	$attr = "";
	$class = 'controls';

	extract( $params );

	if( !empty( $class ) ){
		$class .= ' '.trim( $class );
	}

	if( !empty( $id ) ){
		$attr .= 'id="'.trim( $id ).'"';
	}

	if( !empty( $style ) ){
		$attr .= 'style="'.trim( $style ).'"';
	}

	if( $content ) {
		$ret = '<div class="'.$class.'" '.$attr.' >'.$content.'</div>';
		return $ret;
	}
}
?>
