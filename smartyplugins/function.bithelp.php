<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * source elements
 */
require_once('function.booticon.php');
/**
* smarty_function_bithelp
*/
function smarty_function_bithelp($params, &$gBitSmarty) {
	global $gBitSystem, $gBitUser;
	$outstr = "";
	if( $gBitSystem->isFeatureActive('site_online_help') ){
		if($gBitUser->hasPermission( 'p_admin' )){
			$outstr .= "<a href=\"".KERNEL_PKG_URL."admin/index.php\">".smarty_function_booticon(array('ipackage'=>'icons', 'iname'=>'icon-cogs', 'iexplain'=>'Administration Menu'),$gBitSmarty)."</a> ";
		}
		if( $helpInfo = $gBitSmarty->getTemplateVars('TikiHelpInfo') ) {
			$outstr .= "<a href=\"".$helpInfo["URL"]."\" >".smarty_function_booticon(array('ipackage'=>'icons', 'iname'=>'icon-question-sign', 'iexplain'=>(empty($helpInfo["Desc"])?"help":$helpInfo["Desc"])),$gBitSmarty)."</a>";
		}
	}
	return $outstr;
}
