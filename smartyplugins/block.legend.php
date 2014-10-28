<?php 
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {form} block plugin
 *
 * Type:     block
 * Name:     form
 * Input:
 *           - legend      (optional) - text that appears in the legend
 */
function smarty_block_legend($params, $content, &$gBitSmarty) {
	if( $content ) {
		$attributes = '';
		$attributes .= !empty( $params['class'] ) ? ' class="'.$params['class'].'" ' : '' ;
		$attributes .= !empty( $params['id'] ) ? ' id="'.$params['id'].'" ' : '' ;
		$ret = '<fieldset '.$attributes.'>';
		if( !empty( $params['legend'] ) ) {
			$ret .= '<legend>'.tra( $params['legend'] ).'</legend>';
		}
		$ret .= $content;
		$ret .= '</fieldset>';
		return $ret;
	}
}
?>
