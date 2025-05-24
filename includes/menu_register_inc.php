<?php
/**
 * @package themes
 * @subpackage functions
 */

/**
 * Required setup
 */
global $gBitUser, $gBitSystem, $gBitSmarty;

// Global menu
//	$gBitSystem->registerAppMenu( 'global', NULL, NULL, 'bitpackage:kernel/menu_global.tpl' );

// Application menu
uasort( $gBitSystem->mAppMenu, "mAppMenu_sort" );

// Admin menu
$adminMenu = array();
foreach( array_keys( $gBitSystem->mPackages ) as $package ) {
	if( $gBitUser->hasPermission( 'p_'.$package.'_admin' ) ) {
		$package = strtolower( $package );
		$tpl = "bitpackage:$package/menu_".$package."_admin.tpl";
		if(( $gBitSystem->isPackageActive( $package ) || $package == 'kernel') && $gBitSmarty->templateExists( $tpl )) {
			$adminMenu[$package]['tpl'] = $tpl;
			$adminMenu[$package]['display'] = 'display:'.( empty( $package ) || ( isset( $_COOKIE[$package.'admenu'] ) && ( $_COOKIE[$package.'admenu'] == 'o' ) ) ? 'block;' : 'none;' );
		}
	}
}

if( !empty( $adminMenu ) ) {
	ksort( $adminMenu );
	$gBitSmarty->assignByRef( 'adminMenu', $adminMenu );
}

/**
 * mAppMenu_sort
 */
function mAppMenu_sort( $a, $b ) {
	if( empty( $a['menu_position'] ) ) {
		return 0;
	} elseif( is_numeric( $a['menu_position'] ) ) {
		return( (int)$a['menu_position'] > (int)$b['menu_position'] );
	} elseif( !empty( $a['menu_title'] ) ) {
		return( strcmp( $a['menu_title'], $b['menu_title'] ) );
	}
}
?>
