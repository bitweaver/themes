<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * @link http://www.bitweaver.org/wiki/function_colorinput function_colorinput
 */

/**
 * Smarty {colorinput} function plugin
 *
 * Type:     function
 * Name:     colorinput
 * Purpose:  Hex text field plus native color picker and swatch.
 *
 * Input:
 *           - name        (optional) form field name on the text input
 *           - value       (optional) #RGB or #RRGGBB
 *           - id          (optional) id of the text input
 *           - class       (optional) extra class on the input-group
 *           - size        (optional) sm|lg — Bootstrap input-group size
 *           - title       (optional) title on the text input
 *           - aria-label  (optional) accessible name for the text input
 *           - disabled    (optional) if set, disable both inputs
 */
function smarty_function_colorinput( $pParams, &$pSmarty=NULL ) {
	static $assetsPrinted = FALSE;

	$value = bit_colorinput_normalize( isset( $pParams['value'] ) ? $pParams['value'] : '' );
	if( $value === '' ) {
		$value = '#000000';
	}

	$id = !empty( $pParams['id'] ) ? $pParams['id'] : 'colorinput-'.substr( md5( uniqid( (string)mt_rand(), TRUE ) ), 0, 8 );
	$name = !empty( $pParams['name'] ) ? $pParams['name'] : '';
	$title = !empty( $pParams['title'] ) ? tra( $pParams['title'] ) : tra( 'Color' );
	$aria = !empty( $pParams['aria-label'] ) ? tra( $pParams['aria-label'] ) : $title;
	$size = !empty( $pParams['size'] ) ? $pParams['size'] : '';
	$extraClass = !empty( $pParams['class'] ) ? ' '.$pParams['class'] : '';
	$disabled = !empty( $pParams['disabled'] ) ? ' disabled="disabled"' : '';

	$groupClass = 'input-group colorinput'.$extraClass;
	if( $size === 'sm' || $size === 'lg' ) {
		$groupClass .= ' input-group-'.$size;
	}

	$pickerId = $id.'-picker';
	$swatchId = $id.'-swatch';

	$html = '';
	if( !$assetsPrinted ) {
		$assetsPrinted = TRUE;
		$html .= bit_colorinput_assets();
	}

	$html .= '<div class="'.htmlspecialchars( $groupClass ).'">';
	$html .= '<span class="input-group-addon colorinput-swatch" id="'.htmlspecialchars( $swatchId ).'" style="background:'.htmlspecialchars( $value ).'" title="'.htmlspecialchars( tra( 'Pick color' ) ).'" role="button" tabindex="0">&nbsp;</span>';
	$html .= '<input type="text" class="form-control colorinput-text" id="'.htmlspecialchars( $id ).'"';
	if( $name !== '' ) {
		$html .= ' name="'.htmlspecialchars( $name ).'"';
	}
	$html .= ' value="'.htmlspecialchars( $value ).'" maxlength="7" autocomplete="off" spellcheck="false" title="'.htmlspecialchars( $title ).'" aria-label="'.htmlspecialchars( $aria ).'"'.$disabled.' />';
	$html .= '<input type="color" class="colorinput-picker" id="'.htmlspecialchars( $pickerId ).'" value="'.htmlspecialchars( strtolower( $value ) ).'" tabindex="-1" aria-hidden="true"'.$disabled.' />';
	$html .= '</div>';

	return $html;
}

function bit_colorinput_normalize( $pValue ) {
	$hex = trim( (string)$pValue );
	if( $hex === '' ) {
		return '';
	}
	if( $hex[0] !== '#' ) {
		$hex = '#'.$hex;
	}
	if( preg_match( '/^#([0-9A-Fa-f])([0-9A-Fa-f])([0-9A-Fa-f])$/', $hex, $m ) ) {
		$hex = '#'.$m[1].$m[1].$m[2].$m[2].$m[3].$m[3];
	}
	if( !preg_match( '/^#[0-9A-Fa-f]{6}$/', $hex ) ) {
		return '';
	}
	return strtoupper( $hex );
}

function bit_colorinput_assets() {
	return '<style type="text/css">
.colorinput{position:relative;}
.colorinput-swatch{cursor:pointer;min-width:2em;}
.colorinput-picker{position:absolute;opacity:0;width:0;height:0;pointer-events:none;}
</style>
<script type="text/javascript">/*<![CDATA[*/
(function($){
	if( window.bitColorInputReady ) { return; }
	window.bitColorInputReady = true;
	function bitColorInputNorm( v ) {
		v = String( v || "" ).trim();
		if( !v ) { return null; }
		if( v.charAt(0) !== "#" ) { v = "#"+v; }
		if( /^#[0-9A-Fa-f]{3}$/.test(v) ) {
			v = "#"+v.charAt(1)+v.charAt(1)+v.charAt(2)+v.charAt(2)+v.charAt(3)+v.charAt(3);
		}
		if( !/^#[0-9A-Fa-f]{6}$/.test(v) ) { return null; }
		return v.toUpperCase();
	}
	function bitColorInputApply( $root, hex ) {
		var n = bitColorInputNorm( hex );
		if( !n ) { return false; }
		$root.find(".colorinput-text").val( n );
		$root.find(".colorinput-picker").val( n.toLowerCase() );
		$root.find(".colorinput-swatch").css( "background", n );
		return true;
	}
	$(document).on("click keypress", ".colorinput-swatch", function(e) {
		if( e.type === "keypress" && e.which !== 13 && e.which !== 32 ) { return; }
		e.preventDefault();
		var picker = $(this).closest(".colorinput").find(".colorinput-picker").get(0);
		if( picker && typeof picker.click === "function" ) { picker.click(); }
	});
	$(document).on("change", ".colorinput-picker", function() {
		bitColorInputApply( $(this).closest(".colorinput"), this.value );
	});
	$(document).on("blur", ".colorinput-text", function() {
		var $root = $(this).closest(".colorinput");
		if( !bitColorInputApply( $root, this.value ) ) {
			bitColorInputApply( $root, $root.find(".colorinput-picker").val() );
		}
	});
})(jQuery);
/*]]>*/</script>';
}
?>
