<?php 
/**
 * @version $Header: /cvsroot/bitweaver/_bit_themes/switch_theme.php,v 1.8 2010/02/08 21:27:26 wjames5 Exp $
 * @package themes
 * @subpackage functions
 */

/**
 * Setup
 */
require_once( '../kernel/setup_inc.php' );
include_once( KERNEL_PKG_PATH.'BitBase.php' );

if (isset($_SERVER['HTTP_REFERER'])) {
	$orig_url = $_SERVER['HTTP_REFERER'];
} else {
	$orig_url = $bit_index;
}

if (isset($_GET['theme']) && $gBitSystem->getConfig('users_themes') == 'y'){
	$new_theme = $_GET['theme'];
	if(isset($user) && $gBitSystem->getConfig('users_preferences') == 'y' ) {  
		$gBitUser->storePreference('theme',$new_theme);
		setcookie('bit-theme', '', time()-3600*24*30*12, $gBitSystem->getConfig('cookie_path'), $gBitSystem->getConfig('cookie_domain'));
	} else {
		setcookie('bit-theme', $new_theme, time()+3600*24*30*12, $gBitSystem->getConfig('cookie_path'), $gBitSystem->getConfig('cookie_domain'));
	}
} else{
	setcookie('bit-theme', '', time()-3600*24*30*12, $gBitSystem->getConfig('cookie_path'), $gBitSystem->getConfig('cookie_domain'));
}

header("location: $orig_url");
exit;
?>
