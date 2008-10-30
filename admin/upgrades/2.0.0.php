<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_themes/admin/upgrades/2.0.0.php,v 1.3 2008/10/30 22:02:20 squareing Exp $
 */
global $gBitInstaller;

$infoHash = array(
	'package'      => THEMES_PKG_NAME,
	'version'      => str_replace( '.php', '', basename( __FILE__ )),
	'description'  => "Set core package version number.",
);
$gBitInstaller->registerPackageUpgrade( $infoHash );
?>
