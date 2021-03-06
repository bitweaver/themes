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

