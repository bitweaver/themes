<?php
/**
 * Smarty Library Inteface Class
 *
 * @package Smarty
 * @version $Header$
 *
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
 */

/**
 * required setup
 */

require_once( EXTERNAL_LIBS_PATH.'smarty/libs/Smarty.class.php' );


/**
 * PermissionCheck
 *
 * @package kernel
 */
class PermissionCheck {
	function check( $perm ) {
		global $gBitUser;
		return $gBitUser->hasPermission( $perm );
	}
}

/**
 * BitSmarty
 *
 * @package kernel
 */

class BitSmarty extends Smarty {

	protected $mCompileRsrc;

	/**
	 * BitSmarty initiation
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		global $smarty_force_compile, $smarty_debugging;
		parent::__construct();
		$this->mCompileRsrc = NULL;
		$this->config_dir = "configs/";
		// $this->caching = TRUE;
		$this->force_compile = //$smarty_force_compile;
		$this->debugging = isset($smarty_debugging) && $smarty_debugging;
		$this->assign( 'app_name', 'bitweaver' );
		$this->addPluginsDir( EXTERNAL_LIBS_PATH.'smarty/libs/sysplugins' );
		$this->addPluginsDir( THEMES_PKG_PATH . "smartyplugins" );
		$this->registerFilter('pre', "add_link_ticket" );
		$this->error_reporting = E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_DEPRECATED;
		$this->muteUndefinedOrNullWarnings();
		// Smarty 4: PHP built-in functions used as modifiers must be explicitly registered.
		// SmartyException is caught in case a function is already a Smarty built-in.
		// Non-string-first-arg functions: pass through directly.
		foreach( [
			'abs', 'array_keys', 'array_reverse', 'chr', 'count', 'current', 'sizeof',
			'date', 'extension_loaded', 'floatval', 'floor', 'mktime',
			'get_class', 'http_build_query', 'implode', 'intval',
			'is_a', 'is_array', 'is_int', 'is_null', 'is_numeric', 'is_object',
			'json_encode', 'key', 'method_exists', 'number_format', 'ordinalize', 'rand',
			'round', 'tra', 'unserialize',
		] as $fn ) {
			try {
				$this->registerPlugin( 'modifier', $fn, $fn );
			} catch( \SmartyException $e ) {}
		}
		// String modifiers: null-safe wrappers — PHP 8.1 rejects null for typed string params.
		foreach( [
			'addslashes'        => fn($s) => addslashes($s ?? ''),
			'basename'          => fn($s) => basename($s ?? ''),
			'dirname'           => fn($s) => dirname($s ?? ''),
			'html_entity_decode'=> fn($s) => html_entity_decode($s ?? ''),
			'htmlentities'      => fn($s) => htmlentities($s ?? ''),
			'preg_replace'      => fn($s, ...$a) => preg_replace($s ?? '', ...$a),
			'str_replace'       => fn($s, ...$a) => str_replace($s ?? '', ...$a),
			'strip_tags'        => fn($s) => strip_tags($s ?? ''),
			'stripslashes'      => fn($s) => stripslashes($s ?? ''),
			'stristr'           => fn($s, ...$a) => stristr($s ?? '', ...$a),
			'strlen'            => fn($s) => strlen($s ?? ''),
			'strpos'            => fn($s, ...$a) => strpos($s ?? '', ...$a),
			'strrpos'           => fn($s, ...$a) => strrpos($s ?? '', ...$a),
			'strstr'            => fn($s, ...$a) => strstr($s ?? '', ...$a),
			'strtolower'        => fn($s) => strtolower($s ?? ''),
			'strtotime'         => fn($s) => strtotime($s ?? ''),
			'strtoupper'        => fn($s) => strtoupper($s ?? ''),
			'strtr'             => fn($s, ...$a) => strtr($s ?? '', ...$a),
			'substr'            => fn($s, ...$a) => substr($s ?? '', ...$a),
			'trim'              => fn($s) => trim($s ?? ''),
			'ucfirst'           => fn($s) => ucfirst($s ?? ''),
			'ucwords'           => fn($s) => ucwords($s ?? ''),
			'urlencode'         => fn($s) => urlencode($s ?? ''),
		] as $name => $fn ) {
			try {
				$this->registerPlugin( 'modifier', $name, $fn );
			} catch( \SmartyException $e ) {}
		}
		// Filesystem modifiers: null-safe wrappers so {$path|file_exists} etc. don't warn on missing keys.
		foreach( [
			'file_exists' => fn($f) => $f !== null && file_exists($f),
			'filesize'    => fn($f) => $f !== null && file_exists($f) ? filesize($f) : false,
			'filemtime'   => fn($f) => $f !== null && file_exists($f) ? filemtime($f) : false,
			'is_readable' => fn($f) => $f !== null && is_readable($f),
			'is_dir'      => fn($f) => $f !== null && is_dir($f),
			'is_file'     => fn($f) => $f !== null && is_file($f),
		] as $name => $fn ) {
			try {
				$this->registerPlugin( 'modifier', $name, $fn );
			} catch( \SmartyException $e ) {}
		}
		$this->registerClass( 'BitBase', 'BitBase' );
		global $permCheck;
		$permCheck = new PermissionCheck();
// SMARTY3	$this->register_object( 'perm', $permCheck, array(), TRUE, array( 'autoComplete' ));
		$this->assignByRef( 'perm', $permCheck );
	}

	function scanPackagePluginDirs() {
		global $gBitSystem;
		foreach( $gBitSystem->mPackages as &$packageHash ) {
			if( $packageHash['dir'] != THEMES_PKG_DIR && file_exists( $packageHash['path'].'smartyplugins' ) ) {
				$this->addPluginsDir( $packageHash['path'].'smartyplugins' );
			}
		}
	}

    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false) {
		global $gBitSystem;

		if( strpos( $template, ':' )) {
			list( $resource, $location ) = explode( ':', $template);
			if( $resource == 'bitpackage' ) {
				list( $package, $tpl ) = explode( '/', $location );
				// exclude temp, as it contains nexus menus
				if( !$gBitSystem->isPackageActive( $package ) && $package != 'temp' ) {
					return '';
				}
			}
		}

		if( defined( 'TEMPLATE_DEBUG' ) && TEMPLATE_DEBUG == TRUE ) {
			echo "\n<!-- - - - {$template} - - - -->\n";
		}

		return parent::fetch($template, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
	}

	/**
	 * getModuleConfig
	 *
	 * @access public
	 * @return hash of config values set in sibling .cfg file
	 */
	function getModuleConfig( $pModuleRsrc ) {
		global $moduleConfig;
		$moduleConfig = array();
		$moduleConfigFile = str_replace( '.tpl', '.cfg', $pModuleRsrc );
		$this->includeSiblingFile( $moduleConfigFile );
		return $moduleConfig;
	}

