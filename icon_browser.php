<?php
/**
 * @version $Header$
 *
 * Copyright (c) 2008 bitweaver
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
 *
 * @package themes
 * @subpackage functions
 */

/**
 * Setup
 */
require_once( "../kernel/setup_inc.php" );

if( !$gBitUser->isRegistered() ) {
	$gBitSystem->fatalError( "You need to be registered to view this page." );
}

$iconUsage = array(
	"dialog-ok"                => "Success / Accept",
	"document-open"            => "Import",
	"document-properties"      => "Configuration",
	"document-save-as"         => "Export",
	"edit-copy"                => "Copy",
	"edit-delete"              => "Delete",
	"go-down"                  => "Navigate down",
	"go-home"                  => "Home",
	"go-next"                  => "Navigate right / next",
	"go-previous"              => "Navigate left / previous",
	"go-up"                    => "Navigate up",
	"help-contents"            => "Help",
	"insert-object"            => "Insert",
	"mail-forward"             => "Mail send",
	"view-sort-ascending"      => "All things sorting",
	"view-sort-descending"     => "All things sorting",
	"window-close"             => "Close window",
	"accessories-text-editor"  => "Edit",
	"applications-accessories" => "Plugin",
	"preferences-system"       => "bitweaver administration",
	"emblem-default"           => "Current selection",
	"emblem-downloads"         => "Download",
	"emblem-favorite"          => "Favorite",
	"emblem-readonly"          => "Extra permissions set / Previously used as locked",
	"emblem-shared"            => "No permissions set or unlocked",
	"x-office-document"        => "Note",
	"x-office-presentation"    => "Slideshow",
	"folder"                   => "Folder",
	"dialog-error"             => "Error",
	"dialog-information"       => "Information",
);
$gBitSmarty->assign( 'iconUsage', $iconUsage );

$iconList = array();
$iconNames = array();
if( !empty( $_REQUEST['icon_style'] ) ) {
	$iconThemes = array( $_REQUEST['icon_style'] );
} else {
	$iconThemes = scandir( THEMES_PKG_PATH."icon_styles/" );
}

foreach( $iconThemes as $iconStyle ) {
	if( $icons = icon_fetcher( $iconStyle ) ) {
		$iconList[$iconStyle] = $icons;
		$iconNames = array_merge( $iconNames, $iconList[$iconStyle] );
	}
}

asort( $iconNames );
$gBitSmarty->assign( 'iconNames', $iconNames );
$gBitSmarty->assign( 'iconList', $iconList );

$gBitSystem->display( 'bitpackage:themes/icon_browser.tpl', tra( 'Icon Listing' ) , array( 'display_mode' => 'display' ));

function icon_fetcher( $pStyle = DEFAULT_ICON_STYLE ) {
	$ret = array();
	if( strpos( $pStyle, '.' ) !== 0 && $pStyle != 'CVS' ) {
		$stylePath = THEMES_PKG_PATH."icon_styles/".$pStyle;
		if( is_dir( $stylePath."/large" )) {
			$handle = opendir( $stylePath."/large" );
			while( FALSE !== ( $icon = readdir( $handle ))) {
				if( preg_match( "#\.png$#", $icon ) && !preg_match( "#^process-working\.#", $icon )) {
					$ret[str_replace( ".png", "", $icon )] = str_replace( ".png", "", $icon );
				}
			}
		}
	}
	ksort( $ret );
	return $ret;
}
?>
