<?php
$registerHash = array(
	'package_name' => 'themes',
	'package_path' => dirname( __FILE__ ).'/',
	'activatable' => FALSE,
	'required_package'=> TRUE,
);
$gBitSystem->registerPackage( $registerHash );

// the use of gPreviewStyle is deprecated and will be replaced by using $gBitThemes->setStyle();
// this is used to override the currently set site theme. when this is set everything else is ignored
global $gPreviewStyle;
$gPreviewStyle = FALSE;

require_once( THEMES_PKG_PATH."BitThemes.php" );
global $gBitThemes;
$gBitThemes = new BitThemes();

// if we're viewing this site with a text-browser, we force the text-browser theme
global $gSniffer;
if( !$gSniffer->_feature_set['css1'] && !$gSniffer->_feature_set['css2'] ) {
	$gBitThemes->setStyle( 'lynx' );
}

// setStyle first, in case package decides it wants to reset the style in it's own <package>/bit_setup_inc.php
$theme = $gBitThemes->getStyle();
$theme = !empty( $theme ) ? $theme : DEFAULT_THEME;
// users_themes='y' is for the entire site, 'h' is just for users homepage and is dealt with on users/index.php
if( !empty( $gBitSystem->mDomainInfo['style'] ) ) {
	$theme = $gBitSystem->mDomainInfo['style'];
} elseif( $gBitSystem->getConfig('users_themes') == 'y' ) {
	if ( $gBitUser->isRegistered() && $gBitSystem->isFeatureActive( 'users_preferences' ) ) {
		if( $userStyle = $gBitUser->getPreference('theme') ) {
			$theme = $userStyle;
		}
	}
	if( isset( $_COOKIE['tiki-theme'] )) {
		$theme = $_COOKIE['tiki-theme'];
	}
}
$gBitThemes->setStyle( $theme );

$gBitSmarty->assign_by_ref( 'gBitThemes', $gBitThemes );

?>
