<?php
$change_theme = $gBitSystem->getPreference('users_themes');
$gBitSmarty->assign('change_theme', $change_theme);
$style = $gBitSystem->getStyle();

if( $change_theme == 'y' ) {
	if (isset($_COOKIE['tiki-theme'])) {
		$style = $_COOKIE['tiki-theme'];
	}
	if ($gBitUser->isValid() && $gBitSystem->getPreference('users_preferences') == 'y') {
		$userStyle = $gBitUser->getPreference('theme');
		$style = empty($userStyle) ? $style : $userStyle;
	}

	$styles = $gBitThemes->getStyles( NULL, TRUE );
	$stylesList = $gBitThemes->getStyles();

	$gBitSmarty->assign('styleslist',$stylesList);
	if(isset($style)){
		$gBitSmarty->assign('style', $style);
	}
}
?>
