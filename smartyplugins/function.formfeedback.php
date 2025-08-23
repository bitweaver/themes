<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {formfeedback} function plugin
 *
 * Type:     function
 * Name:     formfeedback
 * Input:
 *           - warning, error or success are defined css styles, but you can feed it anything
 */
function smarty_function_formfeedback( $params, &$gBitSmarty ) {
	if( !empty( $params['pagekey'] ) ) {
		$params = BitFeedback::get( $params['pagekey'] );
	}
	return themes_feedback_to_html( $params );
}

