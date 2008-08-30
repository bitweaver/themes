<?php
$themeSettings = array(
	'site_use_jscalendar' => array(
		'label' => 'Enable JSCalendar',
		'note' => 'If checked, a calendar popup allows for easily selecting a date using an appealing interface.',
	),
	'themes_use_msie_png_hack' => array(
		'label' => 'IE7-js',
		'note' => 'A Javascript library to make Microsoft\'s Internet Explorer behave like a standards-compliant browser. It fixes many HTML and CSS issues and makes transparent PNG work correctly under IE5 and IE6. It is also needed for CSS driven dropdown menus.',
	),
	'themes_collapsible_modules' => array(
		'label' => 'Collapsible modules',
		'note' => 'This allows users to collapse modules by clicking on their titles. Can be useful if you use many modules.',
	),
//	'themes_edit_css' => array(
//		'label' => 'Edit Css',
//		'note' => 'Enables you to edit CSS files from within your browser to customise your site style according to your desires.',
//	),
	'site_disable_fat' => array(
		'label' => "Disable fading",
		'note' => "If checked, success, warning or error messages display no fading effect anymore.",
	),
	'site_disable_jstabs' => array(
		'label' => "Disable Javascript tabs",
		'note' => "If checked, admin pages flow vertically, instead of displaying in a 'tabbed pages' interface.",
	),
	'site_fancy_zoom' => array(
		'label' => "Enable Fancy Zoom for images",
		'note' => "If checked, a Javascript zooms images when clicking on them. This will modify the behaviour when viewing most images. If you are running a commercial site, please read the license notice in /util/javascript/fancyzoom/js-global/FancyZoom.js.",
	),
	'site_mods_req_admn_grp' => array(
		'label' => 'Modules require membership',
		'note' => 'If enabled, modules with group restrictions require the administrator to be member of the group. If disabled, all modules are always visible to administrators.',
	),
	'themes_joined_js_css' => array(
		'label' => 'Joined CSS and JS',
		'note' => 'If enabled, javascript and CSS files will be concatenated into single files to reduce server requests. Warning: this can negatively affect server relative URLs in those files.',
	),
	'themes_packed_js_css' => array(
		'label' => 'Packed CSS and JS',
		'note' => 'If enabled, javascript and CSS files will be reduced to their smallest possible size. Warning: this can negatively affect some javascript files.',
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
