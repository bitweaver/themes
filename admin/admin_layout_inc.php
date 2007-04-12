<?php
// $Header: /cvsroot/bitweaver/_bit_themes/admin/admin_layout_inc.php,v 1.4 2007/04/12 17:56:08 squareing Exp $

// Initialization
require_once( '../../bit_setup_inc.php' );

if( !isset($_REQUEST["groups"] ) ) {
	$_REQUEST["groups"] = array();
}

if( empty( $_REQUEST['module_package'] ) ) {
	$_REQUEST['module_package'] = DEFAULT_PACKAGE;
}

$gBitSmarty->assign_by_ref( 'feedback', $feedback = array() );
$layoutHash = array(
	'layout' => $_REQUEST['module_package'],
	'fallback' => FALSE,
);
$layout = $gBitThemes->getLayout( $layoutHash );

/* this is now part of the overview page to simpify the ui on this page
if( !empty( $_REQUEST['update_modules'] ) && is_array( $_REQUEST['modules'] )) {
	foreach( $_REQUEST['modules'] as $module_id => $module ) {
		$module['module_id'] = $module_id;
		$module['user_id']   = ROOT_USER_ID;
		$gBitThemes->storeModule( $module );
	}
}
 */

if( !empty( $_REQUEST['fix_pos'] )) {
	$gBitThemes->fixPositions( $_REQUEST['module_package'] );
}

if( !empty( $_REQUEST['module_name'] ) ) {
	$fAssign['name'] = $_REQUEST['module_name'];
	$gBitSmarty->assign( 'fAssign', $fAssign );
}

$gBitSystem->verifyInstalledPackages();

$formMiscFeatures = array(
	'site_top_column' => array(
		'label' => 'Top Module Area',
		'note' => 'Check to enable the top module area site-wide.',
	),
	'site_right_column' => array(
		'label' => 'Right Module Area',
		'note' => 'Check to enable the right module area site-wide.',
	),
	'site_left_column' => array(
		'label' => 'Left Module Area',
		'note' => 'Check to enable the left module area site-wide.',
	),
	'site_bottom_column' => array(
		'label' => 'Bottom Module Area',
		'note' => 'Check to enable the bottom module area site-wide.',
	),
);
$gBitSmarty->assign( 'formMiscFeatures',$formMiscFeatures );

foreach( $gBitSystem->mPackages as $key => $package ) {
	if( !empty( $package['installed'] ) && ( !empty( $package['activatable'] ) || !empty( $package['tables'] ) ) ) {
		if( $package['name'] == 'kernel' ) {
			$package['name'] = tra( 'Site Default' );
		}
		$hideColumns[strtolower( $key )] =  ucfirst( $package['name'] );
	}
}
asort( $hideColumns );
$gBitSmarty->assign( 'hideColumns', $hideColumns );

// process form - check what tab was used and set it
$processForm = set_tab();

if( $processForm == 'Hide' ) {
	foreach( array_keys( $formMiscFeatures ) as $item ) {
		simple_set_toggle( $item, THEMES_PKG_NAME );
	}

	// hideable areas
	$hideable = array( 'top', 'left', 'right', 'bottom' );

	// evaluate what columns to hide
	foreach( $hideable as $area ) {
	foreach( array_keys( $hideColumns ) as $package ) {
		$pref = "{$package}_hide_{$area}_col";
		if( isset( $_REQUEST['hide'][$pref] ) ) {
			$gBitSystem->storeConfig( $pref, 'y', THEMES_PKG_NAME );
		} else {
			// remove the setting from the db if it's not set
			$gBitSystem->storeConfig( $pref, NULL );
		}
	}
	}
} elseif( isset( $_REQUEST['module_id'] ) && !empty( $_REQUEST['move_module'] )) {
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
	if( !empty( $_REQUEST['groups'] ) ) {
		$_REQUEST['fAssign']['groups'] = '';
		foreach( $_REQUEST['groups'] as $groupId ) {
			$_REQUEST['fAssign']['groups'] .= $groupId.' ';
		}
	}
	$fAssign = &$_REQUEST['fAssign'];
	$fAssign['layout'] = $_REQUEST['module_package'];
	$gBitThemes->storeModule( $fAssign );
}

// this will sort the layout selection dropdown
ksort( $gBitSystem->mPackages );
$gBitSmarty->assign( 'module_package', $_REQUEST['module_package'] );

$layoutHash = array(
	'layout' => $_REQUEST['module_package'],
	'fallback' => FALSE,
);
$layout = $gBitThemes->getLayout( $layoutHash );
$gBitThemes->generateModuleNames( $layout );
$gBitSmarty->assign_by_ref( 'layout', $layout );

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
$groups = $gBitUser->getAllUserGroups( ROOT_USER_ID );
$gBitSmarty->assign_by_ref( "groups", $groups );

// we need gBitThemes as well
$gBitSmarty->assign_by_ref( "gBitThemes", $gBitThemes );
?>
