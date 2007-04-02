<?php

global $gBitInstaller;

$tables = array(
	/* module_id			a unique id
	 * package				package for which this module is used
	 * layout_area			column in which this module is visible - l r or c
	 * module_rows			number of lines displayed
	 * module_rsrc			path to module template
	 * parameters			parameters for this particular module
	 * pos					positional data to specify the order in which the modules are displayed */

	'themes_layouts' => "
		module_id I4 PRIMARY,
		title C(255),
		layout C(160) NOTNULL DEFAULT 'kernel',
		layout_area C(1) NOTNULL,
		module_rows I4,
		module_rsrc C(250) NOTNULL,
		params C(255),
		cache_time I8,
		groups C(255),
		pos I4 NOTNULL DEFAULT '1'
	",

	'themes_custom_modules' => "
		name C(40) PRIMARY NOTNULL,
		title C(200),
		data X
	",

	/*
	original schema

	'themes_layouts' => "
		user_id I4 NOTNULL,
		module_id I4 NOTNULL,
		layout C(160) NOTNULL DEFAULT 'home',
		layout_position C(1) NOTNULL,
		module_rows I4,
		params C(255),
		ord I4 NOTNULL DEFAULT '1'
	",

	'themes_layouts_modules' => "
		module_id I4 PRIMARY,
		availability C(1),
		title C(255),
		cache_time I8,
		module_rows I4,
		params C(255),
		groups X
	",

	'themes_custom_modules' => "
		name C(200) PRIMARY,
		title C(40),
		data X
	",

	'themes_module_map' => "
		module_id I4 AUTO PRIMARY,
		module_rsrc C(250) NOTNULL
	",
	 */
);

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( THEMES_PKG_NAME, $tableName, $tables[$tableName], TRUE );
}

$indices = array (
	//'themes_layouts_user_id_idx' => array( 'table' => 'themes_layouts', 'cols' => 'user_id', 'opts' => NULL ),
	'themes_layouts_module_idx' => array( 'table' => 'themes_layouts', 'cols' => 'module_id', 'opts' => NULL ),
	//'themes_module_map_rsrc_idx' => array( 'table' => 'themes_module_map', 'cols' => 'module_rsrc', 'opts' => NULL )
);
$gBitInstaller->registerSchemaIndexes( THEMES_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'themes_layouts_module_id_seq' => array( 'start' => 1 )
);
$gBitInstaller->registerSchemaSequences( THEMES_PKG_NAME, $sequences );

$gBitInstaller->registerPackageInfo( THEMES_PKG_NAME, array(
	'description' => "The Themes package is an integral part of bitweaver which allows you to control the look and feel of you site.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
));

//$gBitInstaller->registerSchemaTable( THEMES_PKG_NAME, '', '', TRUE );
$gBitInstaller->registerPreferences( THEMES_PKG_NAME, array(
	array(THEMES_PKG_NAME,'site_slide_style', DEFAULT_THEME ),
	array(THEMES_PKG_NAME,'style', DEFAULT_THEME ),
	array(THEMES_PKG_NAME,'site_style_layout', 'gala_13' ),
	array(THEMES_PKG_NAME,'site_icon_style', 'tango' ),
	array(THEMES_PKG_NAME,'site_top_bar_dropdown','y' ),
	array(THEMES_PKG_NAME,'site_bot_bar','y'),
	array(THEMES_PKG_NAME,'site_top_bar','y'),
));

?>
