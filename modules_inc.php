<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_themes/modules_inc.php,v 1.10 2009/03/31 06:30:03 lsces Exp $
 * @package themes
 * @subpackage functions
 */

/**
 * Setup
 */
global $gBitSmarty, $gBitSystem, $gBitThemes, $module_column, $gHideModules;

clearstatcache();

if( $gBitThemes->mLayout && empty( $gHideModules )) {
	foreach( array_keys( $gBitThemes->mLayout ) as $column ) {
		if( $column != CENTER_COLUMN ) {	// We don't need to pre-fetch center columns
			$module_column = $column;
			for ($i = 0; $i < count( $gBitThemes->mLayout[$column] ); $i++) {
				$r = &$gBitThemes->mLayout[$column][$i];
				if( !empty( $r['visible'] )) {
					list( $package, $template ) = split(  '/', $r['module_rsrc'] );
					// deal with custom modules
					if( $package == '_custom:custom' ) {
						global $gBitLanguage;

						// We're gonna run our own cache mechanism for user_modules
						// the cache is here to avoid calls to consumming queries,
						// each module is different for each language because of the strings
						$cacheDir = TEMP_PKG_PATH.'modules/cache/';
						if( !is_dir( $cacheDir )) {
							mkdir_p( $cacheDir );
						}
						$cachefile = $cacheDir.'_custom.'.$gBitLanguage->mLanguage.'.'.$template.'.tpl.cache';

						if( !empty( $r["cache_time"] ) && file_exists( $cachefile ) && !(( $gBitSystem->getUTCTime() - filemtime( $cachefile )) > $r["cache_time"] )) {
							$fp = fopen( $cachefile, "r" );
							$data = fread( $fp, filesize( $cachefile ));
							fclose( $fp );
							$r["data"] = $data;
						} else {
							if( $moduleParams = $gBitThemes->getCustomModule( $template )) {
								$moduleParams = array_merge( $r, $moduleParams );
								$gBitSmarty->assign_by_ref( 'moduleParams', $moduleParams );
								$data = $gBitSmarty->fetch( 'bitpackage:themes/custom_module.tpl' );

								if( !empty( $r["cache_time"] ) ) {
									// write to chache file
									$fp = fopen( $cachefile, "w+" );
									fwrite( $fp, $data, strlen( $data ));
									fclose( $fp );
								}
								$r["data"] = $data;
							}
						}
						unset( $data );
					} else {
						// using $module_rows, $module_params and $module_title is deprecated. please use $moduleParams hash instead
						global $module_rows, $module_params, $module_title, $gBitLanguage;
						
						$cacheDir = TEMP_PKG_PATH.'modules/cache/';
						if( !is_dir( $cacheDir )) {
							mkdir_p( $cacheDir );
						}
						
						// include tpl name and module id to uniquely identify
						$cachefile = $cacheDir.'_module_'.$r['module_id'].'.'.$gBitLanguage->mLanguage.'.'.$template.'.cache';
						
						// if the time is right get the cache else get it fresh
						if( !empty( $r["cache_time"] ) && file_exists( $cachefile ) && filesize( $cachefile ) && !(( $gBitSystem->getUTCTime() - filemtime( $cachefile )) > $r["cache_time"] ) ) {
							$fp = fopen( $cachefile, "r" );
							$data = fread( $fp, filesize( $cachefile ));
							fclose( $fp );
							$r["data"] = $data;
						} else {
							$module_params = $r['module_params']; // backwards compatability
						
							if( !$r['module_rows'] ) {
								$r['module_rows'] = 10;
							}
						
							// if there's no custom title, get one from file name
							if( !$r['title'] = ( isset( $r['title'] ) ? tra( $r['title'] ) : NULL )) {
								$pattern[0] = "/.*\/mod_(.*)\.tpl/";
								$replace[0] = "$1";
								$pattern[1] = "/_/";
								$replace[1] = " ";
								$r['title'] = ( !empty( $r['title'] ) ? tra( $r['title'] ) : tra( ucfirst( preg_replace( $pattern, $replace, $r['module_rsrc'] ))));
							}
						
							// moduleParams are extracted in BitSmarty::getSiblingAttachments() and passed on the the module php file
							$gBitSmarty->assign_by_ref( 'moduleParams', $moduleParams = $r );
							// assign the custom module title
							$gBitSmarty->assign_by_ref( 'moduleTitle', $r['title'] );
							$data = $gBitSmarty->fetch( $r['module_rsrc'] );
						
							if( !empty( $r["cache_time"] ) ) {
								// write to chache file
								$fp = fopen( $cachefile, "w+" );
								fwrite( $fp, $data, strlen( $data ));
								fclose( $fp );
							}
							$r["data"] = $data;
						}

						unset( $moduleParams );
					}
				}
			}
			$gBitSmarty->assign_by_ref( $column.'_modules', $gBitThemes->mLayout[$column] );
		}
	}
}
?>
