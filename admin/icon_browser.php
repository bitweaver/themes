<?php
require_once( "../../bit_setup_inc.php" );

$gBitSystem->verifyPermission( 'p_admin' );

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

$handle = opendir( THEMES_PKG_PATH."icon_styles/tango/large" );
while( FALSE !== ( $icon = readdir( $handle ))) {
	if( preg_match( "#\.png$#", $icon ) && !preg_match( "#^process-working\.#", $icon )) {
		$iconList[] = str_replace( ".png", "", $icon );
	}
}
asort( $iconList );
$gBitSmarty->assign( 'iconList', $iconList );

$gBitSystem->display( 'bitpackage:themes/icon_browser.tpl', tra( 'Icon Listing' ) , array( 'display_mode' => 'admin' ));
?>
