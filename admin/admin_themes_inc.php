<?php
$themeSettings = array(
	'site_use_jscalendar' => array(
		'label' => 'Enable JSCalendar',
		'note' => 'JSCalendar is a javascript calendar popup that allows you to easily select a date using an easy to use and appealing interface.',
	),
	'themes_use_msie_png_hack' => array(
		'label' => 'MSIE png Hack',
		'note' => 'Microsoft Internet Explorer versions before version 7 can not display png transparency correctly. When you enable this javascript hack, png icons will be loaded in MSIE just like in other browsers but it will cause an additional strain on the browser. If this option is disabled, MSIE &lt; 7 browsers display matching gif icons. In general this option is not required if you are not planing on using png graphics with transparency on your site.',
	),
	'themes_collapsible_modules' => array(
		'label' => 'Collapsible Modules',
		'note' => 'This allows users to collapse modules by clicking on their titles. Can be useful if you use many modules.',
	),
//	'themes_edit_css' => array(
//		'label' => 'Edit Css',
//		'note' => 'Enables you to edit CSS files from within your browser to customise your site style according to your desires.',
//	),
	'site_disable_fat' => array(
		'label' => "Disable Fading",
		'note' => "Disable the fading effect used when displaying any success, warning or error messages.",
	),
	'site_disable_jstabs' => array(
		'label' => "Disable Javascript Tabs",
		'note' =>"If you have difficulties with the javascript tabs, of you don't like them, you can disable them here.",
	),
	'site_mods_req_admn_grp' => array(
		'label' => 'Module Groups Require Admin',
		'note' => 'If a module has group restrictions the module only shows for the administrator if the administrator is in the configured groups. Otherwise all modules show to administrators all the time.',
	),
);
$gBitSmarty->assign( 'themeSettings', $themeSettings );

if( !empty( $_REQUEST['change_prefs'] ) ) {
	$pref_simple_values = array(
		"site_biticon_display_style",
		"site_icon_size",
	);

	foreach( $pref_simple_values as $svitem ) {
		simple_set_value( $svitem, THEMES_PKG_NAME );
	}

	foreach( array_keys( $themeSettings ) as $toggle ) {
		simple_set_toggle( $toggle, THEMES_PKG_NAME );
	}
}

// set the options biticon takes
$biticon_display_options = array(
	'icon' => tra( 'Icon' ),
	'text' => tra( 'Text' ),
	'icon_text' => tra( 'Icon and Text' )
);
$gBitSmarty->assign( "biticon_display_options", $biticon_display_options );

$biticon_sizes = array(
	'small' => tra( 'Small' ),
	'large' => tra( 'Large' ),
);
$gBitSmarty->assign( "biticon_sizes", $biticon_sizes );
?>
