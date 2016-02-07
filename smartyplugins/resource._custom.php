<?php
/**
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource._custom.php
 * Type:     resource
 * Name:     _custom
 * Purpose:  Fetches templates from the correct package
 * -------------------------------------------------------------
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Resource__Custom extends Smarty_Resource_Custom {

	protected function fetch ( $pTplName, &$pTplSource, &$pTplTime ) {
		global $gBitLanguage, $gBitThemes, $gBitSmarty;
		$ret = '';

		// We're gonna run our own cache mechanism for user_modules
		// the cache is here to avoid calls to consumming queries,
		// each module is different for each language because of the strings
		$cacheDir = TEMP_PKG_PATH.'modules/cache/';
		if( !is_dir( $cacheDir )) {
			mkdir_p( $cacheDir );
		}
		list( $package, $template ) = explode(  '/', $pTplName );
		$cacheFile = $cacheDir.'_custom.'.$gBitLanguage->mLanguage.'.'.$template.'.tpl.cache';

		if( !empty( $r["cache_time"] ) && file_exists( $cacheFile ) && !(( $gBitSystem->getUTCTime() - filemtime( $cacheFile )) > $r["cache_time"] )) {
			$pTplSource = file_get_contents( $cacheFile );
		} else {
			global $moduleParams;
			if( $moduleParams = $gBitThemes->getCustomModule( $template )) {
				$gBitSmarty->assignByRef( 'moduleParams', $moduleParams );
				$pTplSource = $gBitSmarty->fetch( 'bitpackage:themes/custom_module.tpl' );
				// write to chache file
				$fp = fopen( $cacheFile, "w+" );
				fwrite( $fp, $data, strlen( $data ));
				fclose( $fp );
			}
		}
		$pTplTime = filemtime( $cacheFile );
	}

    protected function fetchTimestamp( $pTplName ) {
		return null;
	}

}


