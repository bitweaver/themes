<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_themes/icon_browser.php,v 1.5 2009/10/01 13:45:49 wjames5 Exp $
 *
 * Copyright (c) 2008 bitweaver
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
 *
 * @package themes
 * @subpackage functions
 */

/**
 * Setup
 */
require_once( "../bit_setup_inc.php" );

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
$defaultIconList = icon_fetcher();
$gBitSmarty->assign( 'defaultIcons', $defaultIconList );

$activeIconList = array();
if( $gBitSystem->isFeatureActive( 'site_icon_style' ) && $gBitSystem->getConfig( 'site_icon_style' ) != DEFAULT_ICON_STYLE ) {
	$activeIconList = icon_fetcher( $gBitSystem->getConfig( 'site_icon_style' ));
	$gBitSmarty->assign( 'activeIcons', $activeIconList );
}

$iconNames = array_merge( $activeIconList, $defaultIconList );
asort( $iconNames );
$gBitSmarty->assign( 'iconNames', $iconNames );

$gBitSystem->display( 'bitpackage:themes/icon_browser.tpl', tra( 'Icon Listing' ) , array( 'display_mode' => 'display' ));

function icon_fetcher( $pStyle = DEFAULT_ICON_STYLE ) {
	$ret = array();
	$stylePath = THEMES_PKG_PATH."icon_styles/".$pStyle;
	if( is_dir( $stylePath."/large" )) {
		$handle = opendir( $stylePath."/large" );
		while( FALSE !== ( $icon = readdir( $handle ))) {
			if( preg_match( "#\.png$#", $icon ) && !preg_match( "#^process-working\.#", $icon )) {
				$ret[str_replace( ".png", "", $icon )] = str_replace( ".png", "", $icon );
			}
		}
	}
	ksort( $ret );
	return $ret;
}
?>
