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
	$class = 'form-group';

	if( !empty( $params['class'] ) ){
		$class .= ' '.trim( $params['class'] );
	}

	if( !empty( $params['id'] ) ){
		$attr .= 'id="'.trim( $params['id'] ).'" ';
	}

	if( !empty( $params['style'] ) ){
		$attr .= 'style="'.trim( $params['style'] ).'" ';
	}

	if( $content ) {
		$ret = '<div class="'.$class.'" '.$attr.' >'.$content.'</div>';
		return $ret;
	}
}
?>
