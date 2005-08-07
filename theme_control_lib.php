<?php

class ThemeControlLib extends BitBase {
	function ThemeControlLib() {				
		BitBase::BitBase();
	}

	function tc_assign_category($category_id, $theme) {
		$this->tc_remove_cat($category_id);

		$query = "delete from `".BIT_DB_PREFIX."tiki_theme_control_categs` where `category_id`=?";
		$this->mDb->query($query,array($category_id),-1,-1);
		$query = "insert into `".BIT_DB_PREFIX."tiki_theme_control_categs`(`category_id`,`theme`) values(?,?)";
		$this->mDb->query($query,array($category_id,$theme));
	}
/*
deprecated
	function tc_assign_section($section, $theme) {
		$this->tc_remove_section($section);

		$query = "delete from `".BIT_DB_PREFIX."tiki_theme_control_sections` where `section`=?";
		$this->mDb->query($query,array($section),-1,-1);
		$query = "insert into `".BIT_DB_PREFIX."tiki_theme_control_sections`(`section`,`theme`) values(?,?)";
		$this->mDb->query($query,array($section,$theme));
	}
*/
	function tc_assign_object($obj_id, $theme, $type, $name) {

		$obj_id = md5($type . $obj_id);
		$this->tc_remove_object($obj_id);
		$query = "delete from `".BIT_DB_PREFIX."tiki_theme_control_objects` where `obj_id`=?";
		$this->mDb->query($query,array($obj_id),-1,-1);
		$query = "insert into `".BIT_DB_PREFIX."tiki_theme_control_objects`(`obj_id`,`theme`,`type`,`name`) values(?,?,?,?)";
		$this->mDb->query($query,array($obj_id,$theme,$type,$name));
	}