	/**
	 * THE method to invoke if you want to be sure a tpl's sibling php file gets included if it exists. This
	 * should not need to be invoked from anywhere except within this class
	 *
	 * @param string $pFile file to be included, should be of the form "bitpackage:<packagename>/<templatename>"
	 * @return TRUE if a sibling php file was included
	 * @access private
	 */
	function includeSiblingFile( $pFile, $pIncludeVars=NULL ) {
		global $gBitThemes;
		$ret = FALSE;
		if( strpos( $pFile, ':' )) {
			list( $resource, $location ) = explode( ':', $pFile );
			if( $resource == 'bitpackage' ) {
				list( $package, $modFile ) = explode( '/', $location );
				$subdir = preg_match( '/mod_/', $modFile ) ? 'modules' : 'templates';
				if( preg_match('/mod_/', $modFile ) || preg_match( '/center_/', $modFile ) ) {
					global $gBitSystem;
					$path = constant( strtoupper( $package )."_PKG_PATH" );
					$includeFile = "$path$subdir/$modFile";
					if( file_exists( $includeFile )) {
						global $gBitSmarty, $gBitSystem, $gBitUser, $gQueryUserId, $moduleParams;
						$moduleParams = array();
						if( !empty( $pIncludeVars['module_params'] ) ) {
							// module_params were passed through via the {include},
							// e.g. {include file="bitpackage:foobar/mod_list_foo.tpl" module_params="user_id=`$gBitUser->mUserId`&sort_mode=created_desc"}
							$moduleParams['module_params'] = $gBitThemes->parseString( $pIncludeVars['module_params'] );
						} else {
							// Module Params were passed in from the template, like kernel/dynamic.tpl
							$moduleParams = $this->getTemplateVars( 'moduleParams' );
						}
						include( $includeFile );
						$ret = TRUE;
					}
				}
			}
		}
	}

