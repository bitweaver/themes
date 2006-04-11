<?php 
//
// (c) 2006 bitweaver.org - GNU LGPL
//
require_once( '../../bit_setup_inc.php' );
require_once( KERNEL_PKG_PATH."simple_form_functions_lib.php" );

$formMenuSettings = array(
	'top_bar' => array(
		'label' => 'Top bar menu',
		'note' => 'Here you can enable or disable the menubar at the top of the page (available in most themes). Before you disable this bar, please make sure you have some means of navigation set up to access at least the administration page.',
	),
	'top_bar_dropdown' => array(
		'label' => 'Dropdown menu',
		'note' => 'Use the CSS driven dropdown menus in the top bar. Compatibility and further reading can be found at <a class="external" href="http://www.htmldog.com/articles/suckerfish/dropdowns/">Suckerfish Dropdowns</a>.',
	),
	'hide_my_top_bar_link' => array(
		'label' => 'Hide "My" Link',
		'note' => 'Hide the <strong>My &lt;sitename&gt;</strong> link from users that are not logged in.',
	),
);
$gBitSmarty->assign( 'formMenuSettings',$formMenuSettings );

if( !empty( $_REQUEST['menu_settings'] ) ) {
	foreach( array_keys( $formMenuSettings ) as $item ) {
		simple_set_toggle( $item, THEMES_PKG_NAME );
	}
	simple_set_value( 'site_menu_title', THEMES_PKG_NAME );
}

if( !empty( $_REQUEST['update_menus'] ) ) {
	foreach( array_keys( $gBitSystem->mAppMenu ) as $menuPackage ) {
		if( empty($_REQUEST["menu_$menuPackage"] ) ) {
			// the package menu is off - store it off
			$gBitSystem->storeConfig( "menu_$menuPackage", 'n', 'themes', THEMES_PKG_NAME );
		} elseif( $gBitSystem->getConfig( "menu_$menuPackage" ) == 'n' ) {
			// the package menu was off and now is on. Just delete the pref since on is the assumed state
			$gBitSystem->storeConfig( "menu_$menuPackage", NULL, 'themes', THEMES_PKG_NAME );
		}
	}
	header( "Location: ".THEMES_PKG_URL."admin/menus.php" );
	die;
}

$gBitSystem->verifyPermission( 'p_admin' );

$gBitSystem->display( 'bitpackage:themes/admin_themes_menus.tpl', 'Themes Manager' );
?>
