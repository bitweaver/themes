<?php

global $gBitSystem, $gUpgradeFrom, $gUpgradeTo;

$upgrades = array(

'BONNIE' => array( 
	'CLYDE' => array(
// STEP 1
array( 'DATADICT' => array(
array( 'RENAMECOLUMN' => array( 
	'tiki_theme_control_categs' => array( '`categId`' => '`category_id` I4' ),
	'tiki_theme_control_objects' => array( '`objId`' => '`obj_id` I4' ),
	),
),
)),

	)
)
);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( THEMES_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}


?>
