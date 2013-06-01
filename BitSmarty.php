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

require_once( dirname( __FILE__ ).'/smarty/libs/SmartyBC.class.php' );


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
class BitSmarty extends SmartyBC {

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
		$this->addPluginsDir( THEMES_PKG_PATH . "smartyplugins" );
		$this->register_prefilter( "add_link_ticket" );
		$this->error_reporting = E_ALL & ~E_NOTICE;

		global $permCheck;
		$permCheck = new PermissionCheck();
// SMARTY3	$this->register_object( 'perm', $permCheck, array(), TRUE, array( 'autoComplete' ));
		$this->assign_by_ref( 'perm', $permCheck );
	}

	function scanPackagePluginDirs() {
		global $gBitSystem;
		foreach( $gBitSystem->mPackages as &$packageHash ) {
			if( $packageHash['dir'] != THEMES_PKG_DIR && file_exists( $packageHash['path'].'smartyplugins' ) ) {
				$this->addPluginsDir( $packageHash['path'].'smartyplugins' );
			}
		}
	}

	function addPluginsDir( $dir ) {
		$this->plugins_dir = array_merge( array( $dir ), $this->plugins_dir );
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
							$moduleParams = $this->get_template_vars( 'moduleParams' );
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
		$to = '<form\\1><div style="display:inline"><input type="hidden" name="tk" value="{$gBitUser->mTicket}" /></div>';
		$pTplSource = preg_replace( $from, $to, $pTplSource );
		if( strpos( $pTplSource, '{form}' )) {
			$pTplSource = str_replace( '{form}', '{form}<div style="display:inline"><input type="hidden" name="tk" value="{$gBitUser->mTicket}" /></div>', $pTplSource );
		} elseif( strpos( $pTplSource, '{form ' ) ) {
			$from = '#\{form(\}| [^\}]*)\}#i';
			$to = '{form\\1}<div style="display:inline"><input type="hidden" name="tk" value="{$gBitUser->mTicket}" /></div>';
			$pTplSource = preg_replace( $from, $to, $pTplSource );
		}
	}

	return $pTplSource;
}
?>
