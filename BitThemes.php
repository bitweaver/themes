<?php
/**
 * @package themes
 */

/**
 * Set-up
 */
require_once( KERNEL_PKG_PATH.'BitCache.php' );
/**
 * BitThemes
 *
 * @package themes
 * @uses BitBase
 */
class BitThemes extends BitBase {
	// Array that contains a full description of the current layout
	var $mLayout = array();

	// contains the currently active style
	var $mStyle;

	// an array with style information
	var $mStyles = array();

	// Ajax libraries needed by current Ajax framework (MochiKit libs, etc.)
	var $mAjaxLibs = array();

	// Auxiliary Javascript and Css Files
	var $mAuxFiles = array(
		'js'  => array(),
		'css' => array()
	);

	// Raw Javascript and Css Files
	var $mRawFiles = array(
		'js'  => array(),
		'css' => array()
	);

	// Display Mode
	var $mDisplayMode;

	// When all modules are loaded they are loaded here
	var $mModules = array();


	/**
	 * Initiate class
	 *
	 * @return void
	 */
	function __construct() {
		parent::__construct();

		// start up caching engine
		$this->mThemeCache = new BitCache( 'themes', TRUE );
	}


	// {{{ =================== Styles ====================
	/**
	 * load up style related information that must be
	 * loaded before template rendering begins
	 *
	 * @note this is a interim method as we continue sorting
	 * out the optimal order of operations for rendering
	 * pages. there was some conflict between rendering
	 * module templates and loading styles, where some
	 * style information needs to be loaded before the templates
	 * are rendered, and some such as packing javascript and css
	 * should happen after
	 *
	 * @see BitSystem::preDisplay
	 */
	function preLoadStyle(){
		// define style url and path
		if( !defined( 'THEMES_STYLE_URL' ) ) {
			define( 'THEMES_STYLE_URL', $this->getStyleUrl() );
		}
		if( !defined( 'THEMES_STYLE_PATH' ) ) {
			define( 'THEMES_STYLE_PATH', $this->getStylePath() );
		}
	}


	/*
	 * load up all style related information
	 * populates mStyle and mStyles
	 *
	 * @access public
	 * @return void
	 */
	function loadStyle() {
		global $gBitSystem;
		// load default css files
		if( empty( $this->mStyles['styleSheet'] )) {
			$this->mStyles['styleSheet'] = $this->getStyleCssFile( NULL, TRUE );
		}

		// load tpl files that need to be included
		$this->loadTplFiles( "header_inc" );
		$this->loadTplFiles( "footer_inc" );

		// join javascript files that have been loaded
		$this->mStyles['joined_javascript'] = $this->joinAuxFiles( 'js' );

		// layout is called as the viry first, package css is around pos 300 and theme / browser are called last
		// css inserted in <pkg>/header_inc.tpl is called before these files since these are inserted last
		$this->loadCss( $this->getLayoutCssFile(),       TRUE, 1,	TRUE, TRUE );
		$this->loadCss( $this->getStyleCssFile(),        TRUE, 998,	TRUE, TRUE );
		$this->loadCss( $this->getBrowserStyleCssFile(), TRUE, 999,	TRUE, TRUE );
		// check for customized CSS file
		if( file_exists( CONFIG_PKG_PATH.'css/config.css' ) ) {
			$this->loadCss( CONFIG_PKG_PATH.'css/config.css' );
		}
		$this->mStyles['joined_css'] = $this->joinAuxFiles( 'css' );
	}

	/**
	 * figure out the current style
	 *
	 * @param string $ pScanFile file to be looked for
	 * @return none
	 * @access public
	 */
	function getStyle() {
		global $gBitSystem;
		if( empty( $this->mStyle )) {
			$this->mStyle = $gBitSystem->getConfig( 'style' );
		}
		return $this->mStyle;
	}

	/**
	 * figure out the current style
	 *
	 * @param string $ pScanFile file to be looked for
	 * @return none
	 * @access public
	 */
	function setStyle( $pStyle ) {
		global $gBitSmarty;
		$this->mStyle = $pStyle;
		$gBitSmarty->assign( 'style', $pStyle );
	}

	/**
	 * figure out the current style
	 *
	 * @param string $ pScanFile file to be looked for
	 * @return none
	 * @access public
	 */
	function getStyleCssFile( $pStyle = NULL, $pUrl = FALSE ) {
		global $gBitSystem;
		if( empty( $pStyle )) {
			$pStyle = $this->getStyle();
		}
		$ret = '';

		if( $pUrl ) {
			$base = $this->getStyleUrl();
		} else {
			$base = $this->getStylePath();
		}

		if( $gBitSystem->getConfig( 'style_variation' ) && is_readable( $this->getStylePath().'alternate/'.$gBitSystem->getConfig( 'style_variation' ).'.css' )) {
			$ret = $base.'alternate/'.$gBitSystem->getConfig( 'style_variation' ).'.css';
		} elseif( is_readable( $this->getStylePath().$pStyle.'.css' )) {
			$ret = $base.$pStyle.'.css';
		}
		return $ret;
	}

	/**
	 * get browser specific css file
	 *
	 * @param none
	 * @return path to browser specific css file
	 * @access public
	 */
	function getBrowserStyleCssFile( $pUrl = FALSE ) {
		global $gSniffer;

		if( $pUrl ) {
			$base = $this->getStyleUrl();
		} else {
			$base = $this->getStylePath();
		}
		$subpath = $this->getStyle().'_'.$gSniffer->property( 'browser' );

		// Allow us to split by major version with a fallback for others
		if( file_exists( $this->getStylePath().$subpath.$gSniffer->property( 'maj_ver' ).'.css' )) {
			$ret = $base.$subpath.$gSniffer->property( 'maj_ver' ).'.css';
		} elseif( file_exists( $this->getStylePath().$subpath.'.css' )) {
			$ret = $base.$subpath.'.css';
		}
		return !empty( $ret ) ? $ret : NULL;
	}

	/**
	 * get browser specific css file
	 *
	 * @param none
	 * @return path to browser specific css file
	 * @access public
	 */
	function getLayoutCssFile() {
		global $gBitSystem;
		if( $gBitSystem->isFeatureActive( 'site_style_layout' )) {
			$ret = realpath( THEMES_PKG_PATH."layouts/".$gBitSystem->getConfig( 'site_style_layout' ).".css" );
		}
		return !empty( $ret ) ? $ret : NULL;
	}

	/**
	 * figure out the current style URL
	 *
	 * @param string $ pScanFile file to be looked for
	 * @return none
	 * @access public
	 */
	function getStyleUrl( $pStyle = NULL ) {
		if( empty( $pStyle )) {
			$pStyle = $this->getStyle();
		}
		return CONFIG_PKG_URL.'themes/'.$pStyle.'/';
	}

	/**
	 * figure out the current style URL
	 *
	 * @param string $ pScanFile file to be looked for
	 * @return none
	 * @access public
	 */
	function getStylePath( $pStyle = NULL ) {
		if( empty( $pStyle )) {
			$pStyle = $this->getStyle();
		}
		return CONFIG_PKG_PATH.'themes/'.$pStyle.'/';
	}

	/**
	 * getStyles
	 *
	 * @param array $pDir
	 * @param array $pNullOption
	 * @param array $bIncludeCustom
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function getStyles( $pDir = NULL, $pNullOption = NULL, $bIncludeCustom = FALSE ) {
		global $gBitSystem, $gBitUser;

		if( empty( $pDir )) {
			$pDir = CONFIG_PKG_PATH.'themes/';
		}
		$ret = array();

		if( !empty( $pNullOption )) {
			$ret[] = '';
		}

		if( is_dir( $pDir )) {
			$h = opendir( $pDir );
			while( $file = readdir( $h )) {
				if ( is_dir( $pDir."$file" ) && ( $file != '.' && $file != '..' && $file != 'CVS' && $file != 'slideshows' && $file != 'blank' )) {
					$ret[] = $file;
				}
			}
			closedir( $h );
		}

		if( $bIncludeCustom && $gBitSystem->getConfig( 'themes_edit_css' )) {
			// Include the users custom css if they have created one
			$customCSSPath = $gBitUser->getStoragePath( NULL,$gBitUser->mUserId );
			$customCSSFile = $customCSSPath.'custom.css';

			if (file_exists($customCSSFile)) {
				$ret[] = 'custom';
			}
		}

		if( count( $ret )) {
			sort( $ret );
		}

		return $ret;
	}

	/**
	 * getStyleLayouts
	 *
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function getStyleLayouts() {
		$ret = array();

		if( is_dir( THEMES_PKG_PATH.'layouts/' )) {
			$h = opendir( THEMES_PKG_PATH.'layouts/' );
			// collect all layouts
			while( FALSE !== ( $file = readdir( $h ))) {
				if ( !preg_match( "/^\./", $file )) {
					$ret[substr( $file, 0, ( strrpos( $file, '.' )))][substr( $file, ( strrpos( $file, '.' ) + 1 ))] = $file;
				}
			}
			closedir( $h );

			// weed out any files that don't have a css file associated with them
			foreach( $ret as $key => $layout ) {
				if( empty( $layout['css'] )) {
					unset( $ret[$key] );
				}
			}

			ksort( $ret );
		}
		return $ret;
	}

	/**
	* @param $pSubDirs a subdirectory to scan as well - you can pass in multiple dirs using an array
	 *
	 * @param array $pDir
	 * @param array $pNullOption
	 * @param array $pSubDirs
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function getStylesList( $pDir = NULL, $pNullOption = NULL, $pSubDirs = NULL ) {
		global $gBitSystem;

		$ret = array();

		if( empty( $pSubDirs )) {
			$subDirs[] = array( '' );
		} elseif( !is_array( $pSubDirs )) {
			$subDirs[] = $pSubDirs;
		} else {
			$subDirs = $pSubDirs;
		}

		if( empty( $pDir )) {
			$pDir = CONFIG_PKG_PATH.'themes/';
		}

		if( !empty( $pNullOption )) {
			$ret[] = '';
		}

		// open directories
		if( is_dir( $pDir )) {
			$h = opendir( $pDir );
			// cycle through files / dirs
			while( FALSE !== ( $file = readdir( $h ))) {
				if ( is_dir( $pDir.$file ) && ( $file != '.' && $file != '..' && $file != 'CVS' && $file != 'slideshows' && $file != 'blank' )) {
					$ret[$file]['style'] = $file;
					// check if we want to have a look in any subdirs
					foreach( $subDirs as $dir ) {
						if( is_dir( $infoDir = $pDir.$file.'/'.$dir.'/' )) {
							$dh = opendir( $infoDir );
							// cycle through files / dirs
							while( FALSE !== ( $f = readdir( $dh ))) {
								if( is_readable( $infoDir.$f ) && ( $f != '.' &&  $f != '..' &&  $f != 'CVS' )) {
									$ret[$file][$dir][preg_replace( "/\..*/", "", $f )] = CONFIG_PKG_URL.basename( dirname( dirname( $infoDir ))).'/'.$file.'/'.$dir.'/'.$f;

