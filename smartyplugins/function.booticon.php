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
function smarty_function_booticon( $pParams, $pFile ) {
	global $gBitSystem;

	if( empty( $pParams['iforce'] )) {
		$pParams['iforce'] = NULL;
	}

	if( isset( $pParams["url"] )) {
		$outstr .= $pFile;
	} else {
		$outstr = '';
		if( isset( $pParams['href'] ) ) {
			$outstr .= '<a href="'.$pParams['href'].'" class="icon"';
			if( isset( $pParams['iexplain'] ) ) {
				$outstr .= ' title="'.htmlentities( $pParams['iexplain'] ).'"';
			}
			$outstr .= '>';
		}

		$outstr .= '<div class="'.$pParams['iname'];
		if( isset( $pParams["iclass"] ) ) {
			$outstr .=  ' '.$pParams["iclass"].'';
		}
		$outstr .= '"';

		if( isset( $pParams["id"] ) ) {
			$outstr .=  ' id="'.$pParams['id'].'"';
		}

		$outstr .= '></div>';

		if( !empty( $pParams['ilocation'] ) ) {
			if( $pParams['ilocation'] == 'menu' && isset( $pParams['iexplain'] ) ) {
				$outstr .= ' '.$pParams['iexplain'];
			}
		}
		if( isset( $pParams["href"] ) ) {
			$outstr .= '</a>';
		}
	}

	return $outstr;
}

