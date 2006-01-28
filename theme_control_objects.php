<?php

// $Header: /cvsroot/bitweaver/_bit_themes/Attic/theme_control_objects.php,v 1.1.1.1.2.2 2006/01/28 09:19:22 squareing Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

include_once( THEMES_PKG_PATH.'theme_control_lib.php' );
include_once( CATEGORIES_PKG_PATH.'categ_lib.php');
include_once( FILEGALS_PKG_PATH.'filegal_lib.php' );
include_once( ARTICLES_PKG_PATH.'art_lib.php' );
include_once( BLOGS_PKG_PATH.'BitBlog.php' );

function correct_array(&$arr, $id, $name) {
	for ($i = 0; $i < count($arr); $i++) {
		$arr[$i]['obj_id'] = $arr[$i][$id];

		$arr[$i]['objName'] = $arr[$i][$name];
	}
}

$gBitSystem->verifyFeature( 'feature_theme_control' );
$gBitSystem->verifyPermission( 'bit_p_admin' );

$styles = $tcontrollib->getStyles();
$gBitSmarty->assign_by_ref('styles', $styles);

$find_objects = '';
$types = array(
	'image gallery',
	'file gallery',
	'forum',
	'blog',
	'wiki page',
	'faq',
	'quiz',
	'article'
);

$gBitSmarty->assign('types', $types);

if (!isset($_REQUEST['type']))
	$_REQUEST['type'] = 'wiki page';

$gBitSmarty->assign('type', $_REQUEST['type']);

switch ($_REQUEST['type']) {
case 'image gallery':
	$objects = $gBitSystem->list_galleries(0, -1, 'name_desc', 'admin', $find_objects);

	$gBitSmarty->assign_by_ref('objects', $objects["data"]);
	$objects = $objects['data'];
	correct_array($objects, 'gallery_id', 'name');
	break;

case 'file gallery':
	$objects = $filegallib->list_file_galleries(0, -1, 'name_desc', 'admin', $find_objects);

	$gBitSmarty->assign_by_ref('objects', $objects["data"]);
	$objects = $objects['data'];
	correct_array($objects, 'gallery_id', 'name');
	break;

case 'forum':
	$objects = $gBitSystem->list_forums(0, -1, 'name_asc', $find_objects);

	$gBitSmarty->assign_by_ref('objects', $objects["data"]);
	$objects = $objects['data'];
	correct_array($objects, 'forum_id', 'name');
	break;

case 'blog':
	$objects = $gBlog->list_blogs(0, -1, 'title_asc', $find_objects);

	$gBitSmarty->assign_by_ref('objects', $objects["data"]);
	$objects = $objects['data'];
	correct_array($objects, 'blog_id', 'title');
	break;

case 'wiki page':
	$objects = $gBitSystem->list_pages(0, -1, 'page_name_asc', $find_objects);

	$gBitSmarty->assign_by_ref('objects', $objects["data"]);
	$objects = $objects['data'];
	correct_array($objects, 'page_name', 'page_name');
	break;

case 'faq':
	$objects = $gBitSystem->list_faqs(0, -1, 'title_asc', $find_objects);

	$gBitSmarty->assign_by_ref('objects', $objects["data"]);
	$objects = $objects['data'];
	correct_array($objects, 'faq_id', 'title');
	break;

case 'quiz':
	$objects = $gBitSystem->list_quizzes(0, -1, 'name_asc', $find_objects);

	$gBitSmarty->assign_by_ref('objects', $objects["data"]);
	$objects = $objects['data'];
	correct_array($objects, 'quiz_id', 'name');
	break;

case 'article':
	$objects = $artlib->list_articles(0, -1, 'title_asc', $find_objects, '', $user);

	$gBitSmarty->assign_by_ref('objects', $objects["data"]);
	$objects = $objects['data'];
	correct_array($objects, 'article_id', 'title');
	break;

default:
	break;
}

$gBitSmarty->assign_by_ref('objects', $objects);

if (isset($_REQUEST['assign'])) {
	
	list($id, $name) = explode('|', $_REQUEST['objdata']);

	$tcontrollib->tc_assign_object($id, $_REQUEST['theme'], $_REQUEST['type'], $name);
}

if (isset($_REQUEST["delete"])) {
	
	foreach (array_keys($_REQUEST["obj"])as $obj) {
		$tcontrollib->tc_remove_object($obj);
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
$channels = $tcontrollib->tc_list_objects($_REQUEST['type'], $offset, $maxRecords, $sort_mode, $find);

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
$gBitSystem->display( 'bitpackage:themes/theme_control_objects.tpl');

?>
