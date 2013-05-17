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
	detoxify( $params );
	if( !empty( $params['hash'] ) ) {
		$hash = &$params['hash'];
	} else {
		// maybe params were passed in separately
		$hash = &$params;
	}
	$feedback = '';
	$i = 0;
	$color = isset( $hash['color'] )?$hash['color']:"000000";
	foreach( $hash as $key => $val ) {
		if( $val ) {
			$keys = array( 'warning', 'success', 'error', 'important', 'note' );
			if( in_array( $key, $keys )) {
				switch( $key ) {
					case 'success':
						$alertClass = 'alert alert-success';
						break;
					case 'warning':
						$alertClass = 'alert';
						break;
					case 'error':
						$alertClass = 'alert alert-error';
						break;
					case 'note':
					case 'important':
					default:
						$alertClass = 'alert alert-info';
						break;
				}

				if( !is_array( $val ) ) {
					$val = array( $val );
				}

				foreach( $val as $valText ) {
					if( is_array( $valText ) ) {
						foreach( $valText as $text ) {
							$feedback .= '<span class="inline-block '.$alertClass.'">'.$text.'</span>';
						}
					} else {
						$feedback .= '<span class="inline-block '.$alertClass.'">'.$valText.'</span>';
					}
				}

			} else {
				/* unfortunately this plugin was written a little strictly and so it expects all params to be display text
				 * to allow setting of a background color we have to exclude that param when rendering out the html
				 * otherwise we'll render the color as text. -wjames5
				 */ 
				if ( $key != 'color' ) {
					if( is_array( $val ) ) {
						foreach( $val as $text ) {
							$feedback .= '<span class="'.$key.'">'.$text.'</span>';
						}
					} else {
						$feedback .= '<span class="'.$key.'">'.$val.'</span>';
					}
				}
			}
		}
	}

	$html = '';
	if( !empty( $feedback ) ) {
		$html .= $feedback;
	}
	return $html;
}

?>
