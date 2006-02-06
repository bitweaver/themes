<?php 

// Initialization
require_once( '../bit_setup_inc.php' );
include_once( KERNEL_PKG_PATH.'BitBase.php' );

if (isset($_SERVER['HTTP_REFERER'])) {
	$orig_url = $_SERVER['HTTP_REFERER'];
} else {
	$orig_url = $bit_index;
}

if (isset($_GET['theme']) && $gBitSystem->getPreference('feature_user_theme') == 'y'){
	$new_theme = $_GET['theme'];
	if(isset($user) && $gBitSystem->getPreference('feature_user_preferences') == 'y' ) {  
		$gBitUser->storePreference('theme',$new_theme);
		setcookie('tiki-theme', '', time()-3600*24*30*12, $gBitSystem->getPreference('cookie_path'), $gBitSystem->getPreference('cookie_domain'));
	} else {
		setcookie('tiki-theme', $new_theme, time()+3600*24*30*12, $gBitSystem->getPreference('cookie_path'), $gBitSystem->getPreference('cookie_domain'));
	}
} else{
	setcookie('tiki-theme', '', time()-3600*24*30*12, $gBitSystem->getPreference('cookie_path'), $gBitSystem->getPreference('cookie_domain'));
}

header("location: $orig_url");
exit;
?>
