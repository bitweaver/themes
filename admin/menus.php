<?php 
//
// (c) 2006 bitweaver.org - GNU LGPL
//
require_once( '../../bit_setup_inc.php' );

if( !empty( $_REQUEST['update_menus'] ) ) {
	if( !empty( $gBitSystem->mAppMenuDisabled ) ) {
		$disabled = &$gBitSystem->mAppMenuDisabled;
	} else {
		$disabled = array();
	}

	foreach( array_keys( array_merge( $gBitSystem->mAppMenu ,$disabled ) ) as $menuPackage ) {
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

$gBitSystem->verifyPermission( 'bit_p_admin' );

$gBitSystem->display( 'bitpackage:themes/admin_themes_menus.tpl', 'Themes Manager' );
?>