									if( preg_match( "/\.htm$/", $f )) {
										$fh = fopen( $infoDir.$f, "r" );
										$ret[$file][$dir][preg_replace( "/\.htm$/", "", $f )] = fread( $fh, filesize( $infoDir.$f ));
										fclose( $fh );
									}
								}
							}
							// sort the returned items
							@ksort( $ret[$file][$dir] );
							closedir( $dh );
						}
					}
				}
			}
			closedir( $h );
		}

		if( count( $ret )) {
			ksort( $ret );
		}

		return $ret;
	}

	/**
	 * get the icon cache path
	 *
	 * @access public
	 * @return absolute path on where the system should store it's icons
	 */
	function getIconCachePath() {
		global $gSniffer, $gBitSystem, $gBitLanguage;

		// use bitweaver version as dir in case there has been changes since the last version
		$version = $gBitSystem->getBitVersion( FALSE );

		// some browsers need special treatment due to different biticon feed.
		if( $gSniffer->_browser_info['browser'] == 'ie' ) {
			$browser = $gSniffer->_browser_info['browser'].$gSniffer->_browser_info['maj_ver'];
		} else {
			$browser = 'default';
		}

		$cachedir = TEMP_PKG_PATH.'themes/biticon/'.$version.'/'.$gBitSystem->getConfig( 'site_icon_style', DEFAULT_ICON_STYLE ).'/'.$gBitLanguage->getLanguage().'/'.$browser.'/';
		if( !is_dir( $cachedir )) {
			mkdir_p( $cachedir );
		}
		return $cachedir;
	}


	// }}}
	// {{{ =================== Layout ====================
	/**
	 * load current layout into mLayout
	 *
	 * @param  $pParamHash
	 * @return none
	 * @access public
	 */
	function loadLayout( $pParamHash = NULL ) {
		global $gBitSystem;
		if( empty( $this->mLayout ) || !count( $this->mLayout )){
			$this->mLayout = $this->getLayout( $pParamHash );

			/**
			 * this needs to occur after loading the layout to ensure that we don't distrub the fallback process during layout loading
			 * we can disable clumns using various criteria:
			 *     <package>_hide_<area>_col
			 *     <display_mode>_hide_<area>_col
			 *     <package>_<display_mode>_hide_<area>_col
			 */
			$areas = array( 't' => 'top', 'l' => 'left', 'r' => 'right', 'b' => 'bottom' );
			foreach( $areas as $layout => $area ) {
				if(
					$gBitSystem->isFeatureActive( "{$this->mDisplayMode}_hide_{$area}_col" ) ||
					$gBitSystem->isFeatureActive( "{$gBitSystem->mActivePackage}_hide_{$area}_col" ) ||
					$gBitSystem->isFeatureActive( "{$gBitSystem->mActivePackage}_{$this->mDisplayMode}_hide_{$area}_col" )
				) {
					unset( $this->mLayout[$layout] );
				}
			}
		}
	}

	/**
	 * get the current layout from the database, layouts are fetched in this order in this order until one is successfully loaded: 'layout', 'fallback_layout', ACTIVE_PACKGE, DEFAULT_PACKAGE"
	 *
	 * @param array $pParamHash
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function getLayout( $pParamHash = NULL ) {
		global $gCenterPieces, $gBitUser, $gBitSystem;
		$ret = array( 'l' => NULL, 'c' => NULL, 'r' => NULL );

		$layouts =  array();
		if( !empty( $pParamHash['layout'] )) {
			$layouts[] = $pParamHash['layout'];
		}
		if( !empty( $pParamHash['fallback_layout'] )) {
			$layouts[] = $pParamHash['fallback_layout'];
		}
		$layouts[] = ACTIVE_PACKAGE;
		$layouts[] = DEFAULT_PACKAGE;

		foreach( $layouts AS $l ) {
			$query =   "SELECT tl.*
						FROM `".BIT_DB_PREFIX."themes_layouts` tl
						WHERE  tl.`layout`=? ORDER BY ".$this->mDb->convertSortmode( "pos_asc" );

			$result = $this->mDb->query( $query, array( $l ) );
			if( $result && $result->RecordCount() ) {
				break;
			}
		}
		if( !empty( $result ) && $result->RecordCount() ) {
			$row = $result->fetchRow();
			// Check to see if we have ACTIVE_PACKAGE modules at the top of the results
			if( isset( $row['layout'] ) && ( $row['layout'] != DEFAULT_PACKAGE ) && ( ACTIVE_PACKAGE != DEFAULT_PACKAGE )) {
				$skipDefaults = TRUE;
			} else {
				$skipDefaults = FALSE;
			}

			if ( !is_array( $gCenterPieces ) ){
				$gCenterPieces = array();
			}
			while( $row ) {
				if( $skipDefaults && $row['layout'] == DEFAULT_PACKAGE ) {
					// we're done! we've got all the non-DEFAULT_PACKAGE modules
					break;
				}

				// transform groups to managable array
				if( empty( $row["groups"] )) {
					// default is that module is visible at all times
					$row["visible"] = TRUE;
					$row["module_groups"] = array();
				} else {
					$row['module_groups'] = $this->parseGroups( $row['groups'] );

					if( $gBitUser->isAdmin() ) {
						if ( $gBitSystem->isFeatureActive('site_mods_req_admn_grp') ) {
							if( in_array(1, $row['module_groups']) ) {
								$row['visible'] = TRUE;
							}
						}
						else {
							$row["visible"] = TRUE;
						}
					} else {
						// Check for the right groups
						foreach( $row["module_groups"] as $modGroupId ) {
							if( $gBitUser->isInGroup( $modGroupId )) {
								$row["visible"] = TRUE;
								break; // no need to continue looping
							}
						}
					}
				}

				if( empty( $ret[$row['layout_area']] )) {
					$ret[$row['layout_area']] = array();
				}

				$row['module_params'] = $this->parseString( $row['params'] );

				if( $row['layout_area'] == CENTER_COLUMN ) {
					array_push( $gCenterPieces, $row );
				}

				if( !empty( $row["visible"] )) {
					array_push( $ret[$row['layout_area']], $row );
				}

				$row = $result->fetchRow();
			}
		}
		return $ret;
	}

	/**
	 * isModuleLoaded will check if a given modules is being used in the currently active layout
	 *
	 * @param string $pModuleResource the module resource
	 * @param string $pArea optionally specify the area the module should be found in
	 * @access public
	 * @return TRUE on success, FALSE on failure
	 */
	function isModuleLoaded( $pModuleResource, $pArea = NULL ) {
		// load the layout if it hasn't been done yet
		$this->loadLayout();

		if( !$this->verifyArea( $pArea ) && !empty( $this->mLayout[$pArea] )) {
			foreach( $this->mLayout[$pArea] as $module ) {
				if( $pModuleResource == $module['module_rsrc'] ) {
					return TRUE;
				}
			}
		} else {
			foreach( array_keys( $this->mLayout ) as $area ) {
				if( !empty( $this->mLayout[$area] )) {
					foreach( $this->mLayout[$area] as $module ) {
						if( $pModuleResource == $module['module_rsrc'] ) {
							return TRUE;
						}
					}
				}
			}
		}
		return FALSE;
	}

	/**
	 * fix postional data in database using increments of 10 to make it easy for inserting new modules
	 *
	 * @access public
	 * @return void
	 */
	function fixPositions( $pLayout = NULL ) {
		$layouts = $this->getAllLayouts();

		// if we only want to fix the positions of a given layout, strip down the hash
		if( !empty( $pLayout ) && !empty( $layouts[$pLayout] )) {
			$layouts = array( $layouts[$pLayout] );
		}

		foreach( $layouts as $layout ) {
			foreach( $layout as $column ) {
				$i = 5;
				foreach( $column as $module ) {
					$this->mDb->query( "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET pos=? WHERE `module_id`=?", array( $i, $module['module_id'] ));
					$i += 5;
				}
			}
		}
	}

	/**
	 * get a brief summary of set layouts
	 *
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function getAllLayouts() {
		$layouts = array();
		$modules = $this->mDb->getAll( "SELECT tl.* FROM `".BIT_DB_PREFIX."themes_layouts` tl ORDER BY ".$this->mDb->convertSortmode( "pos_asc" ));
		foreach( $modules as $module ) {
			$module['module_groups'] = $this->parseGroups( $module['groups'] );
			$layouts[$module['layout']][$module['layout_area']][] = $module;
		}
		ksort( $layouts );
		// Take the default/kernel layout and make sure it is the first item in hash
		if( ( count( $layouts ) > 1 ) && isset( $layouts['kernel'] ) ) {
			$kernel_layout = $layouts['kernel'];
			unset( $layouts['kernel'] );
			$layouts = array('kernel' => $kernel_layout) + $layouts;
		}
		return $layouts;
	}

	/**
	 * cloneLayout
	 *
	 * @param array $pFromLayout
	 * @param array $pToLayout
	 * @access public
	 * @return boolean TRUE
	 */
	function cloneLayout( $pFromLayout, $pToLayout ) {
		global $gBitSystem;
		if( !empty( $pFromLayout ) && !empty( $pToLayout ) ) {
			// nuke existing layout
			$this->mDb->query( "DELETE FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `layout`=?", array( $pToLayout ));
			// get requested layout
			$layout = $this->mDb->getAll( "
				SELECT `title`, `layout_area`, `module_rows`, `module_rsrc`, `params`, `cache_time`, `groups`, `pos`
				FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `layout`=?", array( $pFromLayout ));
			foreach( $layout as $module ) {
				$module['layout'] = $pToLayout;
				$this->storeModule( $module );
			}
		}
		return TRUE;
	}

	/**
	 * expungeLayout
	 *
	 * @param array $pLayout
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function expungeLayout( $pLayout = NULL ) {
		$bindVars = array();
		if( !empty( $pLayout )) {
			$whereSql = "WHERE `layout`=?";
			$bindVars[] = $pLayout;
		}
		$this->mDb->query( "DELETE FROM `".BIT_DB_PREFIX."themes_layouts` $whereSql", $bindVars );
	}

	/**
	 * transform groups string to handy array
	 * 
	 * @param array $pParseString either space separated list of groups or serialised array
	 * @access public
	 * @return array of groups
	 */
	function parseGroups( $pParseString ) {
		$ret = array();
		// convert groups string to hash
		if( preg_match( '/[A-Za-z]/', $pParseString )) {
			// old style serialized group names
			if( $grps = @unserialize( $pParseString )) {
				foreach( $grps as $grp ) {
					global $gBitUser;
					if( !( $groupId = array_search( $grp, $gBitUser->mGroups ))) {
						if( $gBitUser->isAdmin() ) {
							$ret[] = $gBitUser->groupExists( $grp, '*' );
						}
					}

					if( @$this->verifyId( $groupId )) {
						$ret[] = $groupId;
					}
				}
			}
		} else {
			// new imploded style
			$ret = explode( ' ', $pParseString );
		}
		return $ret;
	}


	// }}}
	// {{{ =================== Modules ====================
	/**
	 * Verfiy module parameters when storing a new module
	 *
	 * @param array $pHash
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function verifyModuleParams( &$pHash ) {
		// we need at least a module_id or a module_rsrc
		if( empty( $pHash['module_id'] ) && empty( $pHash['module_rsrc'] )) {
			$this->mErrors['module_rsrc'] = tra( 'No module id or module file given.' );
		} elseif( !empty( $pHash['module_id'] )) {
			$pHash['store']['module_id'] = $pHash['module_id'];
		} elseif( !empty( $pHash['module_rsrc'] )) {
			$pHash['store']['module_rsrc'] = $pHash['module_rsrc'];
		}

		// if we don't have a valid area, we'll just shove it in the left column
		if( $this->verifyArea( $pHash['layout_area'] )) {
			$pHash['store']['layout_area'] = $pHash['layout_area'];
		} else {
			$pHash['store']['layout_area'] = 'l';
		}

		$pHash['store']['title']         = ( !empty( $pHash['title'] )             ? $pHash['title']         : NULL );
		$pHash['store']['params']        = ( !empty( $pHash['params'] )            ? $pHash['params']        : NULL );
		$pHash['store']['layout']        = ( !empty( $pHash['layout'] )            ? $pHash['layout']        : DEFAULT_PACKAGE );
		$pHash['store']['module_rows']   = ( @is_numeric( $pHash['module_rows'] )  ? $pHash['module_rows']   : NULL );
		$pHash['store']['cache_time']    = ( @is_numeric( $pHash['cache_time'] )   ? $pHash['cache_time']    : NULL );
		$pHash['store']['pos']           = ( @is_numeric( $pHash['pos'] )          ? $pHash['pos']           : 1 );

		if( !empty( $pHash['groups'] ) && is_array( $pHash['groups'] )) {
			$pHash['store']['groups'] = implode( ' ', $pHash['groups'] );
		} else {
			$pHash['store']['groups'] = NULL;
		}

		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * storeModule
	 *
	 * @param array $pHash
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function storeModule( &$pHash ) {
		if( $this->verifyModuleParams( $pHash )) {
			$table = BIT_DB_PREFIX."themes_layouts";

			if( @BitBase::verifyId( $pHash['store']['module_id'] )) {
				// if we've been passed a module_id, we are updating an entry in the DB
				$result = $this->mDb->associateUpdate( $table, $pHash['store'], array( 'module_id' => $pHash['store']['module_id'] ));
			} else {
				// no module_id yet - let's get one
				$pHash['store']['module_id'] = $this->mDb->GenID( 'themes_layouts_module_id_seq' );
				$result = $this->mDb->associateInsert( $table, $pHash['store'] );
			}
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * getModuleData
	 *
	 * @param array $pModuleId
	 * @access public
	 * @return module details of the requested module id
	 */
	function getModuleData( $pModuleId ) {
		if( @BitBase::verifyId( $pModuleId )) {
			$ret = $this->mDb->getRow( "SELECT tl.* FROM `".BIT_DB_PREFIX."themes_layouts` tl WHERE `module_id`=? ", array( $pModuleId ));
			$ret['module_params'] = $this->parseString( $ret['params'] );
			return $ret;
		}
	}

	/**
	 * moduleUp
	 *
	 * @param array $pModuleId
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function moveModuleUp( $pModuleId ) {
		if( @BitBase::verifyId( $pModuleId )) {
			$this->moveModule( $pModuleId, 'up' );
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * moduleDown
	 *
	 * @param array $pModuleId
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function moveModuleDown( $pModuleId ) {
		if( @BitBase::verifyId( $pModuleId )) {
			$this->moveModule( $pModuleId, 'down' );
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * generic function to move module up or down
	 *
	 * @param array $pModuleId
	 * @param string $pOrientation
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function moveModule( $pModuleId, $pDirection = 'down' ) {
		if( @BitBase::verifyId( $pModuleId )) {
			// first we get next module we want to swap with
			$moduleData = $this->getModuleData( $pModuleId );
			if( $pDirection == 'up' ) {
				$pos_check = 'AND `pos`<=?';
				$pos_set   = 'SET `pos`=`pos`-1';
				$order     = 'ORDER BY pos DESC';
			} else {
				$pos_check = 'AND `pos`>=?';
				$pos_set   = 'SET `pos`=`pos`+1';
				$order     = 'ORDER BY pos ASC';
			}
			$query  = "SELECT `module_id` FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `layout`=? AND `layout_area`=? $pos_check AND `module_id` <> ? $order";
			$swapModuleId = $this->mDb->getOne( $query, array( $moduleData['layout'], $moduleData['layout_area'], $moduleData['pos'], $moduleData['module_id'] ));
			if( $moduleSwap = $this->getModuleData( $swapModuleId )) {
				if( $moduleData['pos'] == $moduleSwap['pos'] ) {
					$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` $pos_set WHERE `module_id`=?";
					$result = $this->mDb->query( $query, array( $moduleData['module_id'] ));
				} else {
					$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `pos`=? WHERE `module_id`=?";
					$result = $this->mDb->query( $query, array( $moduleSwap['pos'], $moduleData['module_id'] ));
					$result = $this->mDb->query( $query, array( $moduleData['pos'], $moduleSwap['module_id'] ));
				}
			}
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * setModulePosition
	 *
	 * @param array $pModuleId
	 * @param array $pPos
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function setModulePosition( $pModuleId, $pPos ) {
		if( @BitBase::verifyId( $pModuleId )) {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `pos`=? WHERE `module_id`=?";
			$result = $this->mDb->query( $query, array( $pPos, $pModuleId ));
		}
		return TRUE;
	}

	/**
	 * moveModuleToArea
	 *
	 * @param array $pModuleId
	 * @param array $pArea
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function moveModuleToArea( $pModuleId, $pArea ) {
		if( !$this->verifyArea( $pArea )) {
			$pArea = 'l';
		}

		if( @BitBase::verifyId( $pModuleId )) {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `layout_area`=? WHERE `module_id`=?";
			$result = $this->mDb->query( $query, array( $pArea, $pModuleId ));
		}
		return TRUE;
	}

	/**
	 * unassignModule
	 *
	 * @param array $pModuleId can be a module id or a resource path. if it is a resource path, all modules with that resource will be removed
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function unassignModule( $pModuleMixed ) {
		$ret = FALSE;
		if( @BitBase::verifyId( $pModuleMixed )) {
			if( $this->mDb->query( "DELETE FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `module_id`=?", array( $pModuleMixed ))) {
				$ret = TRUE;
			}
		} elseif( !empty( $pModuleMixed )) {
			if( $this->mDb->query( "DELETE FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `module_rsrc`=?", array( $pModuleMixed ))) {
				$ret = TRUE;
			}
		}
		return $ret;
	}

	/**
	 * if the specified area doesn't make any sense, we just dump it in the left column
	 *
	 * @param array $pArea l --> left       r --> right       c --> center       b --> bottom       t --> top
	 * @access public
	 * @return valid area
	 */
	function verifyArea( &$pArea ) {
		return( !empty( $pArea ) && preg_match( '/^[lrctb]$/', $pArea ));
	}

	/**
	 * generates module names on full hash by reference
	 *
	 * @param array $p2DHash layout hash
	 * @access public
	 * @return void
	 */
	function generateModuleNames( &$p2DHash ) {
		if( is_array( $p2DHash )) {
			// Generate human friendly names
			foreach( array_keys( $p2DHash ) as $col ) {
				if( count( $p2DHash[$col] )) {
					foreach( array_keys( $p2DHash["$col"] ) as $mod ) {
						list( $rsrc, $specifier ) = explode( ':', $p2DHash[$col][$mod]['module_rsrc'], 2 );
						$specelems = explode( '/', $specifier );
						$package = current( $specelems );
						if( $package == 'temp' ) $package = next( $specelems );
						// handle special case for custom modules
						if( !isset( $package )) {
							$package = $rsrc;
						}
						$file = end( $specelems );
						$file = str_replace( 'mod_', '', $file );
						$file = str_replace( '.tpl', '', $file );
						$p2DHash[$col][$mod]['name'] = $package.' &raquo; '.str_replace( '_', ' ', $file );
					}
				}
			}
		}
	}

	/**
	 * getAllModules
	 *
	 * @param string $pDir
	 * @param string $pPrefix
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function getAllModules( $pDir='modules', $pPrefix='mod_' ) {
		global $gBitSystem;
		// @TODO MODULE UPGRADE
		// hash for carrying references to modules:
		// $this->mModules[$pDir][$pPrefix]
		// this is ugly but is to smooth the transition until all modules are upgraded to directory and registration structure
		// it will be unncessary once all packages are caught up

		if(( $modules = $this->getCustomModuleList() ) && $pPrefix == 'mod_' ) {
			foreach( $modules as $m ) {
				$this->mModules[$pDir][$pPrefix][tra( 'Custom Modules' )]['_custom:custom/'.$m["name"]] = array( 'title' => $m["name"] );
			}
			asort( $this->mModules[$pDir][$pPrefix][tra( 'Custom Modules' )] );
		}

		// iterate through all packages and look for all possible modules
		foreach( array_keys( $gBitSystem->mPackages ) as $key ) {
			if( $gBitSystem->isPackageActive( $key )) {
				$loc = BIT_ROOT_PATH.$gBitSystem->mPackages[$key]['dir'].'/'.$pDir;
				if( @is_dir( $loc )) {
					$h = opendir( $loc );
					if( $h ) {
						while (($file = readdir($h)) !== false) {
							// match on legacy module files which require a prefix
							if ( preg_match( "/^$pPrefix(.*)\.tpl$/", $file, $match )) {
								$this->mModules[$pDir][$pPrefix][ucfirst( $key )]['bitpackage:'.$key.'/'.$file] = array( 'title' => str_replace( '_', ' ', $match[1] ),
																														 'template' => $file,
																														);
							}
							// loop over nested directories which contain modern modules
							// these modules are only accessible from gBitThemes
							elseif ( !in_array( $file, array('.','..','CVS') ) && @is_dir( $loc.'/'.$file ) ){
								$conf_file = $loc.'/'.$file.'/config_inc.php';
								// we expect a configuration file
								if( @is_file( $conf_file ) ){
									require_once( $conf_file );
								}
							}
						}
						closedir ($h);
						if( !empty( $this->mModules[$pDir][$pPrefix][ucfirst( $key )] ) ) {
							asort( $this->mModules[$pDir][$pPrefix][ucfirst( $key )] );
						}
					}
				}
				// we scan temp/<pkg>/modules for module files as well for on the fly generated modules (e.g. nexus)
				if( $pDir == 'modules' ) {
					$loc = TEMP_PKG_PATH.$gBitSystem->mPackages[$key]['name'].'/'.$pDir;
					if( @is_dir( $loc )) {
						$h = opendir( $loc );
						if( $h ) {
							while (($file = readdir($h)) !== false) {
								if ( preg_match( "/^$pPrefix(.*)\.tpl$/", $file, $match )) {
									$this->mModules[$pDir][$pPrefix][ucfirst( $key )]['bitpackage:temp/'.$key.'/'.$file] = array( 'title' => str_replace( '_', ' ', $match[1] ),
																																  'template' => $file,
																																);
								}
							}
							closedir ($h);
							asort( $this->mModules[$pDir][$pPrefix][ucfirst( $key )] );
						}
					}
				}
			}
		}
		return $this->mModules[$pDir][$pPrefix];
	}

	function registerModule( $pMixed ){
		$pkg = $pMixed['package'];
		$dir = $pMixed['directory'];
		$tpl = $pMixed['template'];
		$legacy_dir = $pMixed['legacy_dir'];
		$legacy_prefix = $pMixed['legacy_prefix'];
		$this->mModules[$legacy_dir][$legacy_prefix][ucfirst( $pkg )]['bitpackage:'.$pkg.'/'.$dir.'/'.$tpl] = $pMixed;
	}

	// utility function for other packages when they upgrade their modules to the new module system
	// see themes/admin/upgrades/3.0.0.php for an example of usages
	function upgradeModulesPaths(){
		$this->getAllModules();
		$legacy_mods = array();
		$upgrade_mods = array();

		foreach( $this->mModules['modules']['mod_'] as $pkg => $modules ){
			foreach( $modules as $modulepath => $module ){
				$parts =  explode( "/", $modulepath );
				if( count( $parts ) > 2 ){
					$upgrade_mods[array_pop( $parts )] = $modulepath;
				}
			}
		}

		$sql1 = "SELECT DISTINCT `module_rsrc` FROM `".BIT_DB_PREFIX."themes_layouts`";
		$legacy_mods = $this->mDb->getArray( $sql1 );

		// fix everything
		// transaction will save us if something goes bad
		$this->mDb->StartTrans();

		foreach( $legacy_mods as $old ){
			$key =  array_pop( explode( "/", $old['module_rsrc'] ) );
			if( in_array( $key, array_keys($upgrade_mods) ) && $old['module_rsrc'] != $upgrade_mods[$key]){
				$storeHash = array( 'module_rsrc' => $upgrade_mods[$key] );
				$this->mDb->associateUpdate( BIT_DB_PREFIX."themes_layouts", $storeHash, array( 'module_rsrc' => $old['module_rsrc'] ));
			}
		}

		$this->mDb->CompleteTrans();
	}

	/**
	 * get a module-specfic parameters
	 *
	 * @param array $pModuleId
	 * @access public
	 * @return array or parameters
	 */
	function getModuleParameters( $pModuleId ) {
		$ret = array();
		if( @BitBase::verifyId( $pModuleId )) {
			$module = $this->getModuleData( $pModuleId );
			$ret = $module['module_params'];
		} else {
			deprecated( 'Please use the module parameters found in vd( $moduleParams[\'module_params\'] ); or pass in the module id for a database lookup.' );
		}
		return $ret;
	}

	/**
	 * parse URL-like parameter string
	 *
	 * @param array $pParseString
	 * @access public
	 * @return array or parameters
	 */
	function parseString( $pParseString ) {
		$ret = array();
		if( !empty( $pParseString )) {
			// only call crazy regex when params are too complex for parse_str()
			if( strpos( trim( $pParseString ), ' ' )) {
				$ret = parse_xml_attributes( $pParseString );
			} else {
				parse_str( $pParseString, $ret );
			}
		}
		return $ret;
	}


	// }}}
	// {{{ =================== Custom Modules ====================
	/**
	 * verifyCustomModule
	 *
	 * @param array $pParamHash
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function verifyCustomModule( &$pParamHash ) {
		if( !empty( $pParamHash['name'] ) && preg_match( "/[a-zA-Z]/", $pParamHash['name'] )) {
			$pParamHash['store']['name'] = substr( strtolower( preg_replace( "/[^\w]*/", "", $pParamHash['name'] )), 0, 40 );
		}

		if( empty( $pParamHash['store']['name'] )) {
			$this->mErrors[] = tra( 'You need to provide a name for your custom module. Only alphanumeric characters are allowed and you need to use at least one letter.' );
		}

		if( !empty( $pParamHash['title'] )) {
			$pParamHash['store']['title'] = substr( $pParamHash['title'], 0, 200 );
		}

		if( !empty( $pParamHash['data'] )) {
			$pParamHash['store']['data'] = $pParamHash['data'];
		}

		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * storeCustomModule
	 *
	 * @param array $pParamHash
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function storeCustomModule( $pParamHash ) {
		if( $this->verifyCustomModule( $pParamHash )) {
			$table = "`".BIT_DB_PREFIX."themes_custom_modules`";
			$result = $this->mDb->query( "DELETE FROM $table WHERE `name`=?", array( $pParamHash['store']['name'] ));
			$result = $this->mDb->associateInsert( $table, $pParamHash['store'] );
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * getCustomModule
	 *
	 * @param array $pName
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function getCustomModule( $pName ) {
		if( !empty( $pName )) {
			return $this->mDb->getRow( "SELECT * FROM `".BIT_DB_PREFIX."themes_custom_modules` WHERE `name`=?", array( $pName ));
		}
	}

	/**
	 * getCustomModuleList
	 *
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function getCustomModuleList() {
		return( $this->mDb->getAll( "SELECT * FROM `".BIT_DB_PREFIX."themes_custom_modules`" ));
	}

	/**
	 * expungeCustomModule
	 *
	 * @param array $pName
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function expungeCustomModule( $pName ) {
		if( !empty( $pName )) {
			$this->unassignModule( '_custom:custom/'.$pName );
			$result = $this->mDb->query( "DELETE FROM `".BIT_DB_PREFIX."themes_custom_modules` WHERE `name`=?", array( $pName ));
		}
		return TRUE;
	}

	/**
	 * isCustomModule
	 *
	 * @param array $pMixed either name of module or the rsrc of a module
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function isCustomModule( $pMixed ) {
		if( strpos( $pMixed, "_custom:custom" ) !== FALSE ) {
			return TRUE;
		} elseif( strpos( $pMixed, "bitpackage:" ) !== FALSE ) {
			return FALSE;
		} else {
			$result = $this->mDb->getOne( "SELECT `name` FROM `".BIT_DB_PREFIX."themes_custom_modules` WHERE `name`=?", array( $pMixed ));
			return( !empty( $result ));
		}
	}


	// }}}
	// {{{ =================== Javascript and CSS related Methods ====================
	/**
	 * Statically callable function to see if browser supports javascript
	 * determined by cookie set in bitweaver.js
	 * @access public
	 */
	function isJavascriptEnabled() {
		return( !empty( $_COOKIE['javascript_enabled'] ) && $_COOKIE['javascript_enabled'] == 'y' );
	}

	/**
	 * Statically callable function to determine if the current call was made using Ajax
	 *
	 * @access public
	 */
	function isAjaxRequest() {
		return(( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) || !empty( $_REQUEST['ajax_xml'] ));
	}

	// {{{ Javascript and CSS load methods
	/**
	 * Load Ajax libraries
	 *
	 * @param array $pAjaxLib Name of the library we want to use e.g.: prototype or mochikit
	 * @param array $pLibHash Array of additional libraries we need to load
	 * @param boolean $pPack Set to true if you want to pack the javascript file
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function loadAjax( $pAjaxLib, $pLibHash=NULL, $pLibPath=NULL, $pPack = FALSE ) {
		global $gBitSystem, $gBitSmarty, $gSniffer;
		$ret = FALSE;
		$joined = TRUE;
		$ajaxLib = strtolower( $pAjaxLib );
		if( $this->isJavascriptEnabled() ) {
			// set the javascript lib path if not set yet
			if( empty( $pLibPath )) {
				switch( $ajaxLib ) {
					case 'mochikit':
						$pLibPath = UTIL_PKG_PATH."javascript/libs/MochiKit/";
						$pos = 100;
						break;
					case 'yui':
						$pLibPath = UTIL_PKG_PATH."javascript/libs/yui/";
						$pos = 100;
						break;
					case 'jquery':
						$pLibPath = UTIL_PKG_PATH."javascript/libs/jquery/";
						$pos = 100;
						break;
					default:
						$pLibPath = UTIL_PKG_PATH."javascript/";
						$pos = 200;
						break;
				}
			}

			if( !$this->isAjaxLib( $ajaxLib )) {
				// load core javascript files for ajax libraries
				switch( $ajaxLib ) {
					case 'mochikit':
						$this->loadJavascript( $pLibPath.'Base.js', FALSE, $pos++ );
						$this->loadJavascript( $pLibPath.'Async.js', FALSE, $pos++ );
						$this->loadJavascript( UTIL_PKG_PATH.'javascript/MochiKitBitAjax.js', FALSE, 150 );
						break;
					case 'prototype':
						$this->loadJavascript( $pLibPath.'libs/prototype.js', FALSE, $pos++ );
						break;
					case 'jquery':
						$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

						$jqueryVersion = $gBitSystem->getConfig( 'jquery_version', '1.7.1' );
						$jqueryUiVersion = $gBitSystem->getConfig( 'jquery_ui_version', '1.8.18' );
						$jqueryTheme = $gBitSystem->getConfig( 'jquery_theme', 'base' );
						if( defined( 'IS_LIVE' ) && IS_LIVE ) {
							$this->mRawFiles['js'][] = $protocol.'://ajax.googleapis.com/ajax/libs/jquery/'.$jqueryVersion.'/jquery.min.js';
							$this->mRawFiles['js'][] = $protocol.'://ajax.googleapis.com/ajax/libs/jqueryui/'.$jqueryUiVersion.'/jquery-ui.min.js';
							$this->mRawFiles['css'][] = $protocol.'://ajax.googleapis.com/ajax/libs/jqueryui/'.$jqueryUiVersion.'/themes/'.$jqueryTheme.'/jquery-ui.css';
						} else {
							$this->mRawFiles['js'][] = $protocol.'://ajax.googleapis.com/ajax/libs/jquery/'.$jqueryVersion.'/jquery.js';
							$this->mRawFiles['js'][] = $protocol.'://ajax.googleapis.com/ajax/libs/jqueryui/'.$jqueryUiVersion.'/jquery-ui.js';
							$this->mRawFiles['css'][] = $protocol.'://ajax.googleapis.com/ajax/libs/jqueryui/'.$jqueryUiVersion.'/themes/'.$jqueryTheme.'/jquery-ui.css';
						}
						break;
					case 'jquerylocal':
						$joined = FALSE;
						if( defined( 'IS_LIVE' ) && IS_LIVE ) {
							$this->loadJavascript( $pLibPath.'js/jquery.min.js', FALSE, $pos++, $joined );
							$this->loadJavascript( $pLibPath.'js/jquery-ui.custom.min.js', FALSE, $pos++, $joined );
						} else {
							$this->loadJavascript( $pLibPath.'development-bundle/jquery.js', FALSE, $pos++, $joined );
							$this->loadJavascript( $pLibPath.'development-bundle/ui/jquery-ui.custom.js', FALSE, $pos++, $joined );
						}
						$this->loadCss( UTIL_PKG_PATH.'javascript/libs/jquery/development-bundle/themes/base/jquery.ui.all.css' );
					case 'yui':
						$this->loadJavascript( $pLibPath.'yuiloader-dom-event/yuiloader-dom-event.js', FALSE, $pos++ );
						break;
				}
				$this->mAjaxLibs[$ajaxLib] = TRUE;
			}

			if( is_array( $pLibHash )) {
				foreach( $pLibHash as $lib ) {
					$fullLib = ($lib[0] == '/' ? '' : $pLibPath).$lib;
					$this->loadJavascript( $fullLib, $pPack, $pos++, $joined );
				}
			}

			$ret = TRUE;
		}
		return $ret;
	}

	/**
	 * check to see if a given ajax library is loaded
	 *
	 * @param array $pAjaxLib
	 * @access public
	 * @return TRUE on success, FALSE on failure
	 */
	function isAjaxLib( $pAjaxLib ) {
		if( !empty( $this->mAjaxLibs ) && !empty( $pAjaxLib )) {
			return in_array( strtolower( $pAjaxLib ), array_keys( $this->mAjaxLibs ));
		}
	}

	/**
	 * scan packages for <pkg>/templates/header_inc.tpl or footer_inc.tpl files
	 *
	 * @param string $pFilename Name of template file we want to scan for and collect
	 * @access private
	 * @return void
	 */
	function loadTplFiles( $pFilename ) {
		global $gBitSystem;
		// these package templates will be included last
		$prepend = array( 'kernel' );
		$append = array( 'themes' );
		$anti = $mid = $post = array();
		foreach( $gBitSystem->mPackages as $package => $info ) {
			if( !empty( $info['path'] )) {
				$file = "{$info['path']}templates/{$pFilename}.tpl";
				$out = "bitpackage:{$package}/{$pFilename}.tpl";
				if( is_readable( $file )) {
					if( in_array( $package, $prepend )) {
						$anti[] = $out;
					} elseif( in_array( $package, $append )) {
						$post[] = $out;
					} else {
						$mid[] = $out;
					}
				}
			}
		}
		$this->mAuxFiles['templates'][$pFilename] = array_merge( $anti, $mid, $post );
	}

	/**
	 * loadAuxFile will add a file to the mAuxFiles hash for later processing
	 *
	 * @param array $pFile Full path to the file in question
	 * @param string $pType specifies what files to join. typical values include 'js', 'css'
	 * @param numeric $pPosition Specify the position of the javascript file in the load process.
	 *                           If the selected position is occupied, it will search for the next free position in the hash.
	 * @access public
	 * @return TRUE on success, FALSE on failure
	 */
	function loadAuxFile( $pFile = NULL, $pType = NULL, $pPosition = 1, $pAuxFile = TRUE ) {
		if( !empty( $pFile ) && !empty( $pType )) {
			if( $pFile = realpath( $pFile )) {
				if( $pAuxFile ) {
					$fileHash =& $this->mAuxFiles;
				} else {
					$fileHash =& $this->mRawFiles;
				}

				if( !$this->isAuxFile( $pFile, $pType, $pAuxFile )) {
					// if the selected position is occupied, we'll try to load it in the next position
					if( !empty( $fileHash[$pType][$pPosition] )) {
						$this->loadAuxFile( $pFile, $pType, ++$pPosition, $pAuxFile );
					} else {
						$fileHash[$pType][$pPosition] = $pFile;
						// ensure that hash is sorted correctly
						ksort( $fileHash[$pType] );

						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	/**
	 * Load an addition javascript file
	 *
	 * @param string $pJavascriptFile Full path to javascript file
	 * @param boolean $pPack Set to true if you want to pack the javascript file
	 * @param numeric $pPosition Specify the position of the javascript file in the load process
	 * @note
	 *  - generic javascript libraries are loaded between 1 and 99
	 *  - ajax javascript libraries use position numbers between 100 and 599
	 *  - by default all loaded javascript files are after 600.
	 * @access public
	 * @return TRUE on success, FALSE on failure
	 */
	function loadJavascript( $pJavascriptFile, $pPack = FALSE, $pPosition = 600, $pJoined = TRUE ) {
		global $gBitSystem;
		$ret = FALSE;
		if( !empty( $pJavascriptFile )) {
			if( $pPack && $gBitSystem->isFeatureActive( 'themes_packed_js_css' ) && function_exists( 'shell_exec' ) && shell_exec( 'which java' ) ) {
				if( is_file( $pJavascriptFile )) {
					// get a name for the cache file we're going to store
					$cachefile = md5( $pJavascriptFile ).'.js';

					// if the file hasn't been packed and cached yet, we do that now.
					if( !$this->mThemeCache->isCached( $cachefile, filemtime( $pJavascriptFile ))) {
						/* DEPRECATED in favor of better yui compressor
						require_once( UTIL_PKG_PATH.'javascript/class.JavaScriptPacker.php' );
						$packer = new JavaScriptPacker( file_get_contents( $pJavascriptFile ) );
						$this->mThemeCache->writeCacheFile( $cachefile, $packer->pack() );
						*/
						$cacheData = shell_exec( 'java -jar '.UTIL_PKG_PATH.'yui/yuicompressor-2.4.2.jar --type js '.$pJavascriptFile );
						$this->mThemeCache->writeCacheFile( $cachefile, $cacheData );
					}

					// update javascript file with new path
					$pJavascriptFile = $this->mThemeCache->getCacheFile( $cachefile );
				}
			}

			$ret = $this->loadAuxFile( $pJavascriptFile, 'js', $pPosition, ( $pJoined && $gBitSystem->isFeatureActive( 'themes_joined_js_css' )));
		}
		return $ret;
	}

	/**
	 * Load an additional CSS file
	 *
	 * @param array $pCssFile Full path to CSS file
	 * @param numeric $pPosition Specify the position of the javascript file in the load process
	 * @param boolean $pJoined Adds the file to the list of files to be concatenated into a single file
	 * @param boolean $pForce Forces the css file to always be loaded, should only be used by active style
	 * @access public
	 * @return TRUE on success, FALSE on failure
	 */
	function loadCss( $pCssFile, $pPack = TRUE, $pPosition = 300, $pJoined = TRUE, $pForce = FALSE ) {
		global $gBitSystem;
		$ret = FALSE;
		if( !empty( $pCssFile ) && ( !$gBitSystem->isFeatureActive( 'themes_disable_pkg_css' ) || $pForce )) {
			// only manipulate css file if we're joining or packing the files
			if(( $pJoined && $gBitSystem->isFeatureActive( 'themes_joined_js_css' )) || ( $pPack && $gBitSystem->isFeatureActive( 'themes_packed_js_css' ))) {
				$pCssFile = $this->packCss( $pCssFile, ( $pPack && $gBitSystem->isFeatureActive( 'themes_packed_js_css' )));
			}

			$ret = $this->loadAuxFile( $pCssFile, 'css', $pPosition, ( $pJoined && $gBitSystem->isFeatureActive( 'themes_joined_js_css' )));
		}
		return $ret;
	}

	/**
	 * simply pack css file by removing excess whitespace and comments
	 *
	 * @param array $pCssFile full path to css file
	 * @access private
	 * @return TRUE on success, FALSE on failure
	 */
	function packCss( $pCssFile, $pPack = TRUE ) {
		$ret = FALSE;
		if( !empty( $pCssFile ) && is_readable( $pCssFile )) {
			$cachefile = md5( $pCssFile ).'.css';

			if( !$this->mThemeCache->isCached( $cachefile, filemtime( $pCssFile ))) {
				$content = file_get_contents( $pCssFile )."\n";

				// now that @import has been dealt with, there still might be some url()s in the file.
				// if we have any url() in the CSS file, we need to fix the path to the file with an absolute URL
				if( preg_match_all( "#\burl\s*\((.*?)\)#i", $content, $urls )) {
					foreach( $urls[1] as $key => $url ) {
						if( $url = $this->relativeToAbsolute( $url, $pCssFile )) {
							$content = str_replace( $urls[1][$key], $url, $content );
						}
					}
				}

				// if we have an @import(), we fetch that file and insert it
				if( preg_match_all( "#@import([^;]*);#", $content, $imports )) {
					foreach( $imports[1] as $key => $import ) {
						if( $file = $this->relativeToAbsolute( $import, $pCssFile, FALSE )) {
							// since we're packing later on, we don't pack here, otherwise the same sections will be packed multiple times
							$content = str_replace( $imports[0][$key], file_get_contents( $this->packCss( $file, FALSE )), $content );
						}
					}
				}

				// now pack the css file if wanted
				if( $pPack ) {
					$packer = array(
//						"#/\*.*\*/#"           => "",       // one line comments -- disabled for now since it causes problems when someone has a multiline comment and closes it with /* */
						"#\n\s*#s"             => "\n",     // leading whitespace
						"#[\t ]+#"             => " ",      // reduce whitespace
						"#,\s*#s"              => ",",      // whitespace after ,
						"#[ \t]*([:;])[ \t]*#" => "$1",     // whitespace around : ;
						"#;\n+#"               => ";",      // newlines after ;
						"#\s*([\{\}])\s*#"     => "$1",     // whitespace around { }
						"#\}#"                 => "}\n",    // insert newlines after } for readability
						"#{([^\}]*){#"         => "{\n$1{", // insert newlines after { when there's a second { on that line ( e.g.: @media{body{...} )
						"#.*{\s*\}#"           => '',       // remove empty definitions ( thanks to the ',' regex above, things like h1,h2,h3 {} should all be on one line )
						"#\n+#"                => "\n",     // excess newlines
					);
					$content = preg_replace( array_keys( $packer ), array_values( $packer ), $content );
				}

				// css files have been compressed and url()s have been fixed
				$this->mThemeCache->writeCacheFile( $cachefile, $content );
			}
			$ret = $this->mThemeCache->getCacheFile( $cachefile );
		}
		return $ret;
	}

	/**
	 * relativeToAbsolute convert a relative or absolute URL to an absolute URL or path
	 *
	 * @param string $pUrl url() in the css file
	 * @param string $pCssFile full path to the css file calling the url()
	 * @param boolean $pReturnUrl return URL or path to file
	 * @access private
	 * @return URL/path on success, FALSE on failure
	 */
	function relativeToAbsolute( $pUrl, $pCssFile, $pReturnUrl = TRUE ) {
		$ret = FALSE;
		if( !empty( $pUrl ) && !empty( $pCssFile )) {
			// clean up url
			if( preg_match( "#url\s*\(#", $pUrl )) {
				$pUrl = trim( preg_replace( "#url\s*\(([^\)]*)\)#", "$1", $pUrl ));
			}

			$pUrl = trim( preg_replace( "#[\"']#", "", $pUrl ));

			if( strpos( $pUrl, "http" ) === 0 ) {
				// don't do anything
			} elseif( strpos( $pUrl, "/" ) === 0 ) {
				// if this is an absolute url, we check if the file exists
				$ret = substr_replace( $pUrl, BIT_ROOT_PATH, 0, strlen( BIT_ROOT_URL ));
			} else {
				// this url is relative to the original file
				$ret = realpath( dirname( $pCssFile )."/".$pUrl );
			}

			if( $pReturnUrl ) {
				if (is_windows() ) {
					$ret = str_replace( '\\', '/',  $ret );
					// Put first forward slash back
					$ret = substr_replace($ret, '\\', 2, 1 );
					$winBitRootPath = str_replace( '\\', '/',  BIT_ROOT_PATH );
					// Put first forward slash back
					$winBitRootPath = substr_replace($winBitRootPath, '\\', 2, 1 );
					$ret = str_replace( $winBitRootPath, BIT_ROOT_URL, $ret );
				} else {
					$ret = str_replace( BIT_ROOT_PATH, BIT_ROOT_URL, $ret );
				}
			} else if (is_windows() ) {
				$ret = str_replace(  '/', '\\', $ret );
			}
		}
		return $ret;
	}

	/**
	 * joinAuxFiles will join all files in mAuxFiles[hash] into one cached file. This helps keep our HTTP requests down to a minimum.
	 *
	 * @param string $pType specifies what files to join. typical values include 'js', 'css'
	 * @access private
	 * @return url to cached file
	 */
	function joinAuxFiles( $pType ) {
		global $gBitSystem;
		$ret = FALSE;

		// remove conflicting aux files
		$this->cleanAuxFiles( $pType );

		if(( $pType == 'js' || $pType == 'css' ) && !$gBitSystem->isFeatureActive( 'themes_joined_js_css' )) {
			return $ret;
		}

		if( !empty( $pType ) && !empty( $this->mAuxFiles[$pType] ) && is_array( $this->mAuxFiles[$pType] )) {
			$cachestring = '';
			$lastmodified = 0;
			// get a unique cachefile name for this set of javascript files
			foreach( $this->mAuxFiles[$pType] as $file ) {
				if( is_file( $file )) {
					$cachestring .= '|'.$file;
					$lastmodified = max( $lastmodified, filemtime( $file ));
				}
			}
			$cachefile = md5( $cachestring ).'.'.$pType;

			if( !$this->mThemeCache->isCached( $cachefile, $lastmodified )) {
				$contents = '';
				foreach( $this->mAuxFiles[$pType] as $file ) {
					// if we have an extension to check against, we'll do that
					$chars = 0 - ( strlen( $pType ) + 1 );
					if( !empty( $pType ) && substr( $file, $chars ) == '.'.$pType && is_readable( $file )) {
						$contents .= file_get_contents( $file )."\n";
					}
				}
				$this->mThemeCache->writeCacheFile( $cachefile, $contents );
			}

			$ret = $this->mThemeCache->getCacheUrl( $cachefile );
		}
		return $ret;
	}

	/**
	 * cleanAuxFiles will remove unwanted aux files if conflicting files have been loaded
	 *
	 * @param string $pType specifies what files to clean up. typical values include 'js', 'css'
	 * @access private
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 * @note  It is regrettable that we have this method here but our previous
	 *        use of prototype requires this cleanup and might be needed in the
	 *        future as well
	 */
	function cleanAuxFiles( $pType ) {
		// unload files that are not wanted by users
		if( !empty( $this->mUnloadFiles[$pType] )) {
			foreach( $this->mUnloadFiles[$pType] as $file ) {
				if( !empty( $this->mAuxFiles[$pType] )) {
					if( $key = array_search( $file, $this->mAuxFiles[$pType] )) {
						unset( $this->mAuxFiles[$pType][$key] );
					}
				}

				if( !empty( $this->mRawFiles[$pType] )) {
					if( $key = array_search( $file, $this->mRawFiles[$pType] )) {
						unset( $this->mRawFiles[$pType][$key] );
					}
				}
			}
		}

		// remove conflicting files
		if( !empty( $pType ) && !empty( $this->mAuxFiles[$pType] )) {
			if( $pType = 'js' ) {
				// prototype is loaded for a reason. we'll remove mochikit
				if( $this->isAjaxLib( 'prototype' ) && $this->isAjaxLib( 'mochikit' )) {
					foreach( $this->mAuxFiles[$pType] as $key => $js ) {
						if( strstr( $js, 'Mochi' )) {
							unset( $this->mAuxFiles[$pType][$key] );
						}
					}
				}
			}
		}

		// convert full file path to URL in mRawFiles hash
		if( !empty( $this->mRawFiles[$pType] )) {
			foreach( $this->mRawFiles[$pType] as $pos => $file ) {
				if (is_windows() ) {
					$file = str_replace( '\\', '/',  $file );
					// Put first forward slash back
					$file = substr_replace( $file, '\\', 2, 1 );
					$winBitRootPath = str_replace( '\\', '/',  BIT_ROOT_PATH );
					// Put first forward slash back
					$winBitRootPath = substr_replace($winBitRootPath, '\\', 2, 1 );
					if ( strpos( $file, $winBitRootPath ) !== FALSE ) {
						$this->mRawFiles[$pType][$pos] = BIT_ROOT_URL.substr( $file, strlen( $winBitRootPath ));
					}
				} else if ( strpos( $file, BIT_ROOT_PATH ) !== FALSE ) {
					$this->mRawFiles[$pType][$pos] = BIT_ROOT_URL.substr( $file, strlen( BIT_ROOT_PATH ));
				}
			}
		}
	}

	// }}}
	// {{{ Javascript and CSS unload methods
	/**
	 * unloadAuxFile
	 *
	 * @param string $pType specifies what files to clean up. typical values include 'js', 'css'
	 * @param array $pFile Full path to the file in question
	 * @access private
	 * @return void
	 */
	function unloadAuxFile( $pType, $pFile ) {
		if( !empty( $pType ) && !empty( $pFile ) && is_file( $pFile )) {
			$this->mUnloadFiles[$pType][] = $pFile;
		}
	}

	/**
	 * unloadCss
	 *
	 * @param array $pFile Full path to the file in question
	 * @access public
	 * @return void
	 */
	function unloadCss( $pFile ) {
		return $this->unloadAuxFile( 'css', $pFile );
	}

	/**
	 * unloadJvascript
	 *
	 * @param array $pFile Full path to the file in question
	 * @access public
	 * @return void
	 */
	function unloadJavascript( $pFile ) {
		return $this->unloadAuxFile( 'js', $pFile );
	}

	// }}}
	// {{{ Javascript and CSS override methods
	/**
	 * overrideAuxFile Override an aux file
	 *
	 * @param string $pType specifies what files to clean up. typical values include 'js', 'css'
	 * @param array $pOriginalFile Path to old file
	 * @param array $pNewFile Path to new file
	 * @access private
	 * @return boolean TRUE on success, FALSE on failure
	 * @note This can only be used after the original file has been loaded since we're swapping the original one with a new one
	 */
	function overrideAuxFile( $pType, $pOriginalFile, $pNewFile ) {
		$ret = FALSE;
		if( is_file( $pNewFile )) {
			if( $key = array_search( $pOriginalFile, $this->mAuxFiles[$pType] )) {
				$this->mAuxFiles[$pType][$key] = $pNewFile;
				$ret = TRUE;
			}

			if( $key = array_search( $pOriginalFile, $this->mRawFiles[$pType] )) {
				$this->mRawFiles[$pType][$key] = $pNewFile;
				$ret = TRUE;
			}
		}
		return $ret;
	}

	/**
	 * overrideCss
	 *
	 * @param array $pOriginalFile Path to old file
	 * @param array $pNewFile Path to new file
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure
	 * @note See overrideAuxFile note
	 */
	function overrideCss( $pOriginalFile, $pNewFile ) {
		return $this->overrideAuxFile( 'css', $pOriginalFile, $pNewFile );
	}

	/**
	 * overrideJavascript
	 *
	 * @param array $pOriginalFile Path to old file
	 * @param array $pNewFile Path to new file
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure
	 * @note See overrideAuxFile note
	 */
	function overrideJavascript( $pOriginalFile, $pNewFile ) {
		return $this->overrideAuxFile( 'js', $pOriginalFile, $pNewFile );
	}
	// }}}

	/**
	 * isAuxFile
	 *
	 * @param array $pFile Full path to file
	 * @param string $pType specifies what files to check. typical values include 'js', 'css'
	 * @access public
	 * @return TRUE on success, FALSE on failure
	 */
	function isAuxFile( $pFile = NULL, $pType = NULL, $pAuxFile = TRUE ) {
		if( $pAuxFile ) {
			$fileHash =& $this->mAuxFiles;
		} else {
			$fileHash =& $this->mRawFiles;
		}

		if( !empty( $pFile ) && !empty( $pType ) && !empty( $fileHash[$pType] )) {
			return( in_array( $pFile, $fileHash[$pType] ));
		}
	}


	// }}}
	// {{{ =================== Miscellaneous Stuff ====================
	/**
	 * setDisplayMode
	 *
	 * @param string $pDisplayMode
	 * @access public
	 * @return void
	 */
	function setDisplayMode( $pDisplayMode ) {
		if( !empty( $pDisplayMode )) {
			$this->mDisplayMode = $pDisplayMode;
		}
	}

	/**
	 * Set the proper headers for requested output
	 *
	 * @param  $pFormat the output headers. Available options include: html, json, xml or none
	 * @access public
	 */
	function setFormatHeader( $pFormat = 'html' ) {
		// this will tell BitSystem::display what headers have been set in case it's been called independently
		$this->mFormatHeader = $pFormat;

		switch( $pFormat ) {
			case "xml" :
				//since we are returning xml we must report so in the header
				//we also need to tell the browser not to cache the page
				//see: http://mapki.com/index.php?title=Dynamic_XML
				// Date in the past
				header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
				// always modified
				header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" )." GMT" );
				// HTTP/1.1
				header( "Cache-Control: no-store, no-cache, must-revalidate" );
				header( "Cache-Control: post-check=0, pre-check=0", FALSE );
				// HTTP/1.0
				header( "Pragma: no-cache" );
				//XML Header
				header( "Content-Type: text/xml" );
				break;

			case "json" :
				header( 'Content-type: application/json' );
				break;

			case "none" :
			case "center_only" :
				break;

			case "html" :
			default :
				header( 'Content-Type: text/html; charset=utf-8' );
				break;
		}
	}

	/**
	 * getGraphvizGraphAttributes
	 *
	 * @param array $pParams Override any of the settings coming out of this function
	 * @access public
	 * @return array Hash of default values
	 */
	function getGraphvizGraphAttributes( $pParams = array() ) {
		global $gBitSystem;
		$ret = array(
			'bgcolor'  => $gBitSystem->getConfig( 'graphviz_graph_bgcolor', 'transparent' ),
			'color'    => $gBitSystem->getConfig( 'graphviz_graph_color', '#000000' ),
			'fontname' => $gBitSystem->getConfig( 'graphviz_graph_fontname', 'Helvetica' ),
			'fontsize' => $gBitSystem->getConfig( 'graphviz_graph_fontsize', 10 ),
			'nodesep'  => $gBitSystem->getConfig( 'graphviz_graph_nodesep', '.1' ),
			'overlap'  => $gBitSystem->getConfig( 'graphviz_graph_overlap', 'scale' ),
			'rankdir'  => $gBitSystem->getConfig( 'graphviz_graph_rankdir', 'LR' ),
			'size'     => '',
		);

		foreach( $pParams as $key => $value ) {
			// any parameter can be prefixed that they can be passed in all at once
			if( empty( $value ) || preg_match( "@^(edge_|node_)@", $key )) {
				unset( $pParams[$key] );
			} elseif( isset( $ret[preg_replace( '@^graph_@', '', $key )] )) {
				$ret[preg_replace( '@^graph_@', '', $key )] = $value;
			}
		}
		return $ret;
	}

	/**
	 * getGraphvizNodeAttributes
	 *
	 * @param array $pParams Override any of the settings coming out of this function
	 * @access public
	 * @return array Hash of default values
	 */
	function getGraphvizNodeAttributes( $pParams = array() ) {
		global $gBitSystem;
		$ret = array(
			'color'     => $gBitSystem->getConfig( 'graphviz_node_color', '#aaaaaa' ),
			'fillcolor' => $gBitSystem->getConfig( 'graphviz_node_fillcolor', 'white' ),
			'fontname'  => $gBitSystem->getConfig( 'graphviz_node_fontname', 'Helvetica' ),
			'fontsize'  => $gBitSystem->getConfig( 'graphviz_node_fontsize', 10 ),
			'fontcolor' => $gBitSystem->getConfig( 'graphviz_node_fontcolor', 'black' ),
			'height'    => $gBitSystem->getConfig( 'graphviz_node_height', '.1' ),
			'overlap'   => $gBitSystem->getConfig( 'graphviz_node_overlap', 'scale' ),
			'penwidth'  => $gBitSystem->getConfig( 'graphviz_node_penwidth', '1' ),
			'shape'     => $gBitSystem->getConfig( 'graphviz_node_shape', 'box' ),
			'style'     => $gBitSystem->getConfig( 'graphviz_node_style', 'rounded,filled' ),
			'width'     => $gBitSystem->getConfig( 'graphviz_node_width', '.1' ),
		);

		foreach( $pParams as $key => $value ) {
			// any parameter can be prefixed that they can be passed in all at once
			if( empty( $value ) || preg_match( "@^(edge_|graph_)@", $key )) {
				unset( $pParams[$key] );
			} elseif( isset( $ret[preg_replace( '@^node_@', '', $key )] )) {
				$ret[preg_replace( '@^node_@', '', $key )] = $value;
			}
		}
		return $ret;
	}

	/**
	 * getGraphvizEdgeAttributes
	 *
	 * @param array $pParams Override any of the settings coming out of this function
	 * @access public
	 * @return array Hash of default values
	 */
	function getGraphvizEdgeAttributes( $pParams = array() ) {
		global $gBitSystem;
		$ret = array(
			'color'     => $gBitSystem->getConfig( 'graphviz_edge_color', '#888888' ),
			'fontcolor' => $gBitSystem->getConfig( 'graphviz_edge_fontcolor', 'black' ),
			'fontname'  => $gBitSystem->getConfig( 'graphviz_edge_fontname', 'Helvetica' ),
			'fontsize'  => $gBitSystem->getConfig( 'graphviz_edge_fontsize', 10 ),
			'style'     => $gBitSystem->getConfig( 'graphviz_edge_style', 'solid' ),
			'dir'       => '',
			'label'     => '',
		);

		foreach( $pParams as $key => $value ) {
			// any parameter can be prefixed that they can be passed in all at once
			if( empty( $value ) || preg_match( "@^(node_|graph_)@", $key )) {
				unset( $pParams[$key] );
			} elseif( isset( $ret[preg_replace( '@^edge_@', '', $key )] )) {
				$ret[preg_replace( '@^edge_@', '', $key )] = $value;
			}
		}
		return $ret;
	}


	// }}}
	// {{{ =================== Deprecated code ====================
	// deprecated stuff and temporary place holders
	// 																		--------------- all of these functions will be removed quite soon
	/**
	 * @deprecated deprecated since version 2.0.0
	 */
	function storeLayout() {
		deprecated( 'Please remove this function and use storeModule instead' );
	}
	/**
	 * @deprecated deprecated since version 2.0.0
	 */
	function storeModuleParameters($mod_rsrc, $user_id, $params) {
		deprecated( 'This method does not work as expected due to changes in the layout schema. we have not found a suitable replacement yet.' );
	}
	/**
	 * @deprecated deprecated since version 2.0.0
	 */
	function getModuleId($mod_rsrc) {
		deprecated( 'This method does not work as expected due to changes in the layout schema. we have not found a suitable replacement yet.' );
	}
	/**
	 * @deprecated deprecated since version 2.0.0
	 */
	function getStyleCss( $pStyle = NULL ) {
		deprecated( 'Please use: BitThemes::getStyleCssFile()' );
		return $this->getStyleCssFile( $pStyle, TRUE );
	}
	// }}}
}

/**
 * load content specific theme picked by user
 *
 * @param array $pContent
 * @access public
 * @return void
 */
function themes_content_display( $pContent ) {
	global $gBitSystem, $gBitSmarty, $gBitThemes, $gBitUser, $gQueryUser;

	// users_themes='u' is for all users content
	if( is_a( $pContent, 'LibertyContent' ) && $pContent->getPreference( 'style' ) ) {
		$theme = $pContent->getPreference( 'style' );
	} elseif( $gBitSystem->getConfig( 'users_themes' ) == 'u' ) {
		if( $gBitSystem->isFeatureActive( 'users_preferences' ) && is_object( $pContent ) && $pContent->isValid() ) {
			if( $pContent->getField( 'user_id' ) == $gBitUser->mUserId ) {
				// small optimization to reduce checking when we are looking at our own content, which is frequent
				if( $userStyle = $gBitUser->getPreference( 'theme' )) {
					$theme = $userStyle;
				}
			} else {
				$theme = BitUser::getUserPreference( 'theme', NULL, $pContent->getField( 'user_id' ) );
			}
		}
	}

	if( !empty( $theme ) && $theme != DEFAULT_THEME ) {
		$gBitThemes->setStyle( $theme );
		if( !is_object( $gQueryUser ) ) {
			$userClass = $gBitSystem->getConfig( 'user_class', 'BitPermUser' );
			require_once( USERS_PKG_PATH . $userClass .'.php' );
			$gQueryUser = new $userClass( $pContent->getField( 'user_id' ) );
			$gQueryUser->load();
			$gBitSmarty->assign_by_ref( 'gQueryUser', $gQueryUser );
		}
	}
}

/**
 * themes_content_list
 *
 * @param array $pContent
 * @param array $pListHash
 * @access public
 * @return void
 */
function themes_content_list( $pContent, $pListHash ) {
	global $gBitSystem, $gBitSmarty, $gBitThemes, $gBitUser, $gQueryUser;
	// users_themes='u' is for all users content
	if( $gBitSystem->getConfig( 'users_themes' ) == 'u' ) {
		if( $gBitSystem->isFeatureActive( 'users_preferences' ) && !empty( $pListHash['user_id'] ) ) {
			if( $pListHash['user_id'] == $gBitUser->mUserId ) {
				// small optimization to reduce checking when we are looking at our own content, which is frequent
				if( $userStyle = $gBitUser->getPreference('theme') ) {
					$theme = $userStyle;
				}
			} else {
				$theme = BitUser::getUserPreference( 'theme', NULL, $pListHash['user_id'] );
			}
		}
	}
	if( !empty( $theme ) && $theme != DEFAULT_THEME ) {
		$gBitThemes->setStyle( $theme );
		if( !is_object( $gQueryUser ) ) {
			$userClass = $gBitSystem->getConfig( 'user_class', 'BitPermUser' );
			require_once( USERS_PKG_PATH . $userClass .'.php' );
			$gQueryUser = new $userClass( $pListHash['user_id'] );
			$gQueryUser->load();
			$gBitSmarty->assign_by_ref( 'gQueryUser', $gQueryUser );
		}
	}
}

/* vim: :set fdm=marker : */
?>
