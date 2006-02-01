<?php

global $gBitInstaller;

$tables = array(

'themes_control_objects' => "
	obj_id C(250) PRIMARY,
	type C(250) NOTNULL,
	name C(250) NOTNULL,
	theme C(250) NOTNULL
",

'themes_control_categs' => "
	category_id I4 PRIMARY,
	theme C(250) NOTNULL
",

'themes_content_templates' => "
	template_id I4 AUTO PRIMARY,
	content X,
	name C(200),
	created I8
",

'themes_content_templates_sections' => "
	template_id I4 PRIMARY,
	section C(160) PRIMARY
",

'themes_layouts' => "
	user_id I4 NOTNULL,
	module_id I4 NOTNULL,
	layout C(160) NOTNULL DEFAULT 'home',
	position C(1) NOTNULL,
	rows I4,
	params C(255),
	ord I4 NOTNULL DEFAULT '1'
",

'tiki_layouts_modules' => "
	module_id I4 PRIMARY,
	availability C(1),
	title C(255),
	cache_time I8,
	rows I4,
	params C(255),
	groups X
",

'tiki_module_map' => "
	module_id I4 AUTO PRIMARY,
	module_rsrc C(250) NOTNULL
",


);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( THEMES_PKG_NAME, $tableName, $tables[$tableName], TRUE );
}


$gBitInstaller->registerPackageInfo( THEMES_PKG_NAME, array(
	'description' => "The Themes package is an integral part of bitweaver which allows you to control the look and feel of you site.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	'version' => '0.1',
	'state' => 'beta',
	'dependencies' => '',
) );

$gBitInstaller->registerSchemaTable( THEMES_PKG_NAME, '', '', TRUE );

?>
