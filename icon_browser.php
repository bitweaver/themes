<?php
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
$gBitSmarty->assign( 'defaultIconList', icon_fetcher( THEMES_PKG_PATH."icon_styles/".DEFAULT_ICON_STYLE."/" ));

if( $gBitSystem->isFeatureActive( 'site_icon_style' ) && $gBitSystem->getConfig( 'site_icon_style' ) != DEFAULT_ICON_STYLE ) {
	$gBitSmarty->assign( 'activeIconList', icon_fetcher( THEMES_PKG_PATH."icon_styles/".$gBitSystem->getConfig( 'site_icon_style' )."/" ));
}

$gBitSystem->display( 'bitpackage:themes/icon_browser.tpl', tra( 'Icon Listing' ) , array( 'display_mode' => 'display' ));

function icon_fetcher( $pStylePath ) {
	if( is_dir( $pStylePath."large" )) {
		$handle = opendir( $pStylePath."large" );
		while( FALSE !== ( $icon = readdir( $handle ))) {
			if( preg_match( "#\.png$#", $icon ) && !preg_match( "#^process-working\.#", $icon )) {
				$ret[str_replace( ".png", "", $icon )] = str_replace( ".png", "", $icon );
			}
		}
	}
	asort( $ret );
	return $ret;
}
?>