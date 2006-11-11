<?php 
//
// (c) 2006 bitweaver.org - GNU LGPL
//
require_once( '../../bit_setup_inc.php' );
require_once( KERNEL_PKG_PATH."simple_form_functions_lib.php" );

//$gBitSmarty->assign( 'loadDragDrop', TRUE );
//$gBitSystem->setOnloadScript('initDragDrop();');
$gBitSystem->verifyPermission( 'p_admin' );

$formMenuSettings = array(
	'site_top_bar' => array(
		'label' => 'Top bar menu',
		'note' => 'Here you can enable or disable the menubar at the top of the page (available in most themes). Before you disable this bar, please make sure you have some means of navigation set up to access at least the administration page.',
	),
	'site_top_bar_dropdown' => array(
		'label' => 'Dropdown menu',
		'note' => 'Use the CSS driven dropdown menus in the top bar. Compatibility and further reading can be found at <a class="external" href="http://www.htmldog.com/articles/suckerfish/dropdowns/">Suckerfish Dropdowns</a>.',
	),
	'site_hide_my_top_bar_link' => array(
		'label' => 'Hide "My" Link',
		'note' => 'Hide the <strong>My &lt;sitename&gt;</strong> link from users that are not logged in.',
	),
);
$gBitSmarty->assign( 'formMenuSettings',$formMenuSettings );

$formMenuJsSettings = array(
	'site_top_bar_js' => array(
		'label' => 'Enhance Dropdown with Javascript',
		'note' => 'This small javascript will delay the menu slightly making it easier to navigate. Also you can apply below effects. Please see <a class="external" href="http://www.twinhelix.com">TwinHelix</a> for details.',
	),
	'site_top_bar_js_fade' => array(
		'label' => 'Fade Effect',
		'note' => 'Fade in menu dropdown elements.',
	),
	'site_top_bar_js_swipe' => array(
		'label' => 'Swipe Effect',
		'note' => 'Sweep the menu from the top dwon.',
	),
	'site_top_bar_js_clip' => array(
		'label' => 'Clip Effect',
		'note' => 'Similar to the swipe effect.',
	),
);

if( !empty( $_REQUEST['menu_settings'] ) ) {
	foreach( array_keys( $formMenuSettings ) as $item ) {
		simple_set_toggle( $item, THEMES_PKG_NAME );
	}
	simple_set_value( 'site_menu_title', THEMES_PKG_NAME );
}

if( !empty( $_REQUEST['menu_js_settings'] ) ) {
	foreach( array_keys( $formMenuJsSettings ) as $item ) {
		simple_set_toggle( $item, THEMES_PKG_NAME );
	}
}

if( !empty( $_REQUEST['update_menus'] ) ) {
	foreach( array_keys( $gBitSystem->mAppMenu ) as $menuPackage ) {
		if( empty( $_REQUEST["menu_$menuPackage"] ) ) {
			// the package menu is off - store it off
			$gBitSystem->storeConfig( "menu_$menuPackage", 'n', THEMES_PKG_NAME );
		} elseif( $gBitSystem->getConfig( "menu_$menuPackage" ) == 'n' ) {
			// the package menu was off and now is on. Just delete the pref since on is the assumed state
			$gBitSystem->storeConfig( "menu_$menuPackage", NULL, THEMES_PKG_NAME );
		}

		if( !empty( $_REQUEST["{$menuPackage}_menu_text"] ) ) {
			// someone thinks that our default package names aren't good enough! HA!
			$gBitSystem->storeConfig( "{$menuPackage}_menu_text", $_REQUEST["{$menuPackage}_menu_text"], THEMES_PKG_NAME );
		}

		if( !empty( $_REQUEST["{$menuPackage}_menu_position"] ) ) {
			// someone thinks that our default package names aren't good enough! HA!
			$gBitSystem->storeConfig( "{$menuPackage}_menu_position", $_REQUEST["{$menuPackage}_menu_position"], THEMES_PKG_NAME );
		} else {
			$gBitSystem->storeConfig( "{$menuPackage}_menu_position", NULL, THEMES_PKG_NAME );
		}
	}

	// need to reload page to apply settings
	bit_redirect( THEMES_PKG_URL."admin/menus.php" );
}

if( $gBitSystem->isFeatureActive( 'site_top_bar_dropdown' ) ) {
	$gBitSmarty->assign( 'formMenuJsSettings',$formMenuJsSettings );
}

$gBitSystem->display( 'bitpackage:themes/admin_themes_menus.tpl', 'Themes Manager' );
?>
