<?php

// $Header: /cvsroot/bitweaver/_bit_themes/Attic/theme_control.php,v 1.1.1.1.2.2 2006/01/28 09:19:22 squareing Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

include_once( THEMES_PKG_PATH.'theme_control_lib.php' );
include_once( CATEGORIES_PKG_PATH.'categ_lib.php');

$gBitSystem->verifyFeature( 'feature_theme_control' );
$gBitSystem->verifyPermission( 'bit_p_admin' );

$categories = $categlib->get_all_categories();
$gBitSmarty->assign('categories', $categories);

$styles = &$tcontrollib->getStyles();
$gBitSmarty->assign_by_ref( 'styles', $styles );

if (isset($_REQUEST['assigcat'])) {
	if (isset($_REQUEST['category_id'])) {
		
		$tcontrollib->tc_assign_category($_REQUEST['category_id'], $_REQUEST['theme']);
	} else {
		$gBitSmarty->assign('msg', tra("Please create a category first"));

		$gBitSystem->display( 'error.tpl' );
		die;
	}
}

if (isset($_REQUEST["delete"])) {
	if (isset($_REQUEST["categ"])) {
		
		foreach (array_keys($_REQUEST["categ"])as $cat) {
			$tcontrollib->tc_remove_cat($cat);
		}
	}
}

if ( empty( $_REQUEST["sort_mode"] ) ) {
	$sort_mode = 'name_asc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}

if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
if (isset($_REQUEST['page'])) {
	$page = &$_REQUEST['page'];
	$offset = ($page - 1) * $maxRecords;
}
$gBitSmarty->assign_by_ref('offset', $offset);

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}

$gBitSmarty->assign('find', $find);

$gBitSmarty->assign_by_ref('sort_mode', $sort_mode);
$channels = $tcontrollib->tc_list_categories($offset, $maxRecords, $sort_mode, $find);

$cant_pages = ceil($channels["cant"] / $maxRecords);
$gBitSmarty->assign_by_ref('cant_pages', $cant_pages);
$gBitSmarty->assign('actual_page', 1 + ($offset / $maxRecords));

if ($channels["cant"] > ($offset + $maxRecords)) {
	$gBitSmarty->assign('next_offset', $offset + $maxRecords);
} else {
	$gBitSmarty->assign('next_offset', -1);
}

// If offset is > 0 then prev_offset
if ($offset > 0) {
	$gBitSmarty->assign('prev_offset', $offset - $maxRecords);
} else {
	$gBitSmarty->assign('prev_offset', -1);
}

$gBitSmarty->assign_by_ref('channels', $channels["data"]);

//$sections=Array('wiki','galleries','file_galleries','cms','blogs','forums','chat','categories','games','faqs','html_pages','quizzes','surveys','webmail','trackers','featured_links','directory','user_messages','newsreader','mybitweaver');



// Display the template
$gBitSystem->display( 'bitpackage:themes/theme_control.tpl');

?>
