<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_displaycomment
 */
function smarty_function_displaycomment( $pParams, &$pSmarty ) {

	if (!empty($pParams['comment'])) {
		$comment = $pParams['comment'];
		$pSmarty->assign('comment', $comment);
		if (empty($pParams['template'])) {
			$ret = $pSmarty->fetch('bitpackage:liberty/display_comment.tpl');
		} else {
			$ret = $pSmarty->fetch($pParams['template']);
		}
	}

	return $ret;
}

?>
