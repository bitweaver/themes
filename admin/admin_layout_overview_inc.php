<?php
if( !empty( $_REQUEST['update_modules'] ) && is_array( $_REQUEST['modules'] )) {
	foreach( $_REQUEST['modules'] as $module_id => $module ) {
		$module['module_id'] = $module_id;
		$gBitThemes->storeModule( $module );
	}
}

if( !empty( $_REQUEST['fix_pos'] )) {
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
$gBitSmarty->assign_by_ref( 'layoutAreas', $layoutAreas );

$layouts = $gBitThemes->getAllLayouts();
foreach( $layouts as $package => $layout ) {
	$gBitThemes->generateModuleNames( $layout );
	$layouts[$package] = $layout;
}
$gBitSmarty->assign_by_ref( 'layouts', $layouts );

$groups = $gBitUser->getAllUserGroups( ROOT_USER_ID );
$gBitSmarty->assign_by_ref( "groups", $groups );
?>
