<?php
require_once(THEMES_PKG_PATH."theme_control_lib.php");

$change_theme = $gBitSystem->getPreference('feature_user_theme');
$smarty->assign('change_theme', $change_theme);
$style = $gBitSystem->getStyle();

if( $change_theme == 'y' ) {
	if (isset($_COOKIE['tiki-theme'])) {
		$style = $_COOKIE['tiki-theme'];
	}
	if ($gBitUser->isValid() && $gBitSystem->getPreference('feature_userPreferences') == 'y') {
		$userStyle = $gBitUser->getPreference('theme');
		$style = empty($userStyle) ? $style : $userStyle;
	}

	global $tcontrollib;

	$stylesList = $tcontrollib->getStyles();

	$smarty->assign('styleslist',$stylesList);
	if(isset($style)){
		$smarty->assign('style', $style);
	}
}
?>
