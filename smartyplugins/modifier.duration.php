<?php
/**
 * Smarty plugin modifier duration
 *
 * Type:     modifier
 * Name:     duration
 * Purpose:  formats a duration from seconds
 *
 * @package Smarty
 * @subpackage plugins
 */
 
/**
 * Function body.
 *
 * @param string $string Number of seconds
 * @return string in format days,hours,minutes,seconds
 */
function smarty_modifier_duration($string)
{
  $result=Array();
  if($string > 60*60*24) {
    $days = floor($string/(60*60*24));
    $result[]="$days days";
    $string = $string % (60*60*24);
  }
  if($string > 60*60) {
    $hours = floor($string/(60*60));
    $result[]="$hours hours";
    $string = $string % (60*60);
  }
  if($string > 60) {
    $mins = floor($string/(60));
    $result[]="$mins minutes";
    $string = $string % (60);
  }
  if($string > 0) {
    $result[]="$string seconds";
  }
  
  return implode(' ',$result);
}

?>
