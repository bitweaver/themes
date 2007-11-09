<?php
/**
 * BitThemes 
 * 
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

	// Auxillary Files
	var $mAuxFiles = array();




	/**
	 * Initiate class
	 * 
	 * @return void
	 */
	function BitThemes() {
		BitBase::BitBase();
	}




	// =================== Styles ====================
	/**
	 * load up all style related information
	 * populates mStyle and mStyles
	 * 
	 * @access public
	 * @return void
	 */
	function loadStyle() {
		global $gPreviewStyle, $gBitSystem;
		// setup our theme style and check if a preview theme has been picked
		if( !empty( $gPreviewStyle ) ) {
			deprecated( 'The use of $gPreviewStyle is deprecated. Please use $gBitThemes->setStyle() instead' );
			$this->setStyle( $gPreviewStyle );
		}

		// load default css files
		if( empty( $this->mStyles['styleSheet'] )) {
			$this->mStyles['styleSheet'] = $this->getStyleCss();
		}

		// load tpl files that need to be included
		$this->loadTplFiles( "header_inc" );
		$this->loadTplFiles( "footer_inc" );

		// join together all the javascript files
		$this->loadJavascript( UTIL_PKG_PATH.'javascript/bitweaver.js', TRUE );

		if( !$gBitSystem->isFeatureActive( 'site_disable_jstabs' )) {
			$this->loadJavascript( UTIL_PKG_PATH.'javascript/libs/tabpane.js', TRUE );
		}

		if( !$gBitSystem->getConfig( 'site_disable_fat' )) {
			$this->loadJavascript( UTIL_PKG_PATH.'javascript/libs/fat.js', TRUE );
		}

		if( $gBitSystem->isFeatureActive( 'site_top_bar_js' ) && $gBitSystem->isFeatureActive( 'site_top_bar_dropdown' )) {
			$this->loadJavascript( UTIL_PKG_PATH.'javascript/libs/fsmenu.js', TRUE );
		}
		vd($this->mAuxFiles['js']);

		// let's join the various files
		$this->mStyles['joined_javascript'] = $this->joinAuxFiles( 'js' );

		// this is where we could join additional CSS files as well, but that 
		// cuases problems:
		// CSS files frequently use relative paths to images and such. These 
		// are interpreted as relative to where the CSS file is. If we then 
		// join various CSS files into one and store it somewhere else, all 
		// these paths will break. We could replace given words in CSS files 
		// with the full path, but this is likely to cause problems.
		// - xing - Wednesday Nov 07, 2007   10:51:06 CET
		$this->loadJavascript( $this->getBrowserStyleCss() );
		$this->mStyles['joined_css'] = $this->joinAuxFiles( 'css' );

		// define style url and path
		define( 'THEMES_STYLE_URL', $this->getStyleUrl() );
		define( 'THEMES_STYLE_PATH', $this->getStylePath() );
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
	function getStyleCss( $pStyle = NULL ) {
		global $gBitSystem;
		if( empty( $pStyle )) {
			$pStyle = $this->getStyle();
		}
		$ret = '';

		if( $gBitSystem->getConfig( 'style_variation' ) && is_readable( $this->getStylePath().'/alternate/'.$gBitSystem->getConfig( 'style_variation' ).'.css' )) {
			$ret = $this->getStyleUrl().'/alternate/'.$gBitSystem->getConfig( 'style_variation' ).'.css';
		} elseif( is_readable( $this->getStylePath().'/'.$pStyle.'.css' )) {
			$ret = $this->getStyleUrl().'/'.$pStyle.'.css';
		}
		return str_replace( "//", "/", $ret );
	}

	/**
	* get browser specific css file
	*
	* @param none
	* @return path to browser specific css file
	* @access public
	*/
	function getBrowserStyleCss() {
		global $gSniffer;
		if( file_exists( $this->getStylePath().$this->getStyle().'_'.$gSniffer->property( 'browser' ).'.css' )) {
			$ret = $this->getStylePath().$this->getStyle().'_'.$gSniffer->property( 'browser' ).'.css';
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
		return THEMES_PKG_URL.'styles/'.$pStyle.'/';
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
		return THEMES_PKG_PATH.'styles/'.$pStyle.'/';
	}





	// =================== Layout ====================
	/**
	* load current layout into mLayout
	*
	* @param  $pParamHash
	* @return none
	* @access public
	*/
	//function loadLayout($pUserMixed = ROOT_USER_ID, $pLayout = ACTIVE_PACKAGE, $pFallbackLayout = DEFAULT_PACKAGE, $pForceReload = FALSE) {
	function loadLayout( $pParamHash = NULL ) {
		global $gBitSystem;
		if( empty( $this->mLayout ) || !count( $this->mLayout )){
			$this->mLayout = $this->getLayout( $pParamHash );

			// hideable areas
			$hideable = array( 't' => 'top', 'l' => 'left', 'r' => 'right', 'b' => 'bottom' );

			// this needs to occur after loading the layout to ensure that we don't distrub the fallback process during layout loading
			foreach( $hideable as $layout => $area ) {
				if( $gBitSystem->isFeatureActive( ACTIVE_PACKAGE."_hide_{$area}_col" )) {
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
	//function getLayout( $pUserMixed = null, $pLayout = ACTIVE_PACKAGE, $pFallback = TRUE, $pFallbackLayout = DEFAULT_PACKAGE ) {
	function getLayout( $pParamHash = NULL ) {
		global $gCenterPieces, $gBitUser, $gBitSystem;
		$ret = array( 'l' => NULL, 'c' => NULL, 'r' => NULL );

		$layouts =  array();
		if( !empty( $pParamHash['layout'] ) ) {
			$layouts[]           = $pParamHash['layout'];
		}
		if( !empty( $pParamHash['fallback_layout'] ) ) {
			$layouts[]      = $pParamHash['fallback_layout'];
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

			$gCenterPieces = array();
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
		return $layouts;
	}

	function cloneLayout( $pFromLayout, $pToLayout ) {
		global $gBitSystem;
		$packages   = array_keys( $gBitSystem->mPackages );
		$packages[] = 'home';
		if( !empty( $pFromLayout ) && in_array( $pFromLayout, $packages ) && !empty( $pToLayout ) && in_array( $pToLayout, $packages )) {
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




	// =================== Modules ====================
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

		if( $this->verifyArea( $pHash['layout_area'] )) {
			$pHash['store']['layout_area']   = $pHash['layout_area'];
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
	 * moveModuleToArea 
	 * 
	 * @param array $pModuleId 
	 * @param array $pArea 
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function moveModuleToArea( $pModuleId, $pArea ) {
		if( @BitBase::verifyId( $pModuleId ) && $this->verifyArea( $pArea )) {
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
		if( empty( $pArea ) || !preg_match( '/^[lrctb]$/', $pArea )) {
			$pArea = 'l';
		}
		return TRUE;
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
						list( $source, $file ) = split( '/', $p2DHash[$col][$mod]['module_rsrc'] );
						@list( $rsrc, $package ) = split( ':', $source );
						// handle special case for custom modules
						if( !isset( $package )) {
							$package = $rsrc;
						}
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
		$all_modules = array();

		if(( $modules = $this->getCustomModuleList() ) && $pPrefix == 'mod_' ) {
			foreach( $modules as $m ) {
				$all_modules[tra( 'Custom Modules' )]['_custom:custom/'.$m["name"]] = $m["name"];
			}
		}

		// iterate through all packages and look for all possible modules
		foreach( array_keys( $gBitSystem->mPackages ) as $key ) {
			if( $gBitSystem->isPackageActive( $key )) {
				$loc = BIT_ROOT_PATH.$gBitSystem->mPackages[$key]['dir'].'/'.$pDir;
				if( @is_dir( $loc )) {
					$h = opendir( $loc );
					if( $h ) {
						while (($file = readdir($h)) !== false) {
							if ( preg_match( "/^$pPrefix(.*)\.tpl$/", $file, $match )) {
								$all_modules[ucfirst( $key )]['bitpackage:'.$key.'/'.$file] = str_replace( '_', ' ', $match[1] );
							}
						}
						closedir ($h);
					}
				}
				// we scan temp/<pkg>/modules for module files as well for on the fly generated modules (e.g. nexus)
				if( $pDir == 'modules' ) {
					$loc = TEMP_PKG_PATH.$gBitSystem->mPackages[$key]['dir'].'/'.$pDir;
					if( @is_dir( $loc )) {
						$h = opendir( $loc );
						if( $h ) {
							while (($file = readdir($h)) !== false) {
								if ( preg_match( "/^$pPrefix(.*)\.tpl$/", $file, $match )) {
									$all_modules[ucfirst( $key )]['bitpackage:temp/'.$key.'/'.$file] = str_replace( '_', ' ', $match[1] );
								}
							}
							closedir ($h);
						}
					}
				}
			}
		}

		return $all_modules;
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



	// =================== Custom Modules ====================
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




	// =================== Styles ====================
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
			$pDir = THEMES_PKG_PATH.'styles/';
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
			$pDir = THEMES_PKG_PATH.'styles/';
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
									$ret[$file][$dir][preg_replace( "/\..*/", "", $f )] = THEMES_PKG_URL.basename( dirname( dirname( $infoDir ))).'/'.$file.'/'.$dir.'/'.$f;

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
		$version = BIT_MAJOR_VERSION.BIT_MINOR_VERSION.BIT_SUB_VERSION;

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




	// =================== Miscellaneous Methods ====================
	/**
	* Statically callable function to see if browser supports javascript
	* determined by cookie set in bitweaver.js
	* @access public
	**/
	function isJavascriptEnabled() {
		return( !empty( $_COOKIE['javascript_enabled'] ) && $_COOKIE['javascript_enabled'] == 'y' );
	}

	/**
	* Statically callable function to determine if the current call was made using Ajax
	*
	* @access public
	**/
	function isAjaxRequest() {
		return(( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) || !empty( $_REQUEST['ajax_xml'] ));
	}

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
		global $gBitSmarty, $gSniffer;
		$ret = FALSE;
		$ajaxLib = strtolower( $pAjaxLib );
		if( $this->isJavascriptEnabled() ) {
			// set the javascript lib path if not set yet
			if( empty( $pLibPath )) {
				switch( $ajaxLib ) {
					case 'mochikit':
						$pLibPath = UTIL_PKG_PATH."javascript/libs/MochiKit/";
						break;
					default:
						$pLibPath = UTIL_PKG_PATH."javascript/";
						break;
				}
			}

			if( !$this->isAjaxLib( $ajaxLib )) {
				// mochikit is special
				switch( $ajaxLib ) {
					case 'mochikit':
						$this->loadJavascript( UTIL_PKG_PATH.'javascript/MochiKitBitAjax.js' );
						$this->loadJavascript( $pLibPath.'Base.js' );
						$this->loadJavascript( $pLibPath.'Async.js' );
						break;
					case 'prototype':
						$this->loadJavascript( $pLibPath.'prototype.js' );
						break;
				}
				$this->mAjaxLibs[$ajaxLib] = TRUE;
			}

			if( is_array( $pLibHash )) {
				foreach( $pLibHash as $lib ) {
					$this->loadJavascript( $pLibPath.'/'.$lib, $pPack );
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
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
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
	 * Load an addition javascript file
	 * 
	 * @param string $pJavascriptFile Full path to javascript file
	 * @param boolean $pPack Set to true if you want to pack the javascript file
	 * @access public
	 * @return void
	 */
	function loadJavascript( $pJavascriptFile, $pPack = FALSE ) {
		if( !empty( $pJavascriptFile )) {
			if( $pPack ) {
				if( is_file( $pJavascriptFile )) {
					// get a name for the cache file we're going to store
					$cachefile = md5( $pJavascriptFile ).'.js';

					// start up caching engine
					if( empty( $this->mThemeCache )) {
						require_once( KERNEL_PKG_PATH.'BitCache.php' );
						$this->mThemeCache = new BitCache( 'themes', TRUE );
					}

					// if the file hasn't been packed and cached yet, we do that now.
					if( !$this->mThemeCache->isCached( $cachefile, filemtime( $pJavascriptFile ))) {
						require_once( UTIL_PKG_PATH.'javascript/class.JavaScriptPacker.php' );
						$packer = new JavaScriptPacker( file_get_contents( $pJavascriptFile ) );
						$this->mThemeCache->writeCacheFile( $cachefile, $packer->pack() );
					}

					// update javascript file with new path
					$pJavascriptFile = $this->mThemeCache->getCacheFile( $cachefile );
				}
			}

			$this->loadAuxFile( $pJavascriptFile, 'js' );
		}
	}

	/**
	 * Load an additional CSS file
	 * 
	 * @param array $pCssFile Full path to CSS file 
	 * @access public
	 * @return void
	 */
	function loadCss( $pCssFile ) {
		$this->loadAuxFile( $pCssFile, 'css' );
	}

	/**
	 * joinAuxFiles will join all files in mAuxFiles[hash] into one cached file. This helps keep our HTTP requests down to a minimum.
	 * 
	 * @param string $pType specifies what files to join. typical values include 'javascript', 'css'
	 * @access private
	 * @return url to cached file
	 */
	function joinAuxFiles( $pType = 'js' ) {
		$ret = FALSE;
		if( !empty( $this->mAuxFiles[$pType] ) && is_array( $this->mAuxFiles[$pType] )) {
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

			// start up caching engine
			if( empty( $this->mThemeCache )) {
				require_once( KERNEL_PKG_PATH.'BitCache.php' );
				$this->mThemeCache = new BitCache( 'themes', TRUE );
			}

			if( !$this->mThemeCache->isCached( $cachefile, $lastmodified )) {
				$contents = '';
				foreach( $this->mAuxFiles[$pType] as $file ) {
					// if we have an extension to check against, we'll do that
					$chars = 0 - ( strlen( $pType ) + 1 );
					if( !empty( $pType ) && substr( $file, $chars ) == '.'.$pType && is_readable( $file )) {
						$contents .= file_get_contents( $file )."\n\n";
					}
				}
				$this->mThemeCache->writeCacheFile( $cachefile, $contents );
			}

			$ret = $this->mThemeCache->getCacheUrl( $cachefile );
		}
		return $ret;
	}

	/**
	 * loadAuxFile will add a file to the mAuxFiles hash for later processing
	 * 
	 * @param array $pFile Full path to the file in question
	 * @param array $pType 
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function loadAuxFile( $pFile = NULL, $pType = NULL ) {
		if( !empty( $pFile ) && !empty( $pType )) {
			if( $pFile = realpath( $pFile )) {
				if( !$this->isAuxFile( $pFile, $pType )) {
					$this->mAuxFiles[$pType][] = $pFile;
				}
			}
		}
	}

	/**
	 * isAuxFile 
	 * 
	 * @param array $pFile Full path to file
	 * @param array $pType 
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function isAuxFile( $pFile, $pType ) {
		if( !empty( $pFile ) && !empty( $pType ) && !empty( $this->mAuxFiles[$pType] )) {
			return( in_array( $pFile, $this->mAuxFiles[$pType] ));
		}
	}




	// =================== old code ====================
	// deprecated stuff and temporary place holders
	// 																		--------------- all of these functions will be removed quite soon
	function storeLayout() {
		deprecated( 'Please remove this function and use storeModule instead' );
	}
	function storeModuleParameters($mod_rsrc, $user_id, $params) {
		deprecated( 'This method does not work as expected due to changes in the layout schema. we have not found a suitable replacement yet.' );
	}
	function getModuleId($mod_rsrc) {
		deprecated( 'This method does not work as expected due to changes in the layout schema. we have not found a suitable replacement yet.' );
	}
}
?>
