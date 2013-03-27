<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {jstab} block plugin
  *
 * Type:		block
 * Name:		jstab
 * Input:
 * Abstract:	Used to enclose a set of tabs
 */
 
function smarty_block_jstab( $pParams, $pContent, &$gBitSmarty, $pRepeat ) {
	if( empty( $pRepeat ) ){
		global $jsTabLinks;
		// if this is modified, please adjust the preg_match_all() pattern in block.jstabs.php
		$pClass = isset( $pParams['class'] ) ? ' '.$pParams['class'] : '';

		$tClass	= isset( $pParams['class'] ) ? ' '.$pParams['class'] : '';
		$tClick	= isset( $pParams['onclick'] ) ? ' onclick="'.$pParams['onclick'].'"' : '';
		$tTitle	= tra( isset( $pParams['title'] ) ? $pParams['title'] : 'No Title' );

		$tabId = strtolower( isset( $pParams['id'] ) ? $pParams['id'] : 'tab'.preg_replace("/[^A-Za-z0-9]/", '', $tTitle) ); 

		array_unshift( $jsTabLinks, '<li><a href="#'.$tabId.'" data-toggle="tab">' . htmlspecialchars( $tTitle ) . '</a></li>' );

		$ret = '<div class="tab-pane" id="'.$tabId.'">'; 
		$ret .= $pContent;
		$ret .= '</div>';

		return $ret;
	}
}
?>
