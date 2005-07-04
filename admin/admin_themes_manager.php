<?php 
require_once( '../../bit_setup_inc.php' );
include_once( THEMES_PKG_PATH.'theme_control_lib.php' );

$gBitSystem->verifyPermission( 'bit_p_admin' );

require_once( KERNEL_PKG_PATH.'simple_form_functions_lib.php' );

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
	);

	foreach ($pref_toggles as $toggle) {
		simple_set_toggle ($toggle);
	}

	if( isset( $_REQUEST['fRemoveTheme'] ) ) {
		$tcontrollib->expunge_dir( THEMES_PKG_PATH.'styles/'.$_REQUEST['fRemoveTheme'] );
	}

	//Clear the template cache in case the theme has changed TODO: Do this ONLY when the theme changes.
	//TODO: Fix module render order so that this will correctly render the modules on the 1st try too.
//	$smarty->clear_all_cache();
//	$smarty->clear_compiled_tpl();
}

// apply the site style
if( !empty( $_REQUEST["site_style"] ) ) {
	$gBitSystem->storePreference( 'style', $_REQUEST["site_style"] );
	$gPreviewStyle = $_REQUEST["site_style"];
	$gBitSystem->mStyle = $_REQUEST["site_style"];
}

// Get list of available styles
$styles = &$tcontrollib->getStyles();
$smarty->assign_by_ref( "styles", $styles );
$stylesList = &$tcontrollib->getStylesList();
$smarty->assign_by_ref( "stylesList", $stylesList );

// set the options biticon takes
$biticon_display_options = array( 
	'icon' => tra( 'icon' ), 
	'text' => tra( 'text' ), 
	'icon_text' => tra( 'icon and text' ) 
);
$smarty->assign( "biticon_display_options", $biticon_display_options );

$gBitSystem->display( 'bitpackage:themes/admin_themes_manager.tpl', 'Themes Manager' );
?>
