<?php 
require_once( '../../bit_setup_inc.php' );
require_once( KERNEL_PKG_PATH.'simple_form_functions_lib.php' );

$gBitSystem->verifyPermission( 'p_admin' );

// apply the icon theme
if( !empty( $_REQUEST["site_icon_style"] ) ) {
	$gBitSystem->storeConfig( 'site_icon_style', $_REQUEST["site_icon_style"], THEMES_PKG_NAME );
}

// apply the style layout
if( !empty( $_REQUEST["site_style_layout"] ) ) {
	$gBitSystem->storeConfig( 'site_style_layout', ( ( $_REQUEST["site_style_layout"] != 'remove' ) ? $_REQUEST["site_style_layout"] : NULL ), THEMES_PKG_NAME );
}

// apply the site style
if( !empty( $_REQUEST["site_style"] ) ) {
	$gBitSystem->storeConfig( 'style', $_REQUEST["site_style"], THEMES_PKG_NAME );
	$gBitSystem->storeConfig( 'style_variation', !empty( $_REQUEST["style_variation"] ) ? $_REQUEST["style_variation"] : '', THEMES_PKG_NAME );
	$gPreviewStyle = $_REQUEST["site_style"];
	$gBitSystem->mStyle = $_REQUEST["site_style"];
}

// Get list of available styles
$styles = $gBitThemes->getStyles( NULL, TRUE );
$gBitSmarty->assign_by_ref( "styles", $styles );

$subDirs = array( 'style_info', 'alternate' );
$stylesList = $gBitThemes->getStylesList( NULL, NULL, $subDirs );
$gBitSmarty->assign_by_ref( "stylesList", $stylesList );

$subDirs = array( 'style_info' );
$iconStyles = $gBitThemes->getStylesList( THEMES_PKG_PATH."icon_styles/", NULL, $subDirs );
$gBitSmarty->assign_by_ref( "iconStyles", $iconStyles );

$styleLayouts = $gBitThemes->getStyleLayouts();
$gBitSmarty->assign_by_ref( "styleLayouts", $styleLayouts );

// pick some icons for the preview.
$sampleIcons = array(
	'applications-internet',
	'dialog-cancel',
	'dialog-error',
	'dialog-information',
	'dialog-ok',
	'dialog-warning',
	'emblem-favorite',
	'emblem-photos',
	'emblem-readonly',
	'go-jump',
	'go-home',
	'go-next',
	'go-up',
	'help-browser',
	'folder',
);
$gBitSmarty->assign( "sampleIcons", $sampleIcons );

// crude method of loading css styling but we can fix this later
$gBitSmarty->assign( "loadLayoutGalaCss", TRUE );

$gBitSystem->display( 'bitpackage:themes/admin_themes_manager.tpl', 'Themes Manager' );
?>