	/**
	 * verifyCompileDir
	 *
	 * @access public
	 * @return void
	 */
	function verifyCompileDir() {
		global $gBitThemes, $gBitLanguage, $bitdomain;
		if( !defined( "TEMP_PKG_PATH" )) {
			$temp = BIT_ROOT_PATH . "temp/";
		} else {
			$temp = TEMP_PKG_PATH;
		}

		$endPath = $bitdomain.'/'.$gBitThemes->getStyle().'/'.$gBitLanguage->mLanguage;

		// Compile directory
		$compDir = $temp . "templates_c/$endPath";
		$compDir = str_replace( '//', '/', $compDir );
		$compDir = clean_file_path( $compDir );
		mkdir_p( $compDir );
		$this->setCompileDir( $compDir );

		// Cache directory
		$cacheDir = $temp . "cache/$endPath";
		$cacheDir = str_replace( '//', '/', $cacheDir );
		$cacheDir = clean_file_path( $cacheDir );
		mkdir_p( $cacheDir );
		$this->setCacheDir( $cacheDir );
	}
}

// Register a user-space or package function as a Smarty modifier.
// $gBitSmarty must already be initialised — a null here means a loading-order bug, not a silent skip.
function bitsmarty_register_function( $name, $callable ) {
	global $gBitSmarty;
	try {
		$gBitSmarty->registerPlugin( 'modifier', $name, $callable );
	} catch( \SmartyException $e ) {}
}

/**
 * add_link_ticket This will insert a ticket on all template URL's that have GET parameters.
 *
 * @param array $pTplSource source of template
 * @access public
 * @return ammended template source
 */
function add_link_ticket( $pTplSource ) {
	global $gBitUser;

	if( is_object( $gBitUser ) && $gBitUser->isRegistered() ) {
//		$from = '#href="(.*PKG_URL.*php)\?(.*)&(.*)"#i';
//		$to = 'href="\\1?\\2&amp;tk={$gBitUser->mTicket}&\\3"';
//		$pTplSource = preg_replace( $from, $to, $pTplSource );
		$from = '#<form([^>]*)>#i';
		// div tag is for stupid XHTML compliance.
		$to = '<form\\1><div style="display:inline"><input type="hidden" name="tk" value="{$gBitUser->mTicket}"></div>';
		$pTplSource = preg_replace( $from, $to, $pTplSource );
		if( strpos( $pTplSource, '{form}' )) {
			$pTplSource = str_replace( '{form}', '{form}<div style="display:inline"><input type="hidden" name="tk" value="{$gBitUser->mTicket}"></div>', $pTplSource );
		} elseif( strpos( $pTplSource, '{form ' ) ) {
			$from = '#\{form(\}| [^\}]*)\}#i';
			$to = '{form\\1}<div style="display:inline"><input type="hidden" name="tk" value="{$gBitUser->mTicket}"></div>';
			$pTplSource = preg_replace( $from, $to, $pTplSource );
		}
	}

	return $pTplSource;
}
?>
