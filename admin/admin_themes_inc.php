<?php
$themeSettings = array(
	'site_use_jscalendar' => array(
		'label' => 'Enable JSCalendar',
		'note' => 'If checked, a calendar popup allows for easily selecting a date using an appealing interface.',
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
		'note' => 'If enabled, javascript and CSS files will be concatenated into single files to reduce server requests. This is useful for webdesigners and developers. Please enable this feature on live sites.',
	),
	'themes_packed_js_css' => array(
		'label' => 'Packed CSS and JS',
		'note' => 'If enabled, javascript and CSS files will be reduced to their smallest possible size. This is useful for webdesigners and developers. Please enable this feature on live sites.',
	),
	'themes_disable_pkg_css' => array( 
		'label' => 'Disable All Package CSS',
		'note' => 'If checked, all css that is automatically included by packages will be disabled. If you want to include some of the package css it is recommended you copy that css to your theme css file.',
	),
);
$gBitSmarty->assign( 'themeSettings', $themeSettings );

if( !empty( $_REQUEST['change_prefs'] )) {
	$pref_simple_values = array(
		"site_biticon_display_style",
		"site_icon_size",
		"themes_use_msie_js_fix",
	);

	foreach( $pref_simple_values as $svitem ) {
		simple_set_value( $svitem, THEMES_PKG_NAME );
	}

	foreach( array_keys( $themeSettings ) as $toggle ) {
		simple_set_toggle( $toggle, THEMES_PKG_NAME );
	}

	// due to the packing / joining options, we will remove the cache and reload the page
	$gBitThemes->mThemeCache->expungeCache();
	bit_redirect( KERNEL_PKG_URL."admin/index.php?page=themes" );
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

// These numbers are intentionally off by 1 due to the way IE fixes name their js
$ieFixOptions = array(
	'' => tra( 'Off' ),
	'8' => tra( 'IE7 or older' ),
	'7' => tra( 'IE6 or older' ),
);
$gBitSmarty->assign( "ieFixOptions", $ieFixOptions );
?>
