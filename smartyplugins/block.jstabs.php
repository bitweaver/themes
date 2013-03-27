<?php 
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {jstabs} block plugin
 *
 * Type:		block
 * Name:		jstabs
 * Input:		you can use {jstab tab=<tab number>} (staring with 0) to select a given tab
 *              or you can use the url to do so: page.php?jstab=<tab number>
 * Abstract:	Used to enclose a set of tabs
 */
function smarty_block_jstabs( $pParams, $pContent, &$gBitSmarty, $pRepeat ) {
	global $gBitSystem, $jsTabLinks;
	if( $pRepeat ){
		$jsTabLinks = array();
	} else {
		extract( $pParams );

		$tabId = !empty( $pParams['id'] ) ? $pParams['id'] : substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

		if( isset( $_REQUEST['jstab'] ) ) {
			// make sure we aren't passed any evil shit
			if( !isset( $tab ) && isset( $_REQUEST['jstab'] ) && preg_match( "!^\d+$!", $_REQUEST['jstab'] ) ) {
				$tab = $_REQUEST['jstab'];
			}
			$setupJs = '$(\'#'.$tabId.' a[href="#profile"]\').tab(\'show\');';
		} else {
			$setupJs = "$('#$tabId a:first').tab('show');";
		}
		

		$ret = '<ul class="nav nav-tabs" data-tabs="tabs" id="'.$tabId.'">';
		foreach( $jsTabLinks as $tabLink ) {
			$ret .= $tabLink;
		}
		$ret .= '</ul><div class="tab-content">'.$pContent.'</div>';
		$ret .= '<script type="text/javascript">/*<![CDATA[*/ $(\'#'.$tabId.' a\').click(function (e) { e.preventDefault(); $(this).tab(\'show\'); }); '.$setupJs .'/*]]>*/</script> ';


		$jsTabLinks = NULL;

		return $ret;
	}
}
?>
