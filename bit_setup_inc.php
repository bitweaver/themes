<?php
$registerHash = array(
	'package_name' => 'themes',
	'package_path' => dirname( __FILE__ ).'/',
	'activatable' => FALSE,
	'required_package'=> TRUE,
);
$gBitSystem->registerPackage( $registerHash );

$gLibertySystem->registerService( LIBERTY_SERVICE_THEMES, THEMES_PKG_NAME, array(
	'content_display_function' => 'themes_content_display',
	'content_list_function' => 'themes_content_list',
) );

require_once( THEMES_PKG_PATH."BitThemes.php" );
global $gBitThemes;
$gBitThemes = new BitThemes();

// if we're viewing this site with a text-browser, we force the text-browser theme
global $gSniffer;
if( !$gSniffer->_feature_set['css1'] && !$gSniffer->_feature_set['css2'] ) {
	$gBitThemes->setStyle( 'lynx' );
}

// setStyle first, in case package decides it wants to reset the style in it's own <package>/bit_setup_inc.php
if( !$gBitThemes->getStyle() ) {
	$gBitThemes->setStyle( DEFAULT_THEME );
}
$gBitSmarty->assign_by_ref( 'gBitThemes', $gBitThemes );

// load some core javascript files
$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/bitweaver.js', TRUE, 1 );

if( !$gBitSystem->isFeatureActive( 'site_disable_jstabs' )) {
	$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/libs/tabpane.js', TRUE, 40 );
}

if( !$gBitSystem->getConfig( 'site_disable_fat' )) {
	$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/libs/fat.js', TRUE, 50 );
}

if( $gBitSystem->isFeatureActive( 'site_top_bar_js' ) && $gBitSystem->isFeatureActive( 'site_top_bar_dropdown' )) {
	$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/libs/fsmenu.js', TRUE, 60 );
}

if( $gBitSystem->isFeatureActive( 'site_fancy_zoom' )) {
	$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/fancyzoom/js-global/FancyZoom.js', FALSE, 80 );
	$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/fancyzoom/js-global/FancyZoomHTML.js', TRUE, 81 );
	$gBitSystem->setOnloadScript( 'setupZoom();' );
}

// if the datafile plugin is active, we need to load the js file since we don't know where the plugin is being used
if( $gLibertySystem->isPluginActive( 'dataattachment' )) {
	$gBitThemes->loadJavascript( UTIL_PKG_PATH."javascript/flv_player/swfobject.js", FALSE, 25 );
}
?>
