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
		
		$tClass = isset( $pParams['class'] ) ? ' class="'.$pParams['class'].'"' : '';
		$tStyle	= isset( $pParams['style'] ) ? ' style="'.$pParams['style'].'"' : '';
		$tClick	= isset( $pParams['onclick'] ) ? ' onclick="'.$pParams['onclick'].'"' : '';
		$tTitle	= tra( isset( $pParams['title'] ) ? $pParams['title'] : 'No Title' );

		$tabId = strtolower( isset( $pParams['id'] ) ? $pParams['id'] : 'tab'.preg_replace("/[^A-Za-z0-9]/", '', $tTitle) ); 

		$tabString = '<li '.$tClick.' '.$tClass.' '.$tStyle.'><a href="#'.$tabId.'" data-toggle="'.$type.'">' . $tTitle . '</a></li>';
		if( isset( $pParams['position'] ) ) {
			array_splice( $jsTabLinks, $pParams['position'], 0, $tabString );
		} else {
			$jsTabLinks[] = $tabString;
		}

		$tabType = BitBase::getParameter( $pParams, 'tabtype', 'tab' );

		$ret = '<div class="'.$tabType.'-pane" id="'.$tabId.'">'; 
		$ret .= $pContent;
		$ret .= '</div>';

		return $ret;
	}
}
?>
