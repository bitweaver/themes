<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_function_replace_limit
 */
function smarty_modifier_replace_limit( $pHaystack, $pNeedle, $pReplace, $pLimit  ) {
    return preg_replace('/'.str_replace('/', '\/', $pNeedle).'/', $pReplace, $pHaystack, $pLimit); 
}
