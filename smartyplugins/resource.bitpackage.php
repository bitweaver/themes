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

	/**
	 * THE method to invoke if you want to be sure a tpl's sibling php file gets included if it exists. This
	 * should not need to be invoked from anywhere except within this class
	 *
	 * @param string $pFile file to be included, should be of the form "bitpackage:<packagename>/<templatename>"
	 * @return TRUE if a sibling php file was included
	 * @access private
	 */
    public function populate(Smarty_Template_Source $source, Smarty_Internal_Template $_template=null) {
		global $gBitThemes;
		$ret = FALSE;

		if( $siblingPhpFile = static::getSiblingPhpFile( $source->name ) ) {
			global $gBitSmarty, $gBitSystem, $gBitUser, $gQueryUserId, $moduleParams;
			$moduleParams = array();
			if( !empty( $_template->tpl_vars['module_params'] ) ) {
				// module_params were passed through via the {include},
				// e.g. {include file="bitpackage:foobar/mod_list_foo.tpl" module_params="user_id=`$gBitUser->mUserId`&sort_mode=created_desc"}
				$moduleParams['module_params'] = $gBitThemes->parseString( $_template->tpl_vars['module_params']->value );
			} elseif( !empty( $_template->tpl_vars['moduleParams'] ) ) {
				// Module Params were passed in from the template, like kernel/dynamic.tpl
				$moduleParams = $_template->tpl_vars['moduleParams']->value;
			}
			include( $siblingPhpFile );
		}

		parent::populate( $source, $_template );
	}

	public static function getSiblingPhpFile( $pTplName ) {
		$ret = NULL;
		if( preg_match('/mod_/', $pTplName ) || preg_match( '/center_/', $pTplName ) ) {
			if( strpos( $pTplName, '/' )) {
				list( $package, $modFile ) = explode( '/', $pTplName );
				$subdir = preg_match( '/mod_/', $modFile ) ? 'modules' : 'templates';
				global $gBitSmarty, $gBitSystem, $gBitUser, $gQueryUserId, $moduleParams;
				// the PHP sibling file needs to be included here, before the fetch so caching works properly
				$modFile = str_replace( '.tpl', '.php', $modFile );

				$path = constant( strtoupper( $package )."_PKG_PATH" );
				$includeFile = "$path$subdir/$modFile";

				if( file_exists( $includeFile )) {
					$ret = $includeFile;
				}
			}
		}
		return $ret;
	}


    protected function fetchTimestamp( $pTplName ) {
		$ret = FALSE;
		$locations = $this->getTplLocations( $pTplName );
		foreach( $locations as $resource ) {
			if( file_exists( $resource )) {
				$ret = filemtime( $resource );
			}
		}

		return $ret;
	}

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

