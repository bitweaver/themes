<?php
global $gBitSystem, $gBitSmarty;

$registerHash = array(
	'package_name' => 'themes',
	'package_path' => dirname( __FILE__ ).'/',
	'activatable' => FALSE,
);
$gBitSystem->registerPackage( $registerHash );
?>
