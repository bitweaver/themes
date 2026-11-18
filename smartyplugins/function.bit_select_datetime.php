<?php
/**
 * Smarty plugin
 * 
 * 
 * 
 * @package Smarty
 * @subpackage plugins
 */

/**
 *  smarty_function_bit_select_datetime
 *
 *	Outputs a native <input type="datetime-local"> (or type="date" with showtime=false).
 *	The browser supplies the picker; the posted value is display time as
 *	YYYY-MM-DDTHH:MM (or YYYY-MM-DD), which strtotime() reads.
 *
 *	Parameters:
 *	name	The name of the inputfield. Use this to identify the date/datetime input from other inputs. (will be used for <input name="..., defaults to 'date'.
 *	showtime	defines whether you need a date or datetime picking method. Set to 'true' or 'false', defaults to 'true'.
 *	time	A UTC timestamp, or a display-time date string. Defaults to now.
 *
 *	Usage sample:
 *	<form action="edit.php" method="POST">
 *		{formlabel label="Start"}
 *		{forminput}
 *			{bit_select_datetime name="mydate2" time=$gContent->mInfo.start}
 *		{/forminput}
 *	</form>
 *
 *	Later, in edit.php, use this code to obtain the timestamp in UTC:
 *	$timestamp = $gBitSystem->mServerTimestamp->getUTCFromDisplayDate(_REQUEST['mydate2'])
 *
 */
function smarty_function_bit_select_datetime( $pParams, &$gBitSmarty ) {
	global $gBitSystem, $gBitUser;

	$name     = !empty( $pParams['name'] ) ? $pParams['name'] : 'date';
	$showtime = !isset( $pParams['showtime'] ) || ( $pParams['showtime'] !== 'false' && $pParams['showtime'] !== FALSE );
	$time     = isset( $pParams['time'] ) && $pParams['time'] !== '' ? $pParams['time'] : NULL;

	// value is display (not UTC) time: a timestamp is converted, a date string is taken as is
	if( $time === NULL ) {
		$stamp = $gBitSystem->mServerTimestamp->getDisplayDateFromUTC( time() );
	} elseif( is_numeric( $time )) {
		$stamp = $gBitSystem->mServerTimestamp->getDisplayDateFromUTC( (int)$time );
	} else {
		$stamp = strtotime( $time );
	}
	$value = $stamp ? date( $showtime ? 'Y-m-d\\TH:i' : 'Y-m-d', $stamp ) : '';

	$id = preg_replace( '/[^A-Za-z0-9_-]/', '_', $name ).'_id';
	return '<input type="'.( $showtime ? 'datetime-local' : 'date' ).'" class="form-control" style="width:auto;display:inline-block" name="'.htmlspecialchars( $name ).'" id="'.$id.'" value="'.$value.'" /> ('.htmlspecialchars( (string)$gBitUser->getPreference( 'site_display_utc' )).")\n";
}
?>
