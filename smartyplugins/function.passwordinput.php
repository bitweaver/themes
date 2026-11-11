<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * @link http://www.bitweaver.org/wiki/function_passwordinput function_passwordinput
 */

/**
 * Smarty {passwordinput} function plugin
 *
 * Type:     function
 * Name:     passwordinput
 * Purpose:  Password field with a show/hide eyeball in one input-group.
 *           Optional Generate addon fills this field and a confirm field
 *           via BitBase.genPass, then reveals both.
 *
 * Input:
 *           - name        (optional) form field name
 *           - value       (optional) current value
 *           - id          (optional) id of the password input
 *           - class       (optional) extra class on the input-group
 *           - size        (optional) sm|lg — Bootstrap input-group size
 *           - autocomplete (optional) autocomplete attribute
 *           - placeholder (optional)
 *           - maxlength   (optional)
 *           - title       (optional) title on the text input
 *           - required    (optional) if set, mark required
 *           - disabled    (optional) if set, disable the input
 *           - generate    (optional) id of the confirm field; when set,
 *                         add a Generate addon that fills both
 */
function smarty_function_passwordinput( $pParams, &$pSmarty=NULL ) {
	static $assetsPrinted = FALSE;

	$id = !empty( $pParams['id'] ) ? bit_passwordinput_token( $pParams['id'] ) : 'passwordinput-'.substr( md5( uniqid( (string)mt_rand(), TRUE ) ), 0, 8 );
	if( $id === '' ) {
		$id = 'passwordinput-'.substr( md5( uniqid( (string)mt_rand(), TRUE ) ), 0, 8 );
	}
	$name = isset( $pParams['name'] ) ? (string)$pParams['name'] : '';
	$value = isset( $pParams['value'] ) ? (string)$pParams['value'] : '';
	$placeholder = isset( $pParams['placeholder'] ) ? (string)$pParams['placeholder'] : '';
	$autocomplete = isset( $pParams['autocomplete'] ) ? (string)$pParams['autocomplete'] : '';
	$title = !empty( $pParams['title'] ) ? tra( $pParams['title'] ) : '';
	$size = !empty( $pParams['size'] ) ? $pParams['size'] : '';
	$extraClass = !empty( $pParams['class'] ) ? ' '.$pParams['class'] : '';
	$disabled = !empty( $pParams['disabled'] ) ? ' disabled="disabled"' : '';
	$required = !empty( $pParams['required'] ) ? ' required="required"' : '';
	$maxlength = isset( $pParams['maxlength'] ) && $pParams['maxlength'] !== '' ? (int)$pParams['maxlength'] : 0;

	$groupClass = 'input-group passwordinput'.$extraClass;
	if( $size === 'sm' || $size === 'lg' ) {
		$groupClass .= ' input-group-'.$size;
	}

	$showLabel = tra( 'Show password' );
	$hideLabel = tra( 'Hide password' );

	$html = '';
	if( !$assetsPrinted ) {
		$assetsPrinted = TRUE;
		$html .= bit_passwordinput_assets( $showLabel, $hideLabel );
	}

	$html .= '<div class="'.htmlspecialchars( $groupClass ).'">';
	$html .= '<input type="password" class="form-control passwordinput-field" id="'.htmlspecialchars( $id ).'"';
	if( $name !== '' ) {
		$html .= ' name="'.htmlspecialchars( $name ).'"';
	}
	$html .= ' value="'.htmlspecialchars( $value ).'"';
	if( $autocomplete !== '' ) {
		$html .= ' autocomplete="'.htmlspecialchars( $autocomplete ).'"';
	}
	if( $placeholder !== '' ) {
		$html .= ' placeholder="'.htmlspecialchars( $placeholder ).'"';
	}
	if( $title !== '' ) {
		$html .= ' title="'.htmlspecialchars( $title ).'"';
	}
	if( $maxlength > 0 ) {
		$html .= ' maxlength="'.$maxlength.'"';
	}
	$html .= $required.$disabled.' />';

	if( !empty( $pParams['generate'] ) ) {
		$confirmId = ( $pParams['generate'] === TRUE || $pParams['generate'] === '1' || strtolower( (string)$pParams['generate'] ) === 'true' )
			? $id
			: bit_passwordinput_token( $pParams['generate'] );
		if( $confirmId === '' ) {
			$confirmId = $id;
		}
		$jsId = htmlspecialchars( $id, ENT_QUOTES );
		$jsConfirm = htmlspecialchars( $confirmId, ENT_QUOTES );
		$html .= '<span class="input-group-addon" role="button" tabindex="0" onclick="BitBase.genPass(\''.$jsId.'\',\''.$jsId.'\',\''.$jsConfirm.'\');if(window.bitSetPasswordVisible){bitSetPasswordVisible(\''.$jsId.'\',true);bitSetPasswordVisible(\''.$jsConfirm.'\',true);}">'.tra( 'Generate' ).'</span>';
	}

	$html .= '<span class="input-group-addon passwordinput-toggle" role="button" tabindex="0" title="'.htmlspecialchars( $showLabel ).'" aria-label="'.htmlspecialchars( $showLabel ).'" aria-pressed="false" aria-controls="'.htmlspecialchars( $id ).'"><span class="fa fal fa-eye passwordinput-icon" aria-hidden="true"></span></span>';

	$html .= '</div>';

	return $html;
}

