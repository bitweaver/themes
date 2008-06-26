<?php
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
	if( !empty( $package['installed'] ) && ( !empty( $package['activatable'] ) || !empty( $package['tables'] ) ) ) {
		if( $package['name'] == 'kernel' ) {
			$package['name'] = tra( 'Site Default' );
		}
		$hideColumns[strtolower( $key )] =  ucfirst( $package['name'] );
	}
}
asort( $hideColumns );
$gBitSmarty->assign( 'hideColumns', $hideColumns );

if( !empty( $_REQUEST['column_control'] )) {
	foreach( array_keys( $activeColumns ) as $item ) {
		simple_set_toggle( $item, THEMES_PKG_NAME );
	}

	// hideable areas
	$hideable = array( 'top', 'left', 'right', 'bottom' );

	// evaluate what columns to hide
	foreach( $hideable as $area ) {
		// packages
		foreach( array_keys( $hideColumns ) as $package ) {
			$pref = "{$package}_hide_{$area}_col";
			if( isset( $_REQUEST['package'][$pref] ) ) {
				$gBitSystem->storeConfig( $pref, 'y', THEMES_PKG_NAME );
			} else {
				// remove the setting from the db if it's not set
				$gBitSystem->storeConfig( $pref, NULL );
			}
		}

		// modes
		foreach( array_keys( $displayModes ) as $mode ) {
			$pref = "{$mode}_hide_{$area}_col";
			if( isset( $_REQUEST['mode'][$pref] ) ) {
				$gBitSystem->storeConfig( $pref, 'y', THEMES_PKG_NAME );
			} else {
				// remove the setting from the db if it's not set
				$gBitSystem->storeConfig( $pref, NULL );
			}
		}
	}
}

?>
