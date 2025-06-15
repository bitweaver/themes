<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * required setup
 */
global $gBitSmarty;
$gBitSmarty->loadPlugin('smarty_shared_make_timestamp');

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     cal_date_format
 * Purpose:  format datestamps via strftime, (timezone adjusted to calendar specified timezone)
 * Input:    string: input date string
 *           format: strftime format for output
 *           default_date: default date if $string is empty
 * -------------------------------------------------------------
 */
function smarty_modifier_cal_date_format($string, $format = "%b %e, %Y", $default_date=null, $tra_format=null)
{
	$mDate = new BitDate();
	if ( $mDate->get_display_offset()) {
        $format = preg_replace("/ ?%Z/","",$format);
   	} else {
        $format = preg_replace("/%Z/","UTC",$format);
    }

	$disptime = strtotime( $string ); // Let PHP handle all conversion, TZ or not...

	global $gBitSystem, $gBitLanguage; //$gBitLanguage->mLanguage= $gBitSystem->getConfig("language", "en");
	if ($gBitSystem->getConfig("language", "en") != $gBitLanguage->mLanguage && $tra_format) {
		$format = $tra_format;
	}

	return $mDate->strftime($format, $disptime, true);
}

/* vim: set expandtab: */

?>
