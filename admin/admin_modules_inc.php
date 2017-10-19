<?php
// $Header$

require_once( '../../kernel/setup_inc.php' );

$formModuleFeatures = array(
//	'site_collapsible_modules' => array(
//		'label' => 'Collapsible Modules',
//		'note' => 'This allows users to collapse modules by clicking on their titles. Can be useful if you use many modules.',
//	),
//	'site_show_all_modules_always' => array(
//		'label' => 'Display modules to all teams always',
//		'note' => 'If you activate this, any modules you assign will be visible to all users, regardless of the settings on the layout page.<br />Hint: If you lose your login module, use /users/signin.php to login!',
//	),
//	'site_module_controls' => array(
//		'label' => 'Show Module Controls',
//		'note' => 'Displays module control buttons at the top of modules for easy placement by users.',
//	),
);
$gBitSmarty->assign( 'formModuleFeatures',$formModuleFeatures );

if( !empty( $_REQUEST['module_settings'] )) {
	foreach( array_keys( $formModuleFeatures ) as $item ) {
		simple_set_toggle( $item, THEMES_PKG_NAME );
	}
}
?>
