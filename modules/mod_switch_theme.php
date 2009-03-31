<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_themes/modules/mod_switch_theme.php,v 1.11 2009/03/31 06:27:27 lsces Exp $
 * @package kernel
 * @subpackage modules
 */

/**
 * Setup
 */
global $gBitThemes;
$change_theme = $gBitSystem->getConfig('users_themes');
$gBitSmarty->assign('change_theme', $change_theme);
$style = $gBitThemes->getStyle();

if( $change_theme == 'y' ) {
	if ($gBitUser->isValid() && $gBitSystem->getConfig('users_preferences') == 'y') {
		$userStyle = $gBitUser->getPreference('theme');
		$style = empty($userStyle) ? $style : $userStyle;
	}
	if (isset($_COOKIE['bit-theme'])) {
		$style = $_COOKIE['bit-theme'];
	}

	$styles = $gBitThemes->getStyles( NULL, TRUE );
	$stylesList = $gBitThemes->getStyles();

	$gBitSmarty->assign('styleslist',$stylesList);
	if(isset($style)){
		$gBitSmarty->assign('style', $style);
	}
}
?>
