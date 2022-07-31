<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * @link http://www.bitweaver.org/wiki/function_booticon function_booticon
 */

/**
 * Turn collected information into an html image
 *
 * @param boolean $pParams['url'] set to TRUE if you only want the url and nothing else
 * @param string $pParams['iexplain'] Explanation of what the icon represents
 * @param string $pParams['iforce'] takes following optins: icon, icon_text, text - will override system settings
 * @param string $pFile Path to icon file
 * @param string iforce  override site-wide setting how to display icons (can be set to 'icon', 'text' or 'icon_text')
 * @access public
 * @return Full <img> on success
 */
function smarty_function_booticon( $pParams ) {
	global $gBitSystem;

	if( empty( $pParams['iforce'] )) {
		$pParams['iforce'] = NULL;
	}

	$outstr = '';
	if( isset( $pParams['href'] ) ) {
		$outstr .= '<a href="'.$pParams['href'].'" class="icon"';
		if( isset( $pParams['iexplain'] ) ) {
			$outstr .= ' title="'.htmlentities( $pParams['iexplain'] ).'"';
		}
		$outstr .= '>';
	}

	$outstr .= '<span class="fa ';
	if( strpos( $pParams["iname"], 'icon-' ) === 0 ) {
		$pParams['iname'] = str_replace( 'icon-', 'fa-', $pParams['iname'] );
	}
if( strpos( $pParams["iname"], 'fa-' ) !== 0 ) {
	bit_error_log( 'missing fa '.$pParams["iname"] );
}

	$outstr .= str_replace( 'icon-', '', $pParams['iname'] );
	
	if( isset( $pParams["iclass"] ) ) {
		$outstr .=  ' '.$pParams["iclass"].'';
	}
	if( isset( $pParams["class"] ) ) {
		$outstr .=  ' '.$pParams["class"].'';
	}
	if( isset( $pParams["igroup"] ) ) {
		$outstr .=  ' '.$pParams["igroup"].'';
	}
	$outstr .= '"';

	if( isset( $pParams["style"] ) ) {
		$outstr .=  ' style="'.$pParams["style"].'"';
	}

	if( isset( $pParams["id"] ) ) {
		$outstr .=  ' id="'.$pParams['id'].'"';
	}

	if( isset( $pParams['iexplain'] ) ) {
		$outstr .= ' title="'.htmlentities( $pParams['iexplain'] ).'"';
	}

	foreach( array_keys( $pParams ) as $key ) {
		if( strpos( $key, 'on' ) === 0 ) {
			$outstr .=  ' '.$key.'="'.$pParams[$key].'"';
		}
	}

	$outstr .= '></span>';

	if( !empty( $pParams['ilocation'] ) ) {
		if( $pParams['ilocation'] == 'menu' && isset( $pParams['iexplain'] ) ) {
			$outstr .= ' '.$pParams['iexplain'];
		}
	}
	if( isset( $pParams["href"] ) ) {
		$outstr .= '</a>';
	}

	return $outstr;
}

