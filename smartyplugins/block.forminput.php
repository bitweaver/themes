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
	global $gSmartyFormHorizontal;

	// defaults
	$attr = "";
	$class = '';
	if( $gSmartyFormHorizontal ) {
		$class = 'col-sm-8';
	}

	if( !empty( $params['class'] ) ){
		$class .= ' '.trim( $params['class'] );
		if( $gSmartyFormHorizontal && (strpos( $params['class'], 'submit' ) !== FALSE || strpos( $params['class'], 'offset' ) !== FALSE) ) {
			$class .= ' col-sm-offset-4';
		}
	}

	$labelStart = '';
	$labelEnd = '';

	if( !empty( $params['label'] ) ){
		if( $gSmartyFormHorizontal ) {
			$class .= ' col-sm-offset-4';
		}
		$class .= ' '.trim( $params['label'] );
		$labelStart = '<label>';
		$labelEnd = '</label>';
	}

	if( !empty( $params['id'] ) ){
		$attr .= 'id="'.trim( $params['id'] ).'" ';
	}

	if( !empty( $params['style'] ) ){
		$attr .= 'style="'.trim( $params['style'] ).'" ';
	}

	if( $content ) {
		return '<div class="form-group '.$class.'" '.$attr.' >'.$labelStart.$content.$labelEnd.'</div>';
	}
}
?>
