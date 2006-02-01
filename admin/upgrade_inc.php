<?php

global $gBitSystem, $gUpgradeFrom, $gUpgradeTo;

$upgrades = array(

'BONNIE' => array( 
	'BWR1' => array(
// STEP 1
array( 'DATADICT' => array(
array( 'RENAMECOLUMN' => array( 
	'tiki_theme_control_categs' => array( '`categId`' => '`category_id` I4' ),
	'tiki_theme_control_objects' => array( '`objId`' => '`obj_id` I4' ),
	),
),
)),

	)
),

	'BWR1' => array(
		'BWR2' => array(
// de-tikify tables
array( 'DATADICT' => array(
	array( 'RENAMETABLE' => array(
		'tiki_theme_control_categs' => 'themes_control_categs',
		'tiki_theme_control_objects' => 'themes_control_objects',
	)),
)),
		)
	),

);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( THEMES_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}


?>
