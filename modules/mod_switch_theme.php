<?php
/**
 * @version $Header$
 * @package themes
 * @subpackage modules
 */

/**
 * Setup
 */
global $gBitThemes;
$change_theme = $gBitSystem->getConfig('users_themes');
$_template->tpl_vars['change_theme'] = new Smarty_variable( $change_theme);
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

	$_template->tpl_vars['styleslist'] = new Smarty_variable($stylesList);
	if(isset($style)){
		$_template->tpl_vars['style'] = new Smarty_variable( $style);
	}
}
?>
