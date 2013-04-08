<?php
/**
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource.bitpackage.php
 * Type:     resource
 * Name:     bitpackage
 * Purpose:  Fetches templates from the correct package
 * -------------------------------------------------------------
 * @package Smarty
 * @subpackage plugins
 */

class Smarty_Resource_Bitpackage extends Smarty_Resource_Custom {

	protected function fetch ( $pTplName, &$pTplSource, &$pTplTime ) {
		$resources = $this->getTplLocations( $pTplName );

		foreach( $resources as $location => $resource ) {
			if( file_exists( $resource )) {
				$pTplSource = file_get_contents( $resource );
				$pTplTime = filemtime( $resource );
				return;
			}
		}
	}
/*
    protected function fetchTimestamp( $pTplName ) {
		$ret = FALSE;
		foreach( $this->getTplLocations( $pTplName ) as $resource ) {
			if( file_exists( $resource )) {
				$ret = filemtime( $resource );
			}
		}
		return $ret;
	}
*/
	private function getTplLocations( $pTplName ) {
		global $gBitThemes, $gNoForceStyle;

		$path = explode( '/', $pTplName );
		$package = array_shift( $path );
		$template = array_pop( $path );
		$subdir = '';
		foreach( $path as $p ) {
			$subdir .= $p.'/';
		}

		// files found in temp are special - these are stored in temp/<pkg>/(templates|modules)/<template.tpl>
		if( $package == 'temp' ) {
			// if it's a module, we need to look in the correct place
			$subdir .= ( preg_match( '/\b(help_)?mod_/', $template ) ? 'modules' : 'templates' );
			// we can't override these templates - they only exist in temp
			$ret['package_template'] = constant( strtoupper( $package ).'_PKG_PATH' )."$subdir/$template";
		} else {
			if( empty( $gNoForceStyle )) {
				// look in config/themes/force/
				$ret['force']        = CONFIG_PKG_PATH."themes/force/$package/$subdir$template";
				$ret['force_simple'] = CONFIG_PKG_PATH."themes/force/$subdir$template";
			}

			// look in themes/style/<stylename>/
			$ret['override']        = $gBitThemes->getStylePath()."$package/$subdir$template";
			$ret['override_simple'] = $gBitThemes->getStylePath().$subdir.$template;

			// if it's a module, we need to look in the correct place
			$subdir = ( preg_match( '/\b(help_)?mod_/', $template ) ? 'modules' : 'templates' )."/".$subdir;

			// look for default package template
			$ret['package_template'] = constant( strtoupper( $package ).'_PKG_PATH' )."$subdir$template";
		}

		return $ret;
	}
}

