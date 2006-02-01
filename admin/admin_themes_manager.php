<?php 
require_once( '../../bit_setup_inc.php' );
require_once( KERNEL_PKG_PATH.'simple_form_functions_lib.php' );

$gBitSystem->verifyPermission( 'bit_p_admin' );

// Handle Update
$processForm = set_tab();

if( $processForm ) {
	$pref_simple_values = array(
		"slide_style",
		"biticon_display",
	);

	foreach ($pref_simple_values as $svitem) {
		simple_set_value ($svitem);
	}

	$pref_toggles = array(
		"disable_jstabs",
		"disable_fat",
	);

	foreach ($pref_toggles as $toggle) {
		simple_set_toggle ($toggle);
	}

	if( isset( $_REQUEST['fRemoveTheme'] ) ) {
		$gBitThemes->expunge_dir( THEMES_PKG_PATH.'styles/'.$_REQUEST['fRemoveTheme'] );
	}
}

// apply the site style
if( !empty( $_REQUEST["site_style"] ) ) {
	$gBitSystem->storePreference( 'style', $_REQUEST["site_style"] );
	$gBitSystem->storePreference( 'style_variation', !empty( $_REQUEST["style_variation"] ) ? $_REQUEST["style_variation"] : '' );
	$gPreviewStyle = $_REQUEST["site_style"];
	$gBitSystem->mStyle = $_REQUEST["site_style"];
}

// Get list of available styles
$styles = $gBitThemes->getStyles( NULL, TRUE );
$gBitSmarty->assign_by_ref( "styles", $styles );
$subDirs = array( 'style_info', 'alternate' );
$stylesList = $gBitThemes->getStylesList( NULL, NULL, $subDirs );
$gBitSmarty->assign_by_ref( "stylesList", $stylesList );

// set the options biticon takes
$biticon_display_options = array( 
	'icon' => tra( 'icon' ), 
	'text' => tra( 'text' ), 
	'icon_text' => tra( 'icon and text' ) 
);
$gBitSmarty->assign( "biticon_display_options", $biticon_display_options );

$gBitSystem->display( 'bitpackage:themes/admin_themes_manager.tpl', 'Themes Manager' );
?>
