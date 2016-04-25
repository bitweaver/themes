<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * @link http://www.bitweaver.org/wiki/function_textarea function_textarea
 */

/** 
 * Smarty plugin 
 * ------------------------------------------------------------- 
 * File: function.textarea.php 
 * Type: function 
 * Name: textarea 
 * ------------------------------------------------------------- 
 */ 
function smarty_function_textarea( $pParams, &$pSmarty ) {
	global $gBitSystem, $gContent, $gLibertySystem, $gBitSmarty;

	$attributes = '';
	$style = '';
	$class = 'form-control ';
	if (((!empty($gLibertySystem->mPlugins['bithtml']) && $gLibertySystem->mPlugins['bithtml']['is_active'] == 'y') ||
		(!empty($gLibertySystem->mPlugins['tikiwiki']) && $gLibertySystem->mPlugins['tikiwiki']['is_active'] == 'y' &&
		$gBitSystem->isFeatureActive( 'content_force_allow_html' )))
		&& empty( $pParams['nowysiwyg'] )
		) {
		$class .= 'wysiwyg';
	}
	if (empty($pParams['rows'])) {
		$pParams['rows'] = (empty($_COOKIE['rows']) ? $gBitSystem->getConfig('liberty_textarea_height', 20) : $_COOKIE['rows']);
	}
	if (empty($pParams['cols'])) {
		$pParams['cols'] = (empty($_COOKIE['cols']) ? $gBitSystem->getConfig('liberty_textarea_width', 35) : $_COOKIE['rows']);
	}
	if (empty($pParams['id'])) {
		$pParams['id'] = LIBERTY_TEXT_AREA;
	}
	if( empty( $pParams['name'] ) ){
		$pParams['name'] = 'edit';
	}
	if( empty( $pParams['maxchars'] ) ){
		// prevent smarty presistence of vars
		$pParams['maxchars'] = 0;
	}
	foreach ($pParams as $_key=>$_value) {
		switch ($_key) {
		case 'edit':
		case 'name':
		case 'id':
		case 'help':
		case 'noformat':
		case 'label':
		case 'error':
		case 'required':
		case 'maxchars':
			$pSmarty->assign("textarea_".$_key, $_value);
			break;
		case 'class':
			$class .= ' '.$_key;
			break;
		case 'style':
			$style .= $_key;
			break;
		case 'formatguid':
			$pSmarty->assign('formatGuid', $_value);
			break;
		case 'langcode':
			$pSmarty->assign('langCode', $_value);
			break;
		case 'gContent':
			// Trick out gContent
			$oldContent = $gContent;
			$gContent = $_value;
			$pSmarty->assign('gContent', $_value);
			break;
		default:
			$attributes .= $_key.'="'.$_value.'" ';
			break;
		}
	}
	// We control hieght here when bnspell is on so as to be able to not
	// lose the rest of the style on the textarea.
	if ($gBitSystem->isPackageActive('bnspell')) {
		$style .= (empty($style) ? '' : ';').'height:'.$pParams['rows'].'em;';
	}
	$pSmarty->assign('textarea_attributes', $attributes);

	if (!empty($style)) {
		$pSmarty->assign('textarea_style', 'style="'.$style.'"');
	}
	$pSmarty->assign('textarea_class', 'class="form-control '.$class.'"');
	$ret = $pSmarty->fetch("bitpackage:liberty/edit_textarea.tpl");
	if( is_object( $gContent ) ) {
		if( $formatGuid = $gContent->getField( 'format_guid' ) ) {
			$pSmarty->assign('formatGuid', $formatGuid);
		}
		if( $langCode = $gContent->getField( 'lang_code' ) ) {
			$pSmarty->assign( 'langCode', $langCode );
		}
	}

	// Restore gContent
	if (isset($oldContent)) {
		$gContent = $oldContent;
		$pSmarty->assign('gContent', $oldContent);
	}

	// since we have the funky {textarea} in play, we'll display the edit help tab
	$pSmarty->assign( 'display_help_tab', TRUE );

	return $ret;
}
?>
