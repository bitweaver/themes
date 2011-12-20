<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_cookie
 */
function smarty_function_tk($params, &$gBitSmarty) {
    global $gBitUser;
	print 'tk='.$gBitUser->mTicket;
}
?>
