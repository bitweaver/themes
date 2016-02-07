<?php
if( !empty( $_REQUEST['update_modules'] ) && is_array( $_REQUEST['modules'] )) {
	foreach( $_REQUEST['modules'] as $module_id => $module ) {
		$module['module_id'] = $module_id;
		$gBitThemes->storeModule( $module );
	}
}

if( !empty( $_REQUEST['fixpos'] )) {
	$gBitThemes->fixPositions();
}

if( !empty( $_REQUEST['remove_layout'] )) {
	$gBitThemes->expungeLayout( $_REQUEST['remove_layout'] );
}

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
}

if( $gBitSystem->isFeatureActive( 'site_top_column' )) {
	$layoutAreas['top'] = 't';
}
$layoutAreas['left']   = 'l';
$layoutAreas['center'] = 'c';
$layoutAreas['right']  = 'r';
if( $gBitSystem->isFeatureActive( 'site_bottom_column' )) {
	$layoutAreas['bottom'] = 'b';
}
$gBitSmarty->assignByRef( 'layoutAreas', $layoutAreas );

$layouts = $gBitThemes->getAllLayouts();
foreach( $layouts as $package => $layout ) {
	$gBitThemes->generateModuleNames( $layout );
	$layouts[$package] = $layout;
}
$gBitSmarty->assignByRef( 'layouts', $layouts );

$allModulesHelp = $gBitThemes->getAllModules( 'modules', 'help_mod_' );
ksort( $allModulesHelp );
$gBitSmarty->assignByRef( 'allModulesHelp', $allModulesHelp );

$gBitSmarty->assign( 'pageName', 'Layout Options' );
if( defined( 'ROLE_MODEL' )) {
	$roles = $gBitUser->getAllUserRoles( ROOT_USER_ID );
	$gBitSmarty->assignByRef( "roles", $roles );
} else {
	$groups = $gBitUser->getAllUserGroups( ROOT_USER_ID );
	$gBitSmarty->assignByRef( "groups", $groups );
}
?>
