<?php
// $Header: /cvsroot/bitweaver/_bit_themes/admin/admin_custom_modules_inc.php,v 1.2 2010/02/08 21:27:26 wjames5 Exp $

require_once( '../../kernel/setup_inc.php' );
$feedback = array();

if( !empty( $_REQUEST['name'] )) {
	if( !empty( $_REQUEST['action'] )) {
		if( $_REQUEST['action'] == 'remove' ) {
			if( $gBitThemes->expungeCustomModule( $_REQUEST['name'] )) {
				$feedback['success'] = tra( 'The module was successsfully removed.' );
			} else {
				$feedback['error'] = $gBitThemes->mErrors;
			}
		} elseif( $_REQUEST['action'] == 'edit' ) {
			$gBitSmarty->assign( 'module', $gBitThemes->getCustomModule( $_REQUEST['name'] ));
		}
	} elseif( !empty( $_REQUEST['save'] )) {
		if( $gBitThemes->storeCustomModule( $_REQUEST )) {
			$feedback['success'] = tra( 'The module was successsfully stored.' );
		} else {
			$feedback['error'] = $gBitThemes->mErrors;
			$gBitSmarty->assign( 'module', $_REQUEST );
		}
	} elseif( !empty( $_REQUEST['preview'] )) {
		$gBitSmarty->assign( 'module', $_REQUEST );
	}
}

$gBitSmarty->assign( 'feedback', $feedback );
$gBitSmarty->assign( 'customModules', $gBitThemes->getCustomModuleList() );
?>
