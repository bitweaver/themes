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
	array( 'DROPTABLE' => array(
		'tiki_theme_control_categs',
		'tiki_theme_control_objects',
	)),
	array( 'RENAMETABLE' => array(
		'tiki_user_modules'    => 'themes_custom_modules',
		'tiki_layouts'         => 'themes_layouts',
		'tiki_layouts_modules' => 'themes_layouts_modules',
		'tiki_module_map'      => 'themes_module_map',
	)),
	array( 'RENAMECOLUMN' => array(
		'themes_layouts' => array(
			'`rows`' => '`module_rows` I4 NOTNULL',
			'`position`' => '`layout_position` C(1) NOTNULL',
		),
		'themes_layouts_modules' => array(
			'`rows`' => '`module_rows` I4'
		),
	)),
))
		)
	),

);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( THEMES_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}


?>
