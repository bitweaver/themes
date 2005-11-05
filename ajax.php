<?php
require_once( '../bit_setup_inc.php' );
include_once( LIBERTY_PKG_PATH.'attachment_browser.php' );
echo $_REQUEST['ajaxid'].'||'.$gBitSmarty->fetch( 'bitpackage:liberty/attachment_browser.tpl' );
?>
