<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_themes/modules_inc.php,v 1.1 2007/04/02 21:09:36 squareing Exp $
 * @package kernel
 * @subpackage functions
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

						if( file_exists( $cachefile ) && !(( $gBitSystem->getUTCTime() - filemtime( $cachefile )) > $r["cache_time"] )) {
							$fp = fopen( $cachefile, "r" );
							$data = fread( $fp, filesize( $cachefile ));
							fclose( $fp );
							$r["data"] = $data;
						} else {
							if( $moduleParams = $gBitThemes->getCustomModule( $template )) {
								$gBitSmarty->assign_by_ref( 'moduleParams', $moduleParams );
								$data = $gBitSmarty->fetch( 'bitpackage:themes/custom_module.tpl' );

								// write to chache file
								$fp = fopen( $cachefile, "w+" );
								fwrite( $fp, $data, strlen( $data ));
								fclose( $fp );
								$r["data"] = $data;
							}
						}
						unset( $data );
					} else {
						// using $module_rows, $module_params and $module_title is deprecated. please use $moduleParams hash instead
						global $module_rows, $module_params, $module_title, $moduleParams;
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
						/* This was the old and now deprecated assignment stuff.
						$gBitSmarty->assign_by_ref( 'moduleTitle', $r['module_title'] );
						$gBitSmarty->assign_by_ref( 'module_rows', $module_rows = $r['module_rows'] );
						$gBitSmarty->assign_by_ref( 'module_id', $r["module_id"] );
						$gBitSmarty->assign_by_ref( 'module_layout', $r["layout"] );
						 */
						$gBitSmarty->assign_by_ref( 'moduleParams', $moduleParams = $r );
						$r['data'] = $gBitSmarty->fetch( $r['module_rsrc'] );

						unset( $moduleParams );
					}
				}
			}
			$gBitSmarty->assign_by_ref( $column.'_modules', $gBitThemes->mLayout[$column] );
		}
	}
}
?>
