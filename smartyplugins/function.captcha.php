<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

global $gBitSystem;

if( $gBitSystem->isFeatureActive('users_register_recaptcha') ) {
	require_once UTIL_PKG_PATH.'includes/recaptcha/autoload.php';
	$recaptcha = new \ReCaptcha\ReCaptcha( $gBitSystem->getConfig( 'users_register_recaptcha_private_key' ) );
}

if( $gBitSystem->isFeatureActive('users_register_smcaptcha') ) {
	require_once( USERS_PKG_PATH.'includes/solvemedialib.php' );
}

/**
 * smarty_function_captcha
 */
function smarty_function_captcha( $pParams, &$gBitSmarty ) {
	global $gBitSystem, $gBitUser;
	if( !empty( $pParams['force'] ) || empty( $_SESSION['captcha_verified'] ) && !$gBitUser->hasPermission( 'p_users_bypass_captcha' ) ) {
		$pParams['size'] = !empty( $pParams['size'] ) ? $pParams['size'] : '5';
		$pParams['variant'] = !empty( $pParams['variant'] ) ? $pParams['variant'] : 'condensed';
		if( !empty( $pParams['errors'] ) ) {
			$gBitSmarty->assign( 'errors', $pParams['errors'] );
		}
		if( $gBitSystem->isFeatureActive( 'liberty_use_captcha_freecap' ) ) {
			$pParams['source'] = UTIL_PKG_URL."includes/freecap/freecap.php";
		} else {
			$getString = 'size='.$pParams['size'];
			if( @BitBase::verifyId( $pParams['width'] ) ) {
				$getString .= '&width='.$pParams['width'];
			}
			if( @BitBase::verifyId( $pParams['height'] ) ) {
				$getString .= '&height='.$pParams['height'];
			}
			$pParams['source'] = USERS_PKG_URL."captcha_image.php?$getString";
		}
		$gBitSmarty->assign( 'params', $pParams );
		print $gBitSmarty->fetch( "bitpackage:themes/captcha.tpl" );
	}
}
?>