function bit_passwordinput_token( $pValue ) {
	return preg_replace( '/[^A-Za-z0-9_-]/', '', (string)$pValue );
}

function bit_passwordinput_assets( $pShowLabel, $pHideLabel ) {
	$show = json_encode( (string)$pShowLabel );
	$hide = json_encode( (string)$pHideLabel );
	return '<script type="text/javascript">/*<![CDATA[*/
(function(){
	if( window.bitPasswordInputReady ) { return; }
	window.bitPasswordInputReady = true;
	var SHOW = '.$show.';
	var HIDE = '.$hide.';
	function bitSetPasswordVisible( idOrEl, show ) {
		var input = typeof idOrEl === "string" ? document.getElementById( idOrEl ) : idOrEl;
		if( !input ) { return; }
		input.type = show ? "text" : "password";
		var group = input.closest ? input.closest(".passwordinput") : null;
		if( !group ) { return; }
		var btn = group.querySelector(".passwordinput-toggle");
		if( !btn ) { return; }
		var icon = btn.querySelector(".passwordinput-icon");
		if( icon ) {
			icon.classList.toggle("fa-eye", !show);
			icon.classList.toggle("fa-eye-slash", show);
		}
		btn.setAttribute("aria-pressed", show ? "true" : "false");
		btn.setAttribute("title", show ? HIDE : SHOW);
		btn.setAttribute("aria-label", show ? HIDE : SHOW);
	}
	window.bitSetPasswordVisible = bitSetPasswordVisible;
	if( window.BitBase ) {
		BitBase.setPasswordVisible = bitSetPasswordVisible;
	}
	function toggleFromEvent( e ) {
		var btn = e.target.closest ? e.target.closest(".passwordinput-toggle") : null;
		if( !btn ) { return; }
		e.preventDefault();
		var group = btn.closest(".passwordinput");
		var input = group ? group.querySelector(".passwordinput-field") : null;
		if( !input ) { return; }
		bitSetPasswordVisible( input, input.type === "password" );
	}
	document.addEventListener("click", toggleFromEvent);
	document.addEventListener("keydown", function( e ) {
		if( e.key !== "Enter" && e.key !== " " ) { return; }
		var btn = e.target.closest ? e.target.closest(".passwordinput-toggle") : null;
		if( !btn ) { return; }
		e.preventDefault();
		toggleFromEvent( e );
	});
})();
/*]]>*/</script>';
}
