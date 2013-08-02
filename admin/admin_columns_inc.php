<?php
$feedback = array();

// Columns
$activeColumns = array(
	'site_top_column' => array(
		'label' => 'Top Module Area',
		'note' => 'Check to enable the top module area site-wide.',
	),
	'site_right_column' => array(
		'label' => 'Right Module Area',
		'note' => 'Check to enable the right module area site-wide.',
	),
	'site_left_column' => array(
		'label' => 'Left Module Area',
		'note' => 'Check to enable the left module area site-wide.',
	),
	'site_bottom_column' => array(
		'label' => 'Bottom Module Area',
		'note' => 'Check to enable the bottom module area site-wide.',
	),
);
$gBitSmarty->assign( 'activeColumns', $activeColumns );

// Areas
$hideableAreas = array(
	'top'    => 'Top',
	'left'   => 'Left',
	'right'  => 'Right',
	'bottom' => 'Bottom',
);
$gBitSmarty->assign( 'hideableAreas', $hideableAreas );

// Display modes
$displayModes = array(
	"display" => "Display content",
	"list"    => "Display listings such as galleries",
	"edit"    => "Edit areas such as creating a wiki page",
	"upload"  => "Uploading files to a file or image gallery",
	"admin"   => "Package administration",
);
$gBitSmarty->assign( 'displayModes', $displayModes );

// hide columns in individual packages
foreach( $gBitSystem->mPackages as $key => $package ) {
	if( !empty( $package['installed'] ) ) {
		if( $package['name'] == 'kernel' ) {
			$package['name'] = tra( 'Site Default' );
		}
		$packageColumns[strtolower( $key )] =  ucfirst( $package['name'] );
	}
}
asort( $packageColumns );
$gBitSmarty->assign( 'packageColumns', $packageColumns );

// process the form
if( !empty( $_REQUEST['reset_columns'] )) {
	$gBitSystem->storeConfigMatch( "#_hide_(top|right|bottom|left)_col$#" );
	$feedback['success'] = tra( "All custom column settings have been reset." );
} elseif( !empty( $_REQUEST['column_control'] )) {

	foreach( array( 'layout-header', 'layout-maincontent', 'layout-footer' ) as $key ) {
		$gBitSystem->storeConfig( $key, $_REQUEST[$key] );
	}
	foreach( array_keys( $activeColumns ) as $item ) {
		simple_set_toggle( $item, THEMES_PKG_NAME );
	}

	// first we'll remove all stored column settings
	$gBitSystem->storeConfigMatch( "#_hide_(top|right|bottom|left)_col$#" );

	if( !empty( $_REQUEST['hide'] )) {
		foreach( array_keys( $_REQUEST['hide'] ) as $pref ) {
			$gBitSystem->storeConfig( $pref, 'y', THEMES_PKG_NAME );
		}
	}

	$feedback['success'] = tra( "The settings were successfully stored." );
}

$gBitSmarty->assign( 'feedback', $feedback );
?>
