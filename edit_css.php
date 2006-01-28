<?php

// $Header: /cvsroot/bitweaver/_bit_themes/edit_css.php,v 1.1.1.1.2.2 2006/01/28 09:19:22 squareing Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// $Id: edit_css.php,v 1.1.1.1.2.2 2006/01/28 09:19:22 squareing Exp $
include_once( '../bit_setup_inc.php' );
include_once( USERS_PKG_PATH.'BitUser.php' );
include_once( THEMES_PKG_PATH.'css_lib.php' );
include_once( THEMES_PKG_PATH.'theme_control_lib.php' );

//pvd($_REQUEST);
/************************************
**** File Management Functions
****
*************************************/

function delete($dir, $pattern = "*.*")
{
	$deleted = false;
    $pattern = str_replace(array("\*","\?"), array(".*","."), preg_quote($pattern));
    if (substr($dir,-1) != "/") $dir.= "/";
    if (is_dir($dir)) {
    	$d = opendir($dir);
        while ($file = readdir($d)) {
            if (is_file($dir.$file) && ereg("^".$pattern."$", $file)){
                if (unlink($dir.$file))    
                	$deleted[] = $file;
            }
        }
        closedir($d);
        return $deleted;
    }
    else return 0;
}

// it copies $wf to $wto
function copy_dirs($wf, $wto)
{
   if (!file_exists($wto))
   {
       mkdir($wto, 0777);
   }
   $arr=ls_a($wf);
   foreach ($arr as $fn)
   {
       if($fn)
       {
           $fl=$wf."/".$fn;
           $flto=$wto."/".$fn;
           if(is_dir($fl)) copy_dirs($fl, $flto);
           else // begin 2nd improvement
           {
               @copy($fl, $flto);
               chmod($flto, 0666);
           } // end 2nd improvement
       }
   }
}

// get an array of filesnames in the given directory
function ls_a($wh)
{
   if ($handle = opendir($wh))
   {
       while (false !== ($file = readdir($handle)))
       {
           if ($file !== "." && $file !== ".." )
           {
               if(!isset($files)) $files=$file;
               else $files = $file."\r\n".$files;
           }
       }
       closedir($handle);
   }
   $arr=explode("\r\n", $files);
   return $arr;
} 
/**************************************
***** End File Management Functions
*****
***************************************/

$gBitSystem->verifyFeature( 'feature_editcss' );
$gBitSystem->verifyPermission( 'bit_p_create_css' );

$customCSSPath = $gBitUser->getStoragePath( NULL,$gBitUser->mUserId );	// Path to this user's storage directory
$customCSSFile = $customCSSPath.'custom.css';	// Path to this user's custom stylesheet
$customCSSImageURL = $gBitUser->getStorageURL( NULL,$gBitUser->mUserId ).'/images/';
$gBitSmarty->assign_by_ref('customCSSImageURL',$customCSSImageURL);			
// Create a custom.css for this user if they do not already have one
if (!file_exists($customCSSFile)) {
	if (!copy(THEMES_PKG_PATH.'/styles/basic/basic.css', $customCSSFile)) {
		$gBitSmarty->assign('msg', tra("Unable to create a custom CSS file for you!"));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
}

// Action Responses
if (isset($_REQUEST["fSaveCSS"])and $_REQUEST["fSaveCSS"]) {
	// Save any changes the user made to their CSS
	$fp = fopen($customCSSFile, "w");

	if (!$fp) {
		$gBitSmarty->assign('msg', tra("You dont have permission to write the style sheet"));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
	
	fwrite($fp, $_REQUEST["textData"]);
	fclose ($fp);
	$successMsg = "CSS Updated and Saved";
} elseif (isset($_REQUEST["fCancelCSS"]) && $_REQUEST['fCancelCSS']) {
	// Cancel (e.g. do nothing)
	$successMsg = "Changes have been cancelled";
} elseif (isset($_REQUEST['fResetCSS'])) {
	// Reset CSS (e.g. copy an existing style as a base for their custom style)
	$resetStyle = $_REQUEST['resetStyle'];
	$cssData = $css_lib->load_css2_file(THEMES_PKG_PATH."styles/$resetStyle/$resetStyle.css");
	if (file_exists($customCSSPath.'/images')) {
		delete($customCSSPath.'/images/', '*.*');
	}
	if (file_exists(THEMES_PKG_PATH."styles/$resetStyle/images")) {
		copy_dirs("styles/$resetStyle/images", $customCSSPath.'/images/');				
	}
			
	$fp = fopen($customCSSFile, "w");

	if (!$fp) {
		$gBitSmarty->assign('msg', tra("You dont have permission to write the style sheet"));
		$gBitSystem->display( 'error.tpl' );
		die;
	}
	
	fwrite($fp, $cssData);
	fclose ($fp);
	$successMsg = "Your CSS has been reset to the $resetStyle theme.";
} elseif (isset($_REQUEST['fUpload'])) {
	// User has uploaded an image to use in their custom theme
	//print('You uploaded: '.$_FILES['fImgUpload']['name']);
	print(strtoupper($_FILES['fImgUpload']['name']));
	if (!ereg(".JPG$|.PNG$|.GIF$|.BMP$",strtoupper($_FILES['fImgUpload']['name']))) {
		$errorMsg = "Your image must be one of the following types: .jpg, .png, .gif, .bmp";
	} else {
		if ($_FILES['fImgUpload']['error'] == UPLOAD_ERR_OK && copy($_FILES['fImgUpload']['tmp_name'], $customCSSPath.'/images/'.$_FILES['fImgUpload']['name'])) {
			$successMsg = $_FILES['fImgUpload']['name']." successfully added.";
		}
		else {
			$errorMsg = "There was a problem uploading your image.";
		}
	}
} elseif (isset($_REQUEST['fDeleteImg'])) {
	// Delete one of the images in this user's storage directory
	$imgName = $customCSSPath.'/images/'.$_REQUEST['fDeleteImg'];
	//print("imgname: $imgName");
	if (file_exists($imgName)) {
		unlink($imgName);
		$successMsg = $_REQUEST['fDeleteImg']." successfully deleted";
	} else {
		$errorMsg = $_REQUEST['fDeleteImg']." does not exists!";
	}	
} else {
	$action = 'edit';
}


// Get the list of themes the user can choose to derive from (aka Reset to)
$styles = &$tcontrollib->getStyles( NULL, FALSE, FALSE );
$gBitSmarty->assign_by_ref( 'styles', $styles );
$assignStyle = 'basic';
$gBitSmarty->assign_by_ref( 'assignStyle', $assignStyle);


// Read in this user's custom.css to display in the textarea
$lines = file($customCSSFile);
$data = '';
foreach ($lines as $line) {
	$data .= $line;
} 

$gBitSmarty->assign('data', $data);
if (isset($successMsg)) 
	$gBitSmarty->assign('successMsg',$successMsg);
if (isset($errorMsg))
	$gBitSmarty->assign('errorMsg', $errorMsg);

// Get the list of images used by this user's custom theme
$imageList = ls_a($customCSSPath.'images/');
$themeImages = array();
foreach ($imageList as $image) {
	if (ereg(".JPG$|.PNG$|.GIF$|.BMP$",strtoupper($image))) {
		$themeImages[] = $image;
	}
}

$gBitSmarty->assign_by_ref('themeImages',$themeImages);	

$gBitSystem->display( 'bitpackage:themes/edit_css.tpl');

?>
