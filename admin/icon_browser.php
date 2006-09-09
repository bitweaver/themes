<?php
require_once( "../../bit_setup_inc.php" );

$gBitSystem->verifyPermission( 'p_admin' );

$iconHash = array(
	"Standard Action Icons" => array(
		"address-book-new"                  => "The icon used for the action to create a new address book.",
		"application-exit"                  => "The icon used for exiting an application. Typically this is seen in the application's menus as File-&gt;Quit.",
		"appointment-new"                   => "The icon used for the action to create a new appointment in a calendaring application.",
		"contact-new"                       => "The icon used for the action to create a new contact in an address book application.",
		"dialog-cancel"                     => "The icon used for the Cancel button that might appear in dialog windows.",
		"dialog-close"                      => "The icon used for the Close button that might appear in dialog windows.",
		"dialog-ok"                         => "The icon used for the OK button that might appear in dialog windows.",
		"document-new"                      => "The icon used for the action to create a new document.",
		"document-open"                     => "The icon used for the action to open a document.",
		"document-open-recent"              => "The icon used for the action to open a document that was recently opened.",
		"document-page-setup"               => "The icon for the page setup action of a document editor.",
		"document-print"                    => "The icon for the print action of an application.",
		"document-print-preview"            => "The icon for the print preview action of an application.",
		"document-properties"               => "The icon for the action to view the properties of a document in an application.",
		"document-revert"                   => "The icon for the action of reverting to a previous version of a document.",
		"document-save"                     => "The icon for the save action.",
		"document-save-as"                  => "The icon for the save as action.",
		"edit-copy"                         => "The icon for the copy action.",
		"edit-cut"                          => "The icon for the cut action.",
		"edit-delete"                       => "The icon for the delete action.",
		"edit-find"                         => "The icon for the find action.",
		"edit-find-replace"                 => "The icon for the find and replace action.",
		"edit-paste"                        => "The icon for the paste action.",
		"edit-redo"                         => "The icon for the redo action.",
		"edit-select-all"                   => "The icon for the select all action.",
		"edit-undo"                         => "The icon for the undo action.",
		"format-indent-less"                => "The icon for the decrease indent formatting action.",
		"format-indent-more"                => "The icon for the increase indent formatting action.",
		"format-justify-center"             => "The icon for the center justification formatting action.",
		"format-justify-fill"               => "The icon for the fill justification formatting action.",
		"format-justify-left"               => "The icon for the left justification formatting action.",
		"format-justify-right"              => "The icon for the right justification action.",
		"format-text-direction-ltr"         => "The icon for the left-to-right text formatting action.",
		"format-text-direction-rtl"         => "The icon for the right-to-left formatting action.",
		"format-text-bold"                  => "The icon for the bold text formatting action.",
		"format-text-italic"                => "The icon for the italic text formatting action.",
		"format-text-underline"             => "The icon for the underlined text formatting action.",
		"format-text-strikethrough"         => "The icon for the strikethrough text formatting action.",
		"go-bottom"                         => "The icon for the go to bottom of a list action.",
		"go-down"                           => "The icon for the go down in a list action.",
		"go-first"                          => "The icon for the go to the first item in a list action.",
		"go-home"                           => "The icon for the go to home location action.",
		"go-jump"                           => "The icon for the jump to action.",
		"go-last"                           => "The icon for the go to the last item in a list action.",
		"go-next"                           => "The icon for the go to the next item in a list action.",
		"go-previous"                       => "The icon for the go to the previous item in a list action.",
		"go-top"                            => "The icon for the go to the top of a list action.",
		"go-up"                             => "The icon for the go up in a list action.",
		"help-about"                        => "The icon for the About item in the Help menu.",
		"help-contents"                     => "The icon for Contents item in the Help menu.",
		"help-faq"                          => "The icon for the FAQ item in the Help menu.",
		"insert-image"                      => "The icon for the insert image action of an application.",
		"insert-link"                       => "The icon for the insert link action of an application.",
		"insert-object"                     => "The icon for the insert object action of an application.",
		"insert-text"                       => "The icon for the insert text action of an application.",
		"list-add"                          => "The icon for the add to list action.",
		"list-remove"                       => "The icon for the remove from list action.",
		"mail-forward"                      => "The icon for the forward action of an electronic mail application.",
		"mail-mark-important"               => "The icon for the mark as important action of an electronic mail application.",
		"mail-mark-junk"                    => "The icon for the mark as junk action of an electronic mail application.",
		"mail-mark-notjunk"                 => "The icon for the mark as not junk action of an electronic mail application.",
		"mail-mark-read"                    => "The icon for the mark as read action of an electronic mail application.",
		"mail-mark-unread"                  => "The icon for the mark as unread action of an electronic mail application.",
		"mail-message-new"                  => "The icon for the compose new mail action of an electronic mail application.",
		"mail-reply-all"                    => "The icon for the reply to all action of an electronic mail application.",
		"mail-reply-sender"                 => "The icon for the reply to sender action of an electronic mail application.",
		"mail-send-receive"                 => "The icon for the send and receive action of an electronic mail application.",
		"media-eject"                       => "The icon for the eject action of a media player or file manager.",
		"media-playback-pause"              => "The icon for the pause action of a media player.",
		"media-playback-start"              => "The icon for the start playback action of a media player.",
		"media-playback-stop"               => "The icon for the stop action of a media player.",
		"media-record"                      => "The icon for the record action of a media application.",
		"media-seek-backward"               => "The icon for the seek backward action of a media player.",
		"media-seek-forward"                => "The icon for the seek forward action of a media player.",
		"media-skip-backward"               => "The icon for the skip backward action of a media player.",
		"media-skip-forward"                => "The icon for the skip forward action of a media player.",
		"system-lock-screen"                => "The icon used for the Lock Screen item in the desktop's panel application.",
		"system-log-out"                    => "The icon used for the Log Out item in the desktop's panel application.",
		"system-run"                        => "The icon used for the Run Application... item in the desktop's panel application.",
		"system-search"                     => "The icon used for the Search item in the desktop's panel application.",
		"tools-check-spelling"              => "The icon used for the Check Spelling item in the application's Tools menu.",
		"view-fullscreen"                   => "The icon used for the Fullscreen item in the application's View menu.",
		"view-refresh"                      => "The icon used for the Refresh item in the application's View menu.",
		"view-sort-ascending"               => "The icon used for the Sort Ascending item in the application's View menu, or in a button for changing the sort method for a list.",
		"view-sort-descending"              => "The icon used for the Sort Descending item in the application's View menu, or in a button for changing the sort method for a list.",
		"window-close"                      => "The icon used for the Close Window item in the application's Windows menu.",
		"window-new"                        => "The icon used for the New Window item in the application's Windows menu.",
		"zoom-best-fit"                     => "The icon used for the Best Fit item in the application's View menu.",
		"zoom-in"                           => "The icon used for the Zoom in item in the application's View menu.",
		"zoom-original"                     => "The icon used for the Original Size item in the application's View menu.",
		"zoom-out"                          => "The icon used for the Zoom Out item in the application's View menu.",
	),
//	"Standard Animation Icons" => array(
//		"process-working"                   => "This is the standard spinner animation for web browsers and file managers to show that the location is loading. This image should be a multi-frame PNG with the frames as the size that the directory containing the image, is specified to be in. The first frame of the animation should be used for the resting state of the animation.",
//	),
	"Standard Application Icons" => array(
		"accessories-calculator"            => "The icon used for the desktop's calculator accessory program.",
		"accessories-character-map"         => "The icon used for the desktop's international and extended text character accessory program.",
		"accessories-dictionary"            => "The icon used for the desktop's dictionary accessory program.",
		"accessories-text-editor"           => "The icon used for the desktop's text editing accessory program.",
		"help-browser"                      => "The icon used for the desktop's help browsing application.",
		"multimedia-volume-control"         => "The icon used for the desktop's hardware volume control application.",
		"preferences-desktop-accessibility" => "The icon used for the desktop's accessibility preferences.",
		"preferences-desktop-font"          => "The icon used for the desktop's font preferences.",
		"preferences-desktop-keyboard"      => "The icon used for the desktop's keyboard preferences.",
		"preferences-desktop-locale"        => "The icon used for the desktop's locale preferences.",
		"preferences-desktop-multimedia"    => "The icon used for the desktop's multimedia preferences.",
		"preferences-desktop-screensaver"   => "The icon used for the desktop's screen saving preferences.",
		"preferences-desktop-theme"         => "The icon used for the desktop's theme preferences.",
		"preferences-desktop-wallpaper"     => "The icon used for the desktop's wallpaper preferences.",
		"system-file-manager"               => "The icon used for the desktop's file management application.",
		"system-software-update"            => "The icon used for the desktop's software updating application.",
		"utilities-terminal"                => "The icon used for the desktop's terminal emulation application.",
	),
	"Standard Category Icons" => array(
		"applications-accessories"          => "The icon for the Accessories sub-menu of the Programs menu.",
		"applications-development"          => "The icon for the Programming sub-menu of the Programs menu.",
		"applications-games"                => "The icon for the Games sub-menu of the Programs menu.",
		"applications-graphics"             => "The icon for the Graphics sub-menu of the Programs menu.",
		"applications-internet"             => "The icon for the Internet sub-menu of the Programs menu.",
		"applications-multimedia"           => "The icon for the Multimedia sub-menu of the Programs menu.",
		"applications-office"               => "The icon for the Office sub-menu of the Programs menu.",
		"applications-other"                => "The icon for the Other sub-menu of the Programs menu.",
		"applications-system"               => "The icon for the System Tools sub-menu of the Programs menu.",
		"applications-utilities"            => "The icon for the Utilities sub-menu of the Programs menu.",
		"preferences-desktop"               => "The icon for the Desktop Preferences category.",
		"preferences-desktop-accessibility" => "The icon for the Accessibility sub-category of the Desktop Preferences category.",
		"preferences-desktop-peripherals"   => "The icon for the Peripherals sub-category of the Desktop Preferences category.",
		"preferences-desktop-personal"      => "The icon for the Personal sub-category of the Desktop Preferences category.",
		"preferences-other"                 => "The icon for the Other preferences category.",
		"preferences-system"                => "The icon for the System Preferences category.",
		"preferences-system-network"        => "The icon for the Network sub-category of the System Preferences category.",
		"system-help"                       => "The icon for the Help system category.",
	),
	"Standard Device Icons" => array(
		"audio-card"                        => "The icon used for the audio rendering device.",
		"audio-input-microphone"            => "The icon used for the microphone audio input device.",
		"battery"                           => "The icon used for the system battery device.",
		"camera-photo"                      => "The icon used for a digital still camera devices.",
		"camera-video"                      => "The icon used for a video or web camera.",
		"computer"                          => "The icon used for the computing device as a whole.",
		"drive-cdrom"                       => "The icon used for CD and DVD drives.",
		"drive-harddisk"                    => "The icon used for hard disk drives.",
		"drive-removable-media"             => "The icon used for removable media drives.",
		"input-gaming"                      => "The icon used for the gaming input device.",
		"input-keyboard"                    => "The icon used for the keyboard input device.",
		"input-mouse"                       => "The icon used for the mousing input device.",
		"media-cdrom"                       => "The icon used for physical CD and DVD media.",
		"media-floppy"                      => "The icon used for physical floppy disk media.",
		"multimedia-player"                 => "The icon used for generic multimedia playing devices.",
		"network-wired"                     => "The icon used for wired network connections.",
		"network-wireless"                  => "The icon used for wireless network connections.",
		"printer"                           => "The icon used for a printer which is connected locally.",
		"video-display"                     => "The icon used for the monitor that video gets displayed to.",
	),
	"Standard Emblem Icons" => array(
		"emblem-default"                    => "The icon used as an emblem to specify the default selection of a printer for example.",
		"emblem-documents"                  => "The icon used as an emblem for the directory where a user's documents are stored.",
		"emblem-downloads"                  => "The icon used as an emblem for the directory where a user's downloads from the internet are stored.",
		"emblem-favorite"                   => "The icon used as an emblem for files and directories that the user marks as favorites.",
		"emblem-important"                  => "The icon used as an emblem for files and directories that are marked as important by the user.",
		"emblem-mail"                       => "The icon used as an emblem to specify the directory where the user's electronic mail is stored.",
		"emblem-photos"                     => "The icon used as an emblem to specify the directory where the user stores photographs.",
		"emblem-readonly"                   => "The icon used as an emblem for files and directories which can not be written to by the user.",
		"emblem-shared"                     => "The icon used as an emblem for files and directories that are shared to other users.",
		"emblem-symbolic-link"              => "The icon used as an emblem for files and direcotires that are links to other files or directories on the filesystem.",
		"emblem-synchronized"               => "The icon used as an emblem for files or directories that are configured to be synchronized to another device.",
		"emblem-system"                     => "The icon used as an emblem for directories that contain system libraries, settings, and data.",
		"emblem-unreadable"                 => "The icon used as an emblem for files and directories that are inaccessible.",
	),
	"Standard Emotion Icons" => array(
		"face-angel"                        => "The icon used for the 0:-) emote.",
		"face-crying"                       => "The icon used for the&nbsp;:'( emote.",
		"face-devil-grin"                   => "The icon used for the &gt;:-) emote.",
		"face-devil-sad"                    => "The icon used for the &gt;:-( emote.",
		"face-glasses"                      => "The icon used for the B-) emote.",
		"face-kiss"                         => "The icon used for the&nbsp;:-* emote.",
		"face-monkey"                       => "The icon used for the&nbsp;:-(|) emote.",
		"face-plain"                        => "The icon used for the&nbsp;:-| emote.",
		"face-sad"                          => "The icon used for the&nbsp;:-( emote.",
		"face-smile"                        => "The icon used for the&nbsp;:-) emote.",
		"face-smile-big"                    => "The icon used for the&nbsp;:-D emote.",
		"face-smirk"                        => "The icon used for the&nbsp;:-! emote.",
		"face-surprise"                     => "The icon used for the&nbsp;:-0 emote.",
		"face-wink"                         => "The icon used for the&nbsp;;-) emote.",
	),
	"Standard MIME Type Icons" => array(
		"application-x-executable"          => "The icon used for executable file types.",
		"audio-x-generic"                   => "The icon used for generic audio file types.",
		"font-x-generic"                    => "The icon used for generic font file types.",
		"image-x-generic"                   => "The icon used for generic image file types.",
		"package-x-generic"                 => "The icon used for generic package file types.",
		"text-html"                         => "The icon used for HTML text file types.",
		"text-x-generic"                    => "The icon used for generic text file types.",
		"text-x-generic-template"           => "The icon used for generic text templates.",
		"text-x-script"                     => "The icon used for script file types, such as shell scripts.",
		"video-x-generic"                   => "The icon used for generic video file types.",
		"x-office-address-book"             => "The icon used for generic address book file types.",
		"x-office-calendar"                 => "The icon used for generic calendar file types.",
		"x-office-document"                 => "The icon used for generic document and letter file types.",
		"x-office-presentation"             => "The icon used for generic presentation file types.",
		"x-office-spreadsheet"              => "The icon used for generic spreadsheet file types.",
	),
	"Standard Place Icons" => array(
		"folder"                            => "The standard folder icon used to represent directories on local filesystems, mail folders, and other hierarchical groups.",
		"folder-remote"                     => "The icon used for normal directories on a remote filesystem.",
		"network-server"                    => "The icon used for individual host machines under the Network Servers place in the file manager.",
		"network-workgroup"                 => "The icon for the Network Servers place in the desktop's file manager, and workgroups within the network.",
		"start-here"                        => "The icon used by the desktop's main menu for accessing places, applications, and other features.",
		"user-desktop"                      => "The icon for the special Desktop directory of the user.",
		"user-home"                         => "The icon for the special Home directory of the user.",
		"user-trash"                        => "The icon for the user's Trash place in the desktop's file manager.",
	),
	"Standard Status Icons" => array(
		"appointment-missed"                => "The icon used when an appointment was missed.",
		"appointment-soon"                  => "The icon used when an appointment will occur soon.",
		"audio-volume-high"                 => "The icon used to indicate high audio volume.",
		"audio-volume-low"                  => "The icon used to indicate low audio volume.",
		"audio-volume-medium"               => "The icon used to indicate medium audio volume.",
		"audio-volume-muted"                => "The icon used to indicate the muted state for audio playback.",
		"battery-caution"                   => "The icon used when the battery is below 40%.",
		"battery-low"                       => "The icon used when the battery is below 20%.",
		"dialog-error"                      => "The icon used when a dialog is opened to explain an error condition to the user.",
		"dialog-information"                => "The icon used when a dialog is opened to give information to the user that may be pertinent to the requested action.",
		"dialog-password"                   => "The icon used when a dialog requesting the authentication credentials for a user is opened.",
		"dialog-question"                   => "The icon used when a dialog is opened to ask a simple question of the user.",
		"dialog-warning"                    => "The icon used when a dialog is opened to warn the user of impending issues with the requested action.",
		"folder-drag-accept"                => "The icon used for a folder while an object is being dragged onto it, that is of a type that the directory can contain.",
		"folder-open"                       => "The icon used for folders, while their contents are being displayed within the same window. This icon would normally be shown in a tree or list view, next to the main view of a folder's contents.",
		"folder-visiting"                   => "The icon used for folders, while their contents are being displayed in another window. This icon would typically be used when using multiple windows to navigate the hierarchy, such as in Nautilus's spatial mode.",
		"image-loading"                     => "The icon used when another image is being loaded, such as thumnails for larger images in the file manager.",
		"image-missing"                     => "The icon used when another image could not be loaded.",
		"mail-attachment"                   => "The icon used for an electronic mail that contains attachments.",
		"mail-unread"                       => "The icon used for an electronic mail that is unread.",
		"mail-read"                         => "The icon used for an electronic mail that is read.",
		"mail-replied"                      => "The icon used for an electronic mail that has been replied to.",
		"mail-signed"                       => "The icon used for an electronic mail that contains a signature.",
		"mail-signed-verified"              => "The icon used for an electronic mail that contains a signature which has also been verified by the security system.",
		"media-playlist-repeat"             => "The icon for the repeat action of a media player.",
		"media-playlist-shuffle"            => "The icon for the shuffle action of a media player.",
		"network-error"                     => "The icon used when an error occurs trying to intialize the network connection of the computing device. This icon should be two computers, one in the background, with the screens of both computers, colored black, and with the theme's style element for errors, overlayed on top of the icon.",
		"network-idle"                      => "The icon used when no data is being transmitted or received, while the computing device is connected to a network. This icon should be two computers, one in the background, with the screens of both computers, colored black.",
		"network-offline"                   => "The icon used when the computing device is disconnected from the network. This icon should be a computer in the background, with a screen colored black, and the theme's icon element to show that a device is not accessible, in the foreground.",
		"network-receive"                   => "The icon used when data is being received, while the computing device is connected to a network. This icon should be two computers, one in the background, with its screen colored green, and the screen of the computer in the foreground, colored black.",
		"network-transmit"                  => "The icon used when data is being transmitted, while the computing device is connected to a network. This icon should be two computers, one in the background, with its screen colored black, and the screen of the computer in the foreground, colored green.",
		"network-transmit-receive"          => "The icon used data is being both transmitted and received simultaneously, while the computing device is connected to a network. This icon should be two computers, one in the background, with the screens of both computers, colored green.",
		"printer-error"                     => "The icon used when an error occurs while attempting to print. This icon should be the theme's printer device icon, with the theme's style element for errors, overlayed on top of the icon.",
		"printer-printing"                  => "The icon used while a print job is successfully being spooled to a printing device. This icon should be the theme's printer device icon, with a document emerging from the printing device.",
		"software-update-available"         => "The icon used when an update is available for software installed on the computing device, through the system software update program.",
		"software-update-urgent"            => "The icon used when an urgent update is available through the system software update program.",
		"sync-error"                        => "The icon used when an error occurs while attempting to synchronize data from the computing device, to another device.",
		"sync-synchronizing"                => "The icon used while data is successfully synchronizing to another device.",
		"task-due"                          => "The icon used when a task is due soon.",
		"task-passed-due"                   => "The icon used when a task that was due, has been left incomplete.",
		"user-away"                         => "The icon used when a user on a chat network is away from their keyboard and the chat program.",
		"user-idle"                         => "The icon used when a user on a chat network has not been an active participant in any chats on the network, for an extended period of time.",
		"user-offline"                      => "The icon used when a user on a chat network is not available.",
		"user-online"                       => "The icon used when a user on a chat network is available to initiate a conversation with.",
		"user-trash-full"                   => "The icon for the user's Trash in the desktop's file manager, when there are items in the Trash waiting for disposal or recovery.",
		"weather-clear"                     => "The icon used while the weather for a region is clear skies.",
		"weather-clear-night"               => "The icon used while the weather for a region is clear skies during the night.",
		"weather-few-clouds"                => "The icon used while the weather for a region is partly cloudy.",
		"weather-few-clouds-night"          => "The icon used while the weather for a region is partly cloudy during the night.",
		"weather-fog"                       => "The icon used while the weather for a region is foggy.",
		"weather-overcast"                  => "The icon used while the weather for a region is overcast.",
		"weather-severe-alert"              => "The icon used while a sever weather alert is in effect for a region.",
		"weather-showers"                   => "The icon used while rain showers are occurring in a region.",
		"weather-showers-scattered"         => "The icon used while scattered rain showers are occurring in a region.",
		"weather-snow"                      => "The icon used while snow showers are occurring in a region.",
		"weather-storm"                     => "The icon used while storms are occurring in a region.",
	),
);
$gBitSmarty->assign( 'iconHash', $iconHash );
$gBitSystem->display( 'bitpackage:themes/icon_browser.tpl', tra( 'Icon Listing' ) );
?>
