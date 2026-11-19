<?php
/**
 * Theme Settings: kernel/admin/index.php?page=themes
 *
 * Restored after the custom CSS editor was removed; settings whose libraries
 * are gone (JSCalendar, FancyZoom, fading, JS tabs, local jQuery) are not
 * offered any more.
 *
 * @package themes
 * @subpackage admin
 */
global $gBitSystem, $gBitSmarty, $gBitThemes;

$gBitSystem->verifyPermission( 'p_themes_admin' );

$themeSettings = array(
	'themes_collapsible_modules' => array(
		'label' => 'Collapsible modules',
		'note' => 'This allows users to collapse modules by clicking on their titles. Can be useful if you use many modules.',
	),
	'site_mods_req_admn_grp' => array(
		'label' => 'Modules require membership',
		'note' => 'If enabled, modules with group/role restrictions require the administrator to be member of the group/role. If disabled, all modules are always visible to administrators.',
	),
	'themes_joined_js_css' => array(
		'label' => 'Joined CSS and JS',
		'note' => 'If enabled, javascript and CSS files will be concatenated into single files to reduce server requests.',
	),
	'themes_packed_js_css' => array(
		'label' => 'Packed CSS and JS',
		'note' => 'If enabled, javascript and CSS files will be reduced to their smallest possible size.',
	),
	'themes_disable_pkg_css' => array(
		'label' => 'Disable All Package CSS',
		'note' => 'If checked, all css that is automatically included by packages will be disabled. If you want to include some of the package css it is recommended you copy that css to your theme css file.',
	),
);
$gBitSmarty->assign( 'themeSettings', $themeSettings );

if( !empty( $_REQUEST['change_prefs'] )) {
	$pref_simple_values = array(
		'site_biticon_display_style',
		'site_icon_size',
		'default_icon_style',
	);

	foreach( $pref_simple_values as $svitem ) {
		simple_set_value( $svitem, THEMES_PKG_NAME );
	}

	foreach( array_keys( $themeSettings ) as $toggle ) {
		simple_set_toggle( $toggle, THEMES_PKG_NAME );
	}

	// due to the packing / joining options, we will remove the cache and reload the page
	$gBitThemes->mThemeCache->expungeCache();
	bit_redirect( KERNEL_PKG_URL.'admin/index.php?page=themes' );
}

// set the options biticon takes
$biticon_display_options = array(
	'icon' => tra( 'Icon' ),
	'text' => tra( 'Text' ),
	'icon_text' => tra( 'Icon and Text' )
);
$gBitSmarty->assign( 'biticon_display_options', $biticon_display_options );

// get the icon styles
$iconStyles = array();
foreach( $gBitThemes->getStylesList( ICONSETS_PATH, NULL, array( 'style_info' )) as $key => $style ) {
	$iconStyles[$key] = str_replace( '_', ' ', $style['style'] );
}
$gBitSmarty->assign( 'iconStyles', $iconStyles );

$biticon_sizes = array(
	'small' => tra( 'Small' ),
	'large' => tra( 'Large' ),
);
$gBitSmarty->assign( 'biticon_sizes', $biticon_sizes );
