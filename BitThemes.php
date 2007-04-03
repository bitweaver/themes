<?php

class BitThemes extends BitBase {
	// Array that contains a full description of the current layout
	var $mLayout = array();

	function BitThemes() {
		BitBase::BitBase();
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

			// this needs to occur here to ensure that we don't distrub the fallback process during layout loading
			if( $gBitSystem->isFeatureActive( ACTIVE_PACKAGE.'_hide_left_col' ) ) {
				unset( $this->mLayout['l'] );
			}
			if( $gBitSystem->isFeatureActive( ACTIVE_PACKAGE.'_hide_right_col' ) ) {
				unset( $this->mLayout['r'] );
			}
		}
	}

	/**
	 * get the current layout from the database
	 * 
	 * @param array $pParamHash 
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	//function getLayout( $pUserMixed = null, $pLayout = ACTIVE_PACKAGE, $pFallback = TRUE, $pFallbackLayout = DEFAULT_PACKAGE ) {
	function getLayout( $pParamHash = NULL ) {
		global $gCenterPieces, $gBitUser, $gBitSystem;
		$ret = array( 'l' => NULL, 'c' => NULL, 'r' => NULL );
		$bindVars = array();

		$pParamHash['layout']           = ( !empty( $pParamHash['layout'] ) ? $pParamHash['layout'] : ACTIVE_PACKAGE );
		$pParamHash['fallback']         = (( isset( $pParamHash['fallback'] ) && $pParamHash['fallback'] === FALSE ) ? FALSE : TRUE );
		$pParamHash['fallback_layout']  = ( !empty( $pParamHash['fallback_layout'] ) ? $pParamHash['fallback_layout'] : DEFAULT_PACKAGE );

		// This query will always pull ALL of the ACTIVE_PACKAGE _and_ DEFAULT_PACKAGE modules (in that order)
		// This saves a count() query to see if the ACTIVE_PACKAGE has a layout, since it usually probably doesn't
		// I don't know if it's better or not to save the count() query and retrieve more data - my gut says so,
		// but i've done no research - spiderr
		if( $pParamHash['fallback'] && $pParamHash['layout'] != DEFAULT_PACKAGE && $this->dType != 'firebird' && $this->dType != 'mssql'  && $this->dType != 'oci8'  && $this->dType != 'oci8po' ) {
			// ORDER BY comparison is crucial so current layout modules come up first
			$whereClause = " (tl.`layout`=? OR tl.`layout`=?) ORDER BY tl.`layout`=? DESC, ";
			$bindVars[] = $pParamHash['layout'];
			$bindVars[] = $pParamHash['fallback_layout'];
			$bindVars[] = $pParamHash['layout'];
		} elseif( $pParamHash['fallback'] && $pParamHash['layout'] != DEFAULT_PACKAGE ) {
			// ORDER BY is crucial so current layout modules come up first
			$whereClause = " (tl.`layout`=? OR tl.`layout`=?) ORDER BY tl.`layout` DESC, ";
			$bindVars[] = $pParamHash['layout'];
			$bindVars[] = $pParamHash['fallback_layout'];
		} elseif( $pParamHash['layout'] ) {
			$whereClause = " tl.`layout`=? ORDER BY ";
			array_push( $bindVars, $pParamHash['layout'] );
		}

		$query = "
			SELECT tl.*
			FROM `".BIT_DB_PREFIX."themes_layouts` tl
			WHERE $whereClause ".$this->mDb->convertSortmode( "pos_asc" );

		if( $result = $this->mDb->query( $query, $bindVars )) {
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
					// convert groups string to hash
					if( preg_match( '/[A-Za-z]/', $row["groups"] )) {
						// old style serialized group names
						$row["module_groups"] = array();
						if( $grps = @unserialize( $row["groups"] )) {
							foreach( $grps as $grp ) {
								global $gBitUser;
								if( !( $groupId = array_search( $grp, $gBitUser->mGroups ))) {
									if( $gBitUser->isAdmin() ) {
										$row["module_groups"][] = $gBitUser->groupExists( $grp, '*' );
									}
								}

								if( @$this->verifyId( $groupId )) {
									$row["module_groups"][] = $groupId;
								}
							}
						}
					} else {
						// new imploded style
						$row["module_groups"] = explode( ' ', $row["groups"] );
					}

					if( $gBitUser->isAdmin() ) {
						$row["visible"] = TRUE;
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
		$pHash['store']['pos']           = ( @is_numeric( $pHash['pos'] )          ? $pHash['pos']           : NULL );

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
		if( @BitBase::verifyId( $pModuleId ) ) {
			// first we get next module we want to swap with
			$moduleData = $this->getModuleData( $pModuleId );
			$query  = "SELECT MAX(`module_id`) FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `layout_area`=? AND `pos`<=? AND `module_id`<>?";
			$swapModuleId = $this->mDb->getOne( $query, array( $moduleData['layout_area'], $moduleData['pos'], $moduleData['module_id'] ));
			if( $moduleSwap = $this->getModuleData( $swapModuleId )) {
				if( $moduleData['pos'] == $moduleSwap['pos'] ) {
					$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `pos`=`pos`-1 WHERE `module_id`=?";
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
	 * moduleDown 
	 * 
	 * @param array $pModuleId 
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function moveModuleDown( $pModuleId ) {
		if( @BitBase::verifyId( $pModuleId ) ) {
			// first we get next module we want to swap with
			$moduleData = $this->getModuleData( $pModuleId );
			$query  = "SELECT MIN(`module_id`) FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `layout_area`=? AND `pos`>=? AND `module_id`<>?";
			$swapModuleId = $this->mDb->getOne( $query, array( $moduleData['layout_area'], $moduleData['pos'], $moduleData['module_id'] ));
			if( $moduleSwap = $this->getModuleData( $swapModuleId )) {
				if( $moduleData['pos'] == $moduleSwap['pos'] ) {
					$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `pos`=`pos`+1 WHERE `module_id`=?";
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
		if( empty( $pArea ) || !preg_match( '/^[lrcbt]$/', $pArea )) {
			$pArea = 'l';
		}
		return TRUE;
	}

	/**
	 * generateModuleNames 
	 * 
	 * @param array $p2DHash 
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function generateModuleNames( &$p2DHash ) {
		if( is_array( $p2DHash ) ) {
			// Generate human friendly names
			foreach( array_keys( $p2DHash ) as $col ) {
				if( count( $p2DHash[$col] ) ) {
					foreach( array_keys( $p2DHash["$col"] ) as $mod ) {
						list($source, $file) = split( '/', $p2DHash[$col][$mod]['module_rsrc'] );
						@list($rsrc, $package) = split( ':', $source );
						// handle special case for custom modules
						if( !isset( $package ) ) {
							$package = $rsrc;
						}
						$file = str_replace( 'mod_', '', $file );
						$file = str_replace( '.tpl', '', $file );
						$p2DHash[$col][$mod]['name'] = $package.' -> '.str_replace( '_', ' ', $file );
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
			if( $gBitSystem->isPackageActive( $key ) ) {
				$loc = BIT_ROOT_PATH.$gBitSystem->mPackages[$key]['dir'].'/'.$pDir;
				if( @is_dir( $loc ) ) {
					$h = opendir( $loc );
					if( $h ) {
						while (($file = readdir($h)) !== false) {
							if ( preg_match( "/^$pPrefix(.*)\.tpl$/", $file, $match ) ) {
								$all_modules[ucfirst( $key )]['bitpackage:'.$key.'/'.$file] = str_replace( '_', ' ', $match[1] );
							}
						}
						closedir ($h);
					}
				}
				// we scan temp/<pkg>/modules for module files as well for on the fly generated modules (e.g. nexus)
				if( $pDir == 'modules' ) {
					$loc = TEMP_PKG_PATH.$gBitSystem->mPackages[$key]['dir'].'/'.$pDir;
					if( @is_dir( $loc ) ) {
						$h = opendir( $loc );
						if( $h ) {
							while (($file = readdir($h)) !== false) {
								if ( preg_match( "/^$pPrefix(.*)\.tpl$/", $file, $match ) ) {
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

		if( empty( $pDir ) ) {
			$pDir = THEMES_PKG_PATH.'styles/';
		}
		$ret = array();

		if( !empty( $pNullOption ) ) {
			$ret[] = '';
		}

		if( is_dir( $pDir ) ) {
			$h = opendir( $pDir );
			while( $file = readdir( $h ) ) {
				if ( is_dir( $pDir."$file" ) && ( $file != '.' && $file != '..' && $file != 'CVS' && $file != 'slideshows' && $file != 'blank' ) ) {
					$ret[] = $file;
				}
			}
			closedir( $h );
		}

		if( $bIncludeCustom && $gBitSystem->getConfig( 'themes_edit_css' ) ) {	
			// Include the users custom css if they have created one
			$customCSSPath = $gBitUser->getStoragePath( NULL,$gBitUser->mUserId );
			$customCSSFile = $customCSSPath.'custom.css';

			if (file_exists($customCSSFile)) {
				$ret[] = 'custom';
			}
		}

		if( count( $ret ) ) {
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

		if( is_dir( THEMES_PKG_PATH.'layouts/' ) ) {
			$h = opendir( THEMES_PKG_PATH.'layouts/' );
			// collect all layouts
			while( FALSE !== ( $file = readdir( $h ) ) ) {
				if ( !preg_match( "/^\./", $file ) ) {
					$ret[substr( $file, 0, ( strrpos( $file, '.' ) ) )][substr( $file, ( strrpos( $file, '.' ) + 1 ) )] = $file;
				}
			}
			closedir( $h );

			// weed out any files that don't have a css file associated with them
			foreach( $ret as $key => $layout ) {
				if( empty( $layout['css'] ) ) {
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

		if( empty( $pSubDirs ) ) {
			$subDirs[] = array( '' );
		} elseif( !is_array( $pSubDirs ) ) {
			$subDirs[] = $pSubDirs;
		} else {
			$subDirs = $pSubDirs;
		}

		if( empty( $pDir ) ) {
			$pDir = THEMES_PKG_PATH.'styles/';
		}

		if( !empty( $pNullOption ) ) {
			$ret[] = '';
		}

		// open directories
		if( is_dir( $pDir ) ) {
			$h = opendir( $pDir );
			// cycle through files / dirs
			while( FALSE !== ( $file = readdir( $h ) ) ) {
				if ( is_dir( $pDir.$file ) && ( $file != '.' && $file != '..' && $file != 'CVS' && $file != 'slideshows' && $file != 'blank' ) ) {
					$ret[$file]['style'] = $file;
					// check if we want to have a look in any subdirs
					foreach( $subDirs as $dir ) {
						if( is_dir( $infoDir = $pDir.$file.'/'.$dir.'/' ) ) {
							$dh = opendir( $infoDir );
							// cycle through files / dirs
							while( FALSE !== ( $f = readdir( $dh ) ) ) {
								if( is_readable( $infoDir.$f ) && ( $f != '.' &&  $f != '..' &&  $f != 'CVS' ) ) {
									$ret[$file][$dir][preg_replace( "/\..*/", "", $f )] = THEMES_PKG_URL.basename( dirname( dirname( $infoDir ) ) ).'/'.$file.'/'.$dir.'/'.$f;

									if( preg_match( "/\.htm$/", $f ) ) {
										$fh = fopen( $infoDir.$f, "r" );
										$ret[$file][$dir][preg_replace( "/\.htm$/", "", $f )] = fread( $fh, filesize( $infoDir.$f ) );
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

		if( count( $ret ) ) {
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
		global $gSniffer, $gBitSystem;
		$cachedir = TEMP_PKG_PATH.'themes/biticon/'.$gBitSystem->getConfig( 'site_icon_style', DEFAULT_ICON_STYLE ).'/';
		if( !is_dir( $cachedir )) {
			mkdir_p( $cachedir );
		}
		return $cachedir;
	}




	// =================== old modules code ====================
	// deprecated stuff and temporary place holders
	// 																		--------------- all of these functions will be removed quite soon
	function storeLayout() {
		deprecated( 'Please remove this function and use storeModule instead' );
	}

	function storeModuleParameters($mod_rsrc, $user_id, $params) {
		deprecated( 'This method does not work as expected due to changes in the layout schema. we have not found a suitable replacement yet.' );

		if (!is_numeric($mod_rsrc))
			$module_id = $this->getModuleId($mod_rsrc);
		else
			$module_id = $mod_rsrc;

		$paramsStr = '';

		foreach ($params as $setting=>$value) {
			$paramsStr .= "$setting=$value;";
		}

		if (!$module_id)
			return FALSE;

		if ($user_id) {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `params` = ?  WHERE `module_id` = ? AND `user_id` = ?";
			$result = $this->mDb->query($query, array($paramsStr, $module_id, $user_id));
		} else {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts_modules` SET `params` = ? WHERE `module_id` = ?";
			$result = $this->mDb->query($query, array($paramsStr, $module_id));
		}

		return TRUE;
	}

	function getModuleId($mod_rsrc) {
		deprecated( 'This method does not work as expected due to changes in the layout schema. we have not found a suitable replacement yet.' );

		$query = "SELECT `module_id`
			  FROM `".BIT_DB_PREFIX."themes_module_map`
			  WHERE `module_rsrc` = ?";
		$ret = $this->mDb->getOne($query, array($mod_rsrc));
		return $ret;
	}

	/* =============== UNUSED FUNCTIONS ================ can be removed soon - xing
	function getModuleParameters($mod_rsrc, $user_id = ROOT_USER_ID ) {
		deprecated( 'This method does not work as expected due to changes in the layout schema. we have not found a suitable replacement yet.' );

		// First we try to get preferences at the per-user level (e.g. from themes_layouts table)
		$query = "SELECT tl.`params`, tl.`module_rows`
				  FROM `".BIT_DB_PREFIX."themes_layouts` tl, `".BIT_DB_PREFIX."themes_module_map` tmm
				  WHERE tmm.`module_rsrc` = ? AND tl.`user_id` = ? AND tmm.`module_id` = tl.`module_id`";
		$row = $this->mDb->getRow($query,array($mod_rsrc, $user_id));

		$params = array();

		if( empty( $row['params'] ) ) {
			// No per-user preferences were stored for this user so we will pull the default parameters
			$query = "SELECT tlm.`params`, tlm.`module_rows`
				  FROM `".BIT_DB_PREFIX."themes_layouts_modules` tlm, `".BIT_DB_PREFIX."themes_module_map` tmm
				  WHERE tmm.`module_rsrc` = ? AND tmm.`module_id` = tlm.`module_id`";
			$row = $this->mDb->getRow($query,array($mod_rsrc));
		}
		if( !empty( $row['params'] ) ) {
			$tok = strtok($row['params'],';');
			while ($tok) {
				$pref = explode('=',$tok);
					if (count($pref) >= 2)
						$params[$pref[0]] = $pref[1];
				$tok = strtok(';');
			}
		}

		$params['module_rows'] = (!empty($row['module_rows'] ) ? $row['module_rows'] : 10);	// interim hack - drewslater

		return $params;
	}

	function replaceCustomModule($name, $title, $data) {
		if ((!empty($name)) && (!empty($title)) && (!empty($data))) {
			$query = "delete from `".BIT_DB_PREFIX."themes_custom_modules` where `name`=?";
			$result = $this->mDb->query($query,array($name));
			$query = "insert into `".BIT_DB_PREFIX."themes_custom_modules`(`name`,`title`,`data`) values(?,?,?)";

			$result = $this->mDb->query($query,array($name,$title,$data));
			return true;
		}
	}

	function verifyModuleParams( &$pHash ) {
		$pHash = array(
			'module_rsrc'  => $pHash['module_rsrc'],
			'availability' => ( !empty( $pHash['availability'] )     ? $pHash['availability'] : NULL ),
			'params'       => ( !empty( $pHash['params'] )           ? $pHash['params']       : NULL ),
			'title'        => ( !empty( $pHash['title'] )            ? $pHash['title']        : NULL ),
			'layout'       => ( !empty( $pHash['layout'] )           ? $pHash['layout']       : 'kernel' ),
			'module_rows'  => ( @is_numeric( $pHash['module_rows'] ) ? $pHash['module_rows']  : NULL ),
			'cache_time'   => ( @is_numeric( $pHash['cache_time'] )  ? $pHash['cache_time']   : NULL ),
			'ord'          => ( @is_numeric( $pHash['ord'] )         ? $pHash['ord']          : NULL ),
			'pos'          => ( @is_string( $pHash['pos'] )          ? $pHash['pos']          : NULL ),
		);

		if( empty( $pHash['groups'] )) {
			$pHash['groups'] = NULL;
		} elseif( is_array( $pHash['groups'] )) {
			$pHash['groups'] = implode( ' ', $pHash['groups'] );
		}

		// get a module_id if the module is already available in the map
		if( empty( $pHash['module_id'] ) || !is_numeric( $pHash['module_id'] )) {
			$pHash['module_id'] = $this->getModuleId( $pHash['module_rsrc'] );
		}

		// If this module is not listed in the module map we add it
		if( !@BitBase::verifyId( $pHash['module_id'] )) {
			$pHash['module_id'] = $this->mDb->GenID( 'themes_module_map_module_id_seq' );
			$query = "INSERT INTO `".BIT_DB_PREFIX."themes_module_map` (`module_id`, `module_rsrc`) VALUES ( ?, ? )";
			$result = $this->mDb->query( $query, array( $pHash['module_id'], $pHash['module_rsrc'] ));
		}

		return TRUE;
	}

	function storeModule( &$pHash ) {
		if( $this->verifyModuleParams( $pHash )) {
			$query = 'SELECT COUNT(*) AS "count" FROM `'.BIT_DB_PREFIX.'themes_layouts_modules` WHERE `module_id`=?';
			$modCount = $this->mDb->getOne( $query, array( $pHash['module_id'] ));

			$bindVars = array( $pHash['availability'], $pHash['title'], $pHash['cache_time'], $pHash['module_rows'],  $pHash['groups'], $pHash['module_id'] );

			if( $modCount > 0 ) {
				$query = "
					UPDATE `".BIT_DB_PREFIX."themes_layouts_modules`
					SET `availability`=?, `title`=?, `cache_time`=?, `module_rows`=?, `groups`=?
					WHERE `module_id`=?";
			} else {
				$query = "
					INSERT INTO `".BIT_DB_PREFIX."themes_layouts_modules`
					( `availability`, `title`, `cache_time`, `module_rows`, `groups`, `module_id` )
					VALUES ( ?, ?, ?, ?, ?, ? )";
			}
			$result = $this->mDb->query( $query, $bindVars );

			// update parameters
			if( $pHash['layout'] == 'kernel' ) {
				$this->mDb->query( "UPDATE `".BIT_DB_PREFIX."themes_layouts_modules` SET `params`=? WHERE `module_id`=?", array( $pHash['params'], $pHash['module_id'] ) );
			}
		}
	}

	function verifyLayoutParams( &$pHash ) {
		$ret = TRUE;
		if( empty( $pHash['ord'] ) ) $pHash['ord'] = NULL;

		if( (empty( $pHash['module_id'] ) || !is_numeric( $pHash['module_id'] )) && isset( $pHash['module_rsrc'] ) ) {
			$query = "SELECT `module_id` FROM `".BIT_DB_PREFIX."themes_module_map` WHERE `module_rsrc`=?";
			$pHash['module_id'] = $this->mDb->getOne( $query, array( $pHash['module_rsrc'] ) );
		}

		if( empty( $pHash['pos'] ) ) {
			$ret = FALSE;
		}

		return $ret;
	}

	function storeLayout( $pHash ) {
		if( $this->verifyLayoutParams( $pHash ) ) {
			if( !isset( $pHash['params'] ) ) {
				$pHash['params'] = NULL;
			}

			$query = "DELETE FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `user_id`=? AND `layout`=? AND `module_id`=? AND `ord`=?";
			$result = $this->mDb->query( $query, array( $pHash['user_id'], $pHash['layout'], (int)$pHash['module_id'], $pHash['ord'] ) );
			//check for valid values
			// kernel layout (site default) params are stored in themes_layouts_modules
			if( $pHash['layout'] == 'kernel' ) {
				$pHash['params'] = NULL;
			}

			$query = "INSERT INTO `".BIT_DB_PREFIX."themes_layouts`
				(`user_id`, `module_id`, `layout_position`, `ord`, `params`, `layout`)
				VALUES (?,?,?,?,?,?)";
			$result = $this->mDb->query( $query, array( $pHash['user_id'], $pHash['module_id'], $pHash['pos'], (int)$pHash['ord'], $pHash['params'], $pHash['layout'] ) );
		}
		return true;
	}
	 */

	/*
	// part of Drag and Drop
	function verifyBatch( &$pParamHash ) {
		// initialise variables
		$pParamHash['store']['modules'] = array();
		$pParamHash['store']['layout'] = array();

		// prepare all modules parameters for storage
		if( !empty( $pParamHash['modules'] ) ) {
			foreach( $pParamHash['modules'] as $module_id => $params ) {
				// all values that makes sense from themes_layouts_modules are here
				// perhaps we can rewrite verifyModuleParams to work with this thing here.
				$paramOptions = array( 'module_rows', 'title', 'params', 'cache_time' );
				foreach( $paramOptions as $option ) {
					// we need to be able to set things in the db to NULL, if the user wants to nuke some settings
					if( isset( $params[$option] ) ) {
						$pParamHash['store']['modules'][$module_id][$option] = !empty( $params[$option] ) ? $params[$option] : NULL;
					}
				}
			}
		}

		// unserialize() code from drag / drop interface
		// our serializer in js doesn't support multi level serialisation, so we have to unserialize each sub array manually.
		if( !empty( $pParamHash['side_columns'] ) || !empty( $pParamHash['center_column'] ) ) {
			$side_columns = ( unserialize( $pParamHash['side_columns'] ) );
			foreach( $side_columns as $col => $modules ) {
				$side_columns[$col] = unserialize( $modules );
			}
			$center_column = ( unserialize( $pParamHash['center_column'] ) );
			foreach( $center_column as $col => $modules ) {
				$center_column[$col] = unserialize( $modules );
			}
			$newLayout = array_merge( $side_columns, $center_column );
		}

		global $gBitSystem;
		// compare $pParamHash['user_id'] with $gBitUser - otherwise a user could simply set user_id in url and screw up someone elses layout.
		$bitLayout = $gBitSystem->getLayout( ( ( !empty( $pParamHash['user_id'] ) && ( $gBitUser->mUserId == $pParamHash['user_id'] ) ) ? $pParamHash['user_id'] : ROOT_USER_ID ), $_REQUEST['module_package'], FALSE );

		// prepare modules positional data for storage
		if( !empty( $bitLayout ) && !empty( $newLayout ) ) {
			// cycle through both layouts and join the information
			foreach( $bitLayout as $area => $column ) {
				if( !empty( $column ) ) {
					foreach( $column as $module ) {
						foreach( $newLayout as $_area => $_column ) {
							foreach( $_column as $_pos => $_module ) {
								// module_id-<id> is the information fed from the drag / drop javascript
								if( preg_match( "/^module_id-".$module['module_id']."$/", $_module ) ) {
									$pParamHash['store']['layout'][$module['module_id']]['ord'] = $_pos + 1;
									$pParamHash['store']['layout'][$module['module_id']]['layout_position'] = substr( $_area, 0, 1 );
								}
							}
						}
					}
				}
			}
		}
		return TRUE;
	}

	// part of Drag and Drop
	function storeModulesBatch( $pParamHash ) {
		if( $this->verifyBatch( $pParamHash ) ) {
			foreach( $pParamHash['store']['layout'] as $module_id => $storeModule ) {
				$table = "`".BIT_DB_PREFIX."themes_layouts`";
				$this->mDb->associateUpdate( $table, $storeModule, array( 'module_id' => $module_id ) );
			}
			foreach( $pParamHash['store']['modules'] as $module_id => $storeModule ) {
				$table = "`".BIT_DB_PREFIX."themes_layouts_modules`";
				$this->mDb->associateUpdate( $table, $storeModule, array( 'module_id' => $module_id ) );
			}
		}
		return TRUE;
	}

	function getAssignedModules( $name ) {
		$query = "select tlm.*, count() from `".BIT_DB_PREFIX."themes_layouts_modules` where `name`=?";

		$result = $this->mDb->query($query,array($name));
		$res = $result->fetchRow();

		// handle old style serialized group names for legacy data
		if( preg_match( '/[A-Za-z]/', $res["groups"] ) ) {
			static $getAllModulesallGroups;
			if( empty( $allGroups ) ) {
				$allGroups = $gBitUser->getAllUserGroups( ROOT_USER_ID );
			}
			$allGroupNames = array();
			foreach( array_keys( $allGroups ) as $groupId ) {
				array_push( $allGroupNames, $allGroups[$groupId] );
			}
			if( $modGroups = @unserialize( $res["groups"] ) ) {
				foreach( $grps as $groupName ) {
					if( $searchId = array_search( $groupName, $allGroupNames ) ) {
						$res["groups"] = $searchId.' ';
					}
				}
			}
		}
		$res["groups"] = explode( trim( $res["groups"] ), ' ' );

		return $res;
	}

	function disableModule( $pModuleName ) {
		$query = "SELECT `module_id` FROM `".BIT_DB_PREFIX."themes_module_map` WHERE `module_rsrc`=?";
		if( $module_id = $this->mDb->getOne($query,array($pModuleName)) ) {
			$query = "DELETE FROM `".BIT_DB_PREFIX."themes_layouts_modules` WHERE `module_id`=?";
			$result = $this->mDb->query($query,array($module_id));
		}
	}

	function assignModule( $pModuleId, $pUserId, $pLayout, $pPosition, $pOrder = 0, $securityOK = FALSE ) {
		global $gBitUser;
		// security check
		if( ($gBitUser->isAdmin() || $securityOK || ( $gBitUser->mUserId==$pUserId )) && is_numeric( $pModuleId ) ) {
			$this->unassignModule( $pModuleId, $pUserId, $pLayout );
			$query = "INSERT INTO `".BIT_DB_PREFIX."themes_layouts` (`user_id`, `module_id`, `layout`, `layout_position`, `ord`) VALUES(?,?,?,?,?)";
			$result = $this->mDb->query( $query, array( $pUserId, (int)$pModuleId, $pLayout, $pPosition, $pOrder ) );
		}
	}

	function removeAllLayoutModules( $pUserId = NULL, $pLayout = NULL, $securityOK = FALSE) {
		global $gBitUser;
		if ( ($gBitUser->isAdmin() || $securityOK || ( $gBitUser->mUserId == $pUserId )) && ($pUserId && $pLayout)) {
			$query = "DELETE FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `user_id` = ? AND `layout` = ?";
			$result = $this->mDb->query( $query, array($pUserId, $pLayout));
		}
	}

	function unassignModule( $pModuleId, $pUserId = NULL, $pLayout = NULL, $pOrder = NULL ) {
		global $gBitUser;
		global $gQueryUser;

		if (!is_numeric($pModuleId)) {
			$pModuleId = $this->getModuleId($pModuleId);
			if (!$pModuleId)
				return FALSE;
		}
		$binds = array((int)$pModuleId);

		if ($pUserId) {
			$userSql = " AND `user_id` = ? ";
			array_push($binds, (int)$pUserId);
		} else {
			$userSql = '';
		}

		$layoutSql = '';

		if ($pLayout) {
			$layoutSql .= " AND `layout` = ? ";
			array_push($binds, $pLayout);
		}

		if( $pOrder ) {
			$layoutSql .= " AND `ord` = ? ";
			array_push($binds, $pOrder);
		}

	// security check
		if( ($gBitUser->isAdmin() || ( $pUserId && $gBitUser->mUserId==$pUserId ) || $gBitUser->object_has_permission( $gBitUser->mUserId, $gQueryUser->mInfo['content_id'], 'bituser', 'p_users_admin') ) && is_numeric( $pModuleId ) ) {
			$query = "DELETE FROM `".BIT_DB_PREFIX."themes_layouts` where `module_id`=? $userSql $layoutSql";
			$result = $this->mDb->query( $query, $binds );
		}
		return true;
	}

	function moduleUp( $pModuleId, $pUserId, $pLayout ) {
		if( is_numeric( $pModuleId ) ) {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `ord`=`ord`-1 WHERE `module_id`=? AND `user_id`=? AND `layout`=?";
			$result = $this->mDb->query( $query, array( $pModuleId, $pUserId, $pLayout ) );
		}
		return TRUE;
	}

	function moduleDown( $pModuleId, $pUserId, $pLayout ) {
		if( is_numeric( $pModuleId ) ) {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `ord`=`ord`+1 WHERE `module_id`=? AND `user_id`=? AND `layout`=?";
			$result = $this->mDb->query( $query, array( $pModuleId, $pUserId, $pLayout ) );
		}
		return TRUE;
	}

	function moduleOrder( $pModuleId, $pUserId, $pLayout, $pOrder ) {
		if( is_numeric( $pModuleId ) ) {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `ord`=? WHERE `module_id`=? AND `user_id`=? AND `layout`=?";
			$result = $this->mDb->query( $query, array( $pOrder, $pModuleId, $pUserId, $pLayout ) );
		}
	}

	function modulePosition( $pModuleId, $pUserId, $pLayout, $pPosition ) {
		if( is_numeric( $pModuleId ) ) {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `layout_position`=? WHERE `module_id`=? AND `user_id`=? AND `layout`=?";
			$result = $this->mDb->query( $query, array( $pPosition, $pModuleId, $pUserId, $pLayout ) );
		}
	}

	function hasAssignedModules( $iUserMixed ) {
		if( is_numeric( $iUserMixed ) ) {
			$query = "SELECT count(`module_id`) FROM `".BIT_DB_PREFIX."themes_layouts` where `user_id`=?";
		} else {
			$query = "SELECT count(tl.`module_id`)
					  FROM `".BIT_DB_PREFIX."themes_layouts` tl, `".BIT_DB_PREFIX."users_users` uu
					  WHERE tl.`user_id`=uu.`user_id` AND uu.`login`=?";
		}
		$result = $this->mDb->getOne( $query, array( $iUserMixed ) );

		return $result;
	}


	function getAssignableModules() {
		global $gBitUser;

		$ret = array( 'center'=>array(), 'border'=>array() );
		$query = "SELECT tmm.`module_rsrc`, tlm.*
	              FROM `".BIT_DB_PREFIX."themes_layouts_modules` tlm, `".BIT_DB_PREFIX."themes_module_map` tmm
				  WHERE tmm.`module_id` = tlm.`module_id` ORDER BY `module_rsrc`";
		$result = $this->mDb->query( $query );
		while( $row = $result->fetchRow() ) {
			if( preg_match( '/center_/', $row['module_rsrc'] ) ) {
				$subArray = 'center';
			} else {
				$subArray = 'border';
			}
			$row['name'] = $this->convertResourceToName( $row['module_rsrc'] );

			// handle old style serialized group names for legacy data
			if( preg_match( '/[A-Za-z]/', $row["groups"] ) ) {
				static $allGroups;
				if( empty( $allGroups ) ) {
					$allGroups = $gBitUser->getAllUserGroups( ROOT_USER_ID );
				}
				$allGroupNames = array();
				foreach( array_keys( $allGroups ) as $groupId ) {
					$allGroupNames["$groupId"] = $allGroups[$groupId]["group_name"];
				}
				if( $modGroups = @unserialize( $row["groups"] ) ) {
					foreach( $modGroups as $groupName ) {
						if( $searchId = array_search( $groupName, $allGroupNames ) ) {
							$row["groups"] = $searchId.' ';
						}
					}
				}
			}

			$row["groups"] = trim( $row["groups"] );
			if( !empty( $row["groups"] ) ) {
				$row["groups"] = explode( ' ', $row["groups"] );
			}
			if ( $gBitUser->isAdmin() || !empty( $row['groups'] ) || (is_array($row['groups']) && in_array($gBitUser->mGroups, $row['groups'])) ) {
				array_push( $ret[$subArray], $row );
			}
		}
		return $ret;
	}

	function convertResourceToName( $iRsrc ) {
		if( is_string( $iRsrc ) ) {
			// Generate human friendly names
			list($source, $file) = split( '/', $iRsrc );
			list($rsrc, $package) = split( ':', $source );
			$file = str_replace( 'mod_', '', $file );
			$file = str_replace( 'center_', '', $file );
			$file = str_replace( '.tpl', '', $file );
			return( $package.' -> '.str_replace( '_', ' ', $file ) );
		}
	}
	 */

	/*
	function getCustomModule($name) {
		$query = "select * from `".BIT_DB_PREFIX."themes_custom_modules` where `name`=?";
		$result = $this->mDb->query($query,array($name));
		$res = $result->fetchRow();
		return $res;
	}

	function removeCustomModule($name) {
		$moduleId = $this->getModuleId('_custom:custom/'.$name);

		if ($moduleId) {
			$this->unassignModule($moduleId);
			$query = " delete from `".BIT_DB_PREFIX."themes_custom_modules` where `name`=?";
			$result = $this->mDb->query($query,array($name));
			$query = " DELETE FROM `".BIT_DB_PREFIX."themes_layouts_modules` where `module_id` = ?";
			$result = $this->mDb->query($query, array($moduleId));
		}

		return true;
	}

	function listCustomModules() {
		global $gBitSystem;
		$retval = array();
		$query = "select * from `".BIT_DB_PREFIX."themes_custom_modules`";

		$result = $this->mDb->query($query,array());
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."themes_custom_modules`";
		$cant = $this->mDb->getOne($query_cant,array());
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	function expunge_dir( $path ) {
		$ret = FALSE;
		if( $handle = opendir( $path )) {
			while( false!==( $file_or_folder = readdir( $handle ))) {
				if( $file_or_folder != "." && $file_or_folder != ".." ) {
					if( is_dir( $path.'/'.$file_or_folder )) {
						BitThemes::expunge_dir( $path.'/'.$file_or_folder );
					} else {
						unlink( $path.'/'.$file_or_folder );
					}
				}
			}
			closedir( $handle );

			if( rmdir( $path )) {
				$ret = TRUE;
			}
		}
		return $ret;
	}

	function user_has_module_assigned($iUserId, $iLayout = NULL, $iModuleId = NULL, $iModuleRsrc = NULL) {
		$ret = FALSE;
		if (!$iModuleId && $iModuleRsrc) {
			$iModuleId = $this->getModuleId($iModuleRsrc);
		}

		if ($iModuleId && $iUserId) {
			$bindVars = array($iUserId, $iModuleId);
			$sql = "SELECT count(*) FROM `".BIT_DB_PREFIX."themes_layouts` WHERE `user_id` = ? AND `module_id` = ?";
			if ($iLayout) {
				$bindVars[] = $iLayout;
				$sql .= " AND `layout` = ?";
			}
			$ret = (bool)$this->mDb->getOne($sql, $bindVars);
		}
		return $ret;
	}

	function store_rows($rows, $mod_rsrc, $user_id = NULL) {
		$module_id = $this->get_module_id($mod_rsrc);

		if (!$module_id)
			return FALSE;

		if ($user_id) {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts` SET `rows` = ?  WHERE `module_id` = ? AND `user_id` = ?";
			$result = $this->mDb->query($query, array($rows, $module_id, $user_id));
		} else {
			$query = "UPDATE `".BIT_DB_PREFIX."themes_layouts_modules` SET `rows` = ? WHERE `module_id` = ?";
			$result = $this->mDb->query($query, array($rows, $module_id));
		}

		return TRUE;
	}
	*/

}

?>
