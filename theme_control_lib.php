<?php

class BitThemes extends BitBase {
	function BitThemes() {				
		BitBase::BitBase();
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

	/**
	* @param $pSubDirs a subdirectory to scan as well - you can pass in multiple dirs using an array
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
			while( $file = readdir( $h ) ) {
				if ( is_dir( $pDir.$file ) && ( $file != '.' &&  $file != '..' &&  $file != 'CVS' &&  $file != 'slideshows' &&  $file != 'blank' ) ) {
					$ret[$file]['style'] = $file;
					// check if we want to have a look in any subdirs
					foreach( $subDirs as $dir ) {
						if( is_dir( $infoDir = $pDir.$file.'/'.$dir.'/' ) ) {
							$dh = opendir( $infoDir );
							// cycle through files / dirs
							while( $f = readdir( $dh ) ) {
								if ( is_readable( $infoDir.$f ) && ( $f != '.' &&  $f != '..' &&  $f != 'CVS' ) ) {
									$ret[$file][$dir][preg_replace( "/\..*/", "", $f )] = THEMES_PKG_URL.'styles/'.$file.'/'.$dir.'/'.$f;

									if( preg_match( "/\.htm$/", $f ) ) {
										$fh = fopen( $infoDir.$f, "r" );
										$ret[$file][$dir][preg_replace( "/\.htm$/", "", $f )] = fread( $fh, filesize( $infoDir.$f ) );
										fclose( $fh );
									}
								}
							}
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
	* delete entire folder and everything within it
	* @param path path to folder
	* @note caution!
	*/
	function expunge_dir( $path ) {
		$handle = opendir($path);
		while( false!==( $file_or_folder = readdir( $handle ) ) ) {
			if( $file_or_folder != "." && $file_or_folder != ".." ) {
				if( is_dir( $path.'/'.$file_or_folder ) ) {
					BitThemes::expunge_dir( $path.'/'.$file_or_folder );
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

//global $tcontrollib;
//$tcontrollib  = new ThemeControlLib();

?>
