<?php
$registerHash = array(
	'package_name' => 'themes',
	'package_path' => dirname( dirname( __FILE__ ) ).'/',
	'activatable' => FALSE,
	'required_package'=> TRUE,
);
$gBitSystem->registerPackage( $registerHash );

define( 'DEFAULT_ICON_STYLE', $gBitSystem->getConfig( 'default_icon_style', 'tango' ) );

$gLibertySystem->registerService(
	LIBERTY_SERVICE_THEMES,
	THEMES_PKG_NAME,
	array(
		'content_display_function' => 'themes_content_display',
		'content_list_function' => 'themes_content_list',
	),
	array( 'description' => 'Applied when user themes are enabled; See theme pkg administration to enable.' )
);

require_once( THEMES_PKG_CLASS_PATH.'BitThemes.php' );

BitThemes::loadSingleton();
global $gBitThemes, $gBitSmarty;

$gBitSmarty->verifyCompileDir();


// setStyle first, in case package decides it wants to reset the style in it's own <package>/bit_setup_inc.php
if( !$gBitThemes->getStyle() ) {
	$gBitThemes->setStyle( DEFAULT_THEME );
}
$gBitSmarty->assignByRef( 'gBitThemes', $gBitThemes );

// load some core javascript files
$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/bitweaver.js', TRUE, 1 );
$gBitThemes->loadAjax( $gBitSystem->getConfig( 'themes_jquery_hosting', 'jquery' ) );

if( $gBitSystem->isFeatureActive( 'site_fancy_zoom' )) {
	$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/fancyzoom/js-global/FancyZoom.js', TRUE, 80 );
	$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/fancyzoom/js-global/FancyZoomHTML.js', TRUE, 81 );
	$gBitSystem->setOnloadScript( 'setupZoom();' );
}

$gBitSystem->mOnload[] = 'BitBase.setupShowHide();';

$styleString = $gBitSystem->getConfig('style');

if( !defined( 'THEMES_PATH' ) ) {
	define( 'THEMES_PATH', CONFIG_PKG_PATH.'themes/' );
}
if( !defined( 'THEMES_URL' ) ) {
	define( 'THEMES_URL', CONFIG_PKG_URL.'themes/' );
}
if( !defined( 'THEMES_URI' ) ) {
	define( 'THEMES_URI', CONFIG_PKG_URI.'themes/' );
}
if( !defined( 'ICONSETS_PATH' ) ) {
	define( 'ICONSETS_PATH', CONFIG_PKG_PATH.'iconsets/' );
}
if( !defined( 'ICONSETS_URL' ) ) {
	define( 'ICONSETS_URL', CONFIG_PKG_URL.'iconsets/' );
}
if( !defined( 'ICONSETS_URI' ) ) {
	define( 'ICONSETS_URI', CONFIG_PKG_URI.'iconsets/' );
}

if( !defined( 'CONFIG_THEME_PATH' ) ) {
	define( 'CONFIG_THEME_PATH', THEMES_PATH.$styleString.'/' );
}
if( !defined( 'CONFIG_THEME_URL' ) ) {
	define( 'CONFIG_THEME_URL', THEMES_URL.$styleString.'/' );
}
if( !defined( 'CONFIG_IMAGE_PATH' ) ) {
	define( 'CONFIG_IMAGE_PATH', THEMES_PATH.$styleString.'/images/' );
}
if( !defined( 'CONFIG_IMAGE_URL' ) ) {
	define( 'CONFIG_IMAGE_URL', THEMES_URL.$styleString.'/images/' );
}
