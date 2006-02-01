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

'themes_control_objects' => "
	obj_id C(250) PRIMARY,
	type C(250) NOTNULL,
	name C(250) NOTNULL,
	theme C(250) NOTNULL
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