	function tc_get_theme_by_categ( $pCategory ) {
		$ret = '';
		if( !empty( $pCategory['category_id'] ) && is_numeric( $pCategory['category_id'] ) ) {
			if ($this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_theme_control_categs` where `category_id`=?",array($pCategory['category_id']))) {
				return $this->mDb->getOne("select `theme` from `".BIT_DB_PREFIX."tiki_theme_control_categs` where `category_id`=?",array($pCategory['category_id']));
			}
		}
		return $ret;
	}
/*
	function tc_get_theme_by_section($section) {
		if ($this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_theme_control_sections` where `section`=?",array($section))) {
			return $this->mDb->getOne("select `theme` from `".BIT_DB_PREFIX."tiki_theme_control_sections` where `section`=?",array($section));
		} else {
			return '';
		}
	}
*/
	function tc_get_theme_by_object($type, $obj_id) {
		$obj_id = md5($type . $obj_id);

		if ($this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tiki_theme_control_objects` where `type`=? and `obj_id`=?",array($type,$obj_id))) {
			return $this->mDb->getOne("select `theme` from `".BIT_DB_PREFIX."tiki_theme_control_objects` where `type`=? and `obj_id`=?",array($type,$obj_id));
		} else {
			return '';
		}
	}

	function tc_list_categories($offset, $maxRecords, $sort_mode, $find) {

		if ($find) {
			$findesc = '%' . strtoupper( $find ). '%';
			$mid = " where (UPPER(`theme`) like ?)";
			$bindvars=array($findesc);
		} else {
			$mid = "";
			$bindvars=array();
		}

		$query = "select tc.`category_id`,tc.`name`,`theme` from `".BIT_DB_PREFIX."tiki_theme_control_categs` ttt,`".BIT_DB_PREFIX."tiki_categories` tc where ttt.`category_id`=tc.`category_id` $mid order by ".$this->mDb->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tiki_theme_control_categs` ttt,`".BIT_DB_PREFIX."tiki_categories` tc where ttt.`category_id`=tc.`category_id` $mid";
		$result = $this->mDb->query($query,$bindvars,$maxRecords,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}
/*
	function tc_list_sections($offset, $maxRecords, $sort_mode, $find) {

		if ($find) {
			$findesc = '%' . $find . '%';

			$mid = " where (`theme` like $findesc)";
			$bindvars=array($findesc);
		} else {
			$mid = "";
			$bindvars=array();
		}

		$query = "select * from `".BIT_DB_PREFIX."tiki_theme_control_sections` $mid order by ".$this->mDb->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tiki_theme_control_sections` $mid";
		$result = $this->mDb->query($query,$bindvars,$maxRecords,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}
*/
	function tc_list_objects($type, $offset, $maxRecords, $sort_mode, $find) {

		if ($find) {
			$findesc = '%' . strtoupper( $find ). '%';
			$mid = " where (UPPER(`theme`) like ?)";
			$bindvars=array($type, $findesc);
		} else {
			$mid = "";
			$bindvars=array($type);
		}

		$query = "select * from `".BIT_DB_PREFIX."tiki_theme_control_objects` where `type`=? $mid order by ".$this->mDb->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tiki_theme_control_objects` where `type`=? $mid";
		$result = $this->mDb->query($query,$bindvars,$maxRecords,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	function tc_remove_cat($cat) {
		$query = "delete from `".BIT_DB_PREFIX."tiki_theme_control_categs` where `category_id`=?";

		$this->mDb->query($query,array($cat));
	}
/*
	function tc_remove_section($section) {
		$query = "delete from `".BIT_DB_PREFIX."tiki_theme_control_sections` where `section`=?";

		$this->mDb->query($query,array($section));
	}
*/
	function tc_remove_object($obj_id) {
		$query = "delete from `".BIT_DB_PREFIX."tiki_theme_control_objects` where `obj_id`=?";

		$this->mDb->query($query,array($obj_id));
	}
	
	function getStyles( $pDir = NULL, $pNullOption = NULL, $bIncludeCustom = FALSE ) {
		global $gBitSystem;
		global $gBitUser;
		
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
				if ( is_dir( $pDir."$file") && ( $file != '.' &&  $file != '..' &&  $file != 'CVS' &&  $file != 'slideshows' &&  $file != 'blank') ) {
					$ret[] = $file;
				}
			}
			closedir( $h );
		}
		
		if ($bIncludeCustom && $gBitSystem->getPreference('feature_editcss')) {	
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

	function getStylesList( $pDir=NULL, $pNullOption=NULL, $pSubDir = 'style_info' ) {
		global $gBitSystem;

		$ret = array();

		if( empty( $pDir ) ) {
			$pDir = THEMES_PKG_PATH.'styles/';
		}

		if( !empty( $pNullOption ) ) {
			$ret[] = '';
		}

		if( is_dir( $pDir ) ) {
			$h = opendir( $pDir );
			while( $file = readdir( $h ) ) {
				if ( is_dir( $pDir.$file ) && ( $file != '.' &&  $file != '..' &&  $file != 'CVS' &&  $file != 'slideshows' &&  $file != 'blank') ) {
					$ret[$file]['style'] = $file;
					if( is_dir( $infoDir = $pDir.$file.'/'.$pSubDir.'/' ) ) {
						$dh = opendir( $infoDir );
						while( $f = readdir( $dh ) ) {
							if( preg_match( "/^preview/", $f ) ) {
								$ret[$file]['preview'] = THEMES_PKG_URL.'styles/'.$file.'/'.$pSubDir.'/'.$f;
							}

							if( $f == 'description.htm' ) {
								$fh = fopen( $infoDir.$f, "r" );
								$ret[$file]['description'] = fread( $fh, filesize( $infoDir.$f ) );
								fclose( $fh );
							}
						}
						closedir( $dh );
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
	* delete entire folder and everything within it
	* @param path path to folder
	* @note caution!
	*/
	function expunge_dir( $path ) {
		$handle = opendir($path);
		while( false!==( $file_or_folder = readdir( $handle ) ) ) {
			if( $file_or_folder != "." && $file_or_folder != ".." ) {
				if( is_dir( $path.'/'.$file_or_folder ) ) {
					 $this->expunge_dir( $path.'/'.$file_or_folder );	// recursive
				} else {
					unlink( $path.'/'.$file_or_folder );
				}
			}
		}
		closedir( $handle );
		if( rmdir( $path ) ) {
			return true;	
		}
	}
}

global $tcontrollib;
$tcontrollib  = new ThemeControlLib();

?>
