<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_themes/admin/upgrades/2.0.0.php,v 1.2 2008/10/29 22:05:19 squareing Exp $
 */
global $gBitInstaller;

$infoHash = array(
	'package'      => THEMES_PKG_NAME,
	'version'      => str_replace( '.php', '', basename( __FILE__ )),
	'description'  => "Set core package version number.",
);

$gBitInstaller->registerPackageUpgrade( $infoHash );

$gBitInstaller->registerPackageDependencies( $infoHash, array(
	'liberty'   => array( 'min' => '2.1.0' ),
	'users'     => array( 'min' => '2.1.0' ),
	'kernel'    => array( 'min' => '2.0.0' ),
	'languages' => array( 'min' => '2.0.0' ),
));
?>
