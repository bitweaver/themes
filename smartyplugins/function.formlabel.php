<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {formlabel} function plugin
 *
 * Type:     function
 * Name:     formlabel
 * Input:
 *           - label       (required) - words that are displayed
 *		  - mandatory (optional) - add a class formmandatory in the div
 */
function smarty_function_formlabel( $params,&$gBitSmarty ) {
	$atts = '';
	$class = 'large-10 content-right';
	foreach($params as $key => $val) {
		switch( $key ) {
			case 'label':
				$name = $val;
				break;
			case 'mandatory':
				$mandatory = true;
			case 'class':
				$class .= ' '.$val;
			default:
				if( $val ) {
					$atts .= ' '.$key.'="'.$val.'"';
				}
				break;
		}
	}
	$html = '<label class="'.$class.'" ';
	if (isset($mandatory) && $mandatory) {
		$html .= ' required';
	}
	if( $atts != '' ) {
		$html .= $atts;
	}
	$html .= '>';
	if( empty( $params['no_translate'] ) ) {
		$html .= tra( $name );
	} else {
		$html .= $name;
	}
	$html .= '</label>';
	return $html;
}
?>
