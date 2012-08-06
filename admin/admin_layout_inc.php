<?php
// $Header$

// Initialization
require_once( '../../kernel/setup_inc.php' );

if( defined( 'ROLE_MODEL' )) {
	if( !isset($_REQUEST["roles"] ) ) {
		$_REQUEST["roles"] = array();
	}
} else {
	if( !isset($_REQUEST["groups"] ) ) {
		$_REQUEST["groups"] = array();
	}
}

if( empty( $_REQUEST['module_package'] ) ) {
	$_REQUEST['module_package'] = DEFAULT_PACKAGE;
}

$feedback = array();
$gBitSmarty->assign_by_ref( 'feedback', $feedback );
$layoutHash = array(
	'layout' => $_REQUEST['module_package'],
	'fallback' => FALSE,
);
$layout = $gBitThemes->getLayout( $layoutHash );

if( !empty( $_REQUEST['fixpos'] )) {
	$gBitThemes->fixPositions( $_REQUEST['module_package'] );
}

if( !empty( $_REQUEST['module_name'] ) ) {
	$fAssign['name'] = $_REQUEST['module_name'];
	$gBitSmarty->assign( 'fAssign', $fAssign );
}

$gBitSystem->verifyInstalledPackages();

// clone existing layout
$cloneLayouts = $gBitThemes->getAllLayouts();
$gBitSmarty->assign( 'cloneLayouts', $cloneLayouts );
if( !empty( $_REQUEST['from_layout'] ) && !empty( $_REQUEST['to_layout'] )) {
	$gBitThemes->cloneLayout( $_REQUEST['from_layout'], $_REQUEST['to_layout'] );
}

// process form - check what tab was used and set it
$processForm = set_tab();

if( isset( $_REQUEST['module_id'] ) && !empty( $_REQUEST['move_module'] )) {
	if( isset( $_REQUEST['move_module'] )) {
		switch( $_REQUEST['move_module'] ) {
			case "unassign":
				$gBitThemes->unassignModule( $_REQUEST['module_id'] );
				break;
			case "up":
				$gBitThemes->moveModuleUp( $_REQUEST['module_id'] );
				break;
			case "down":
				$gBitThemes->moveModuleDown( $_REQUEST['module_id'] );
				break;
			case "left":
				$gBitThemes->moveModuleToArea( $_REQUEST['module_id'], 'l' );
				break;
			case "right":
				$gBitThemes->moveModuleToArea( $_REQUEST['module_id'], 'r' );
				break;
		}
	}
} elseif( $processForm == 'Center' || $processForm == 'Column' ) {
	$fAssign = &$_REQUEST['fAssign'];

	if( defined( 'ROLE_MODEL' )) {
		if( !empty( $_REQUEST['roles'] ) ) {
			 $fAssign['roles'] = $_REQUEST['roles'];
		}
	} else {
		if( !empty( $_REQUEST['groups'] ) ) {
			$fAssign['groups'] = $_REQUEST['groups'];
		}
	}

	// either add the module to all available layouts or just the active one

	$fAssign['layout'] = $_REQUEST['module_package'];
	$gBitThemes->storeModule( $fAssign );
	unset( $fAssign['store'] );
	if( !empty( $fAssign['add_to_all'] )) {
		foreach( array_keys( $cloneLayouts ) as $pkg ) {
			if( $pkg != $_REQUEST['module_package'] ){
				$fAssign['layout'] = $pkg;
				$gBitThemes->storeModule( $fAssign );
				unset( $fAssign['store'] );
			}
		}
	} 
}

// this will sort the layout selection dropdown
$allLayouts = $gBitThemes->getAllLayouts();
ksort( $allLayouts );
$gBitSmarty->assign_by_ref( 'allLayouts', $allLayouts );
$gBitSmarty->assign( 'module_package', $_REQUEST['module_package'] );

$layoutHash = array(
	'layout' => $_REQUEST['module_package'],
	'fallback' => FALSE,
);
$editLayout = $gBitThemes->getLayout( $layoutHash );
$gBitThemes->generateModuleNames( $editLayout );
$gBitSmarty->assign( 'editLayout', $editLayout );

$combinedList = array_merge( array_keys( $gBitSystem->mPackages ), array_keys( $allLayouts ) );
$layoutList = array();
foreach( $combinedList as $l ) {
	$layoutList[$l] = $l;
	if( isset( $allLayouts[$l] ) ) {
		$layoutList[$l] .= ' *';
	}
}
asort( $layoutList );
$gBitSmarty->assign( 'layoutList', $layoutList );

if( $gBitSystem->isFeatureActive( 'site_top_column' )) {
	$layoutAreas['top'] = 't';
}
$layoutAreas['left']   = 'l';
$layoutAreas['center'] = 'c';
$layoutAreas['right']  = 'r';
if( $gBitSystem->isFeatureActive( 'site_bottom_column' )) {
	$layoutAreas['bottom'] = 'b';
}
$gBitSmarty->assign_by_ref( 'layoutAreas', $layoutAreas );

$allModules = $gBitThemes->getAllModules();
ksort( $allModules );
$gBitSmarty->assign_by_ref( 'allModules', $allModules );

$allModulesHelp = $gBitThemes->getAllModules( 'modules', 'help_mod_' );
ksort( $allModulesHelp );
$gBitSmarty->assign_by_ref( 'allModulesHelp', $allModulesHelp );

$allCenters = $gBitThemes->getAllModules( 'templates', 'center_' );
ksort( $allCenters );
$gBitSmarty->assign_by_ref( 'allCenters', $allCenters );

$orders = array();

for( $i = 1; $i < 50; $i++ ) {
	$orders[] = $i;
}

$gBitSmarty->assign_by_ref( 'orders', $orders );

if( defined( 'ROLE_MODEL' )) {
	$roles = $gBitUser->getAllUserRoles( ROOT_USER_ID );
	$gBitSmarty->assign_by_ref( "roles", $roles );
} else {
	$groups = $gBitUser->getAllUserGroups( ROOT_USER_ID );
	$gBitSmarty->assign_by_ref( "groups", $groups );
}
// we need gBitThemes as well
$gBitSmarty->assign_by_ref( "gBitThemes", $gBitThemes );

$gBitThemes->loadJavascript( THEMES_PKG_PATH.'scripts/BitThemes.js', TRUE );
?>
