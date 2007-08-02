<?php
$registerHash = array(
	'package_name' => 'themes',
	'package_path' => dirname( __FILE__ ).'/',
	'activatable' => FALSE,
	'required_package'=> TRUE,
);
$gBitSystem->registerPackage( $registerHash );

$gLibertySystem->registerService( LIBERTY_SERVICE_THEMES, THEMES_PKG_NAME, array(
	'content_display_function' => 'themes_content_display',
	'content_list_function' => 'themes_content_list',
) );

require_once( THEMES_PKG_PATH."BitThemes.php" );
global $gBitThemes;
$gBitThemes = new BitThemes();

// if we're viewing this site with a text-browser, we force the text-browser theme
global $gSniffer;
if( !$gSniffer->_feature_set['css1'] && !$gSniffer->_feature_set['css2'] ) {
	$gBitThemes->setStyle( 'lynx' );
}

// setStyle first, in case package decides it wants to reset the style in it's own <package>/bit_setup_inc.php
if( !$gBitThemes->getStyle() ) {
	$gBitThemes->setStyle( DEFAULT_THEME );
}
$gBitSmarty->assign_by_ref( 'gBitThemes', $gBitThemes );

function themes_content_display( $pContent ) {
	global $gBitSystem, $gBitSmarty, $gBitThemes, $gBitUser, $gQueryUser;	

	// users_themes='u' is for all users content
	if( $gBitSystem->getConfig('users_themes') == 'u' ) {
		if( $gBitSystem->isFeatureActive( 'users_preferences' ) && is_object( $pContent ) && $pContent->isValid() ) {
			if( $pContent->getField( 'user_id' ) == $gBitUser->mUserId ) {
				// small optimization to reduce checking when we are looking at our own content, which is frequent
				if( $userStyle = $gBitUser->getPreference('theme') ) {
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
			$gQueryUser = new BitPermUser( $pContent->getField( 'user_id' ) );
			$gQueryUser->load();
			$gBitSmarty->assign_by_ref( 'gQueryUser', $gQueryUser );
		}
	}
}

function themes_content_list( $pContent, $pListHash ) {
	global $gBitSystem, $gBitSmarty, $gBitThemes, $gBitUser, $gQueryUser;	
	// users_themes='u' is for all users content
	if( $gBitSystem->getConfig('users_themes') == 'u' ) {
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
			$gQueryUser = new BitPermUser( $pListHash['user_id'] );
			$gQueryUser->load();
			$gBitSmarty->assign_by_ref( 'gQueryUser', $gQueryUser );
		}
	}
}

?>
