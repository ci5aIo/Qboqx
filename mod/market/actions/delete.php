<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Make sure we're logged in (send us to the front page if not)
if (!elgg_is_logged_in()) forward();

// Get input data
$guid = (int) get_input('guid');
		
// Make sure we actually have permission to edit
$market = get_entity($guid);
$container = $market->getContainerEntity();

if (($market->getSubtype() == "market" || 
     $market->getSubtype() == "item"   ||
     $market->getSubtype() == "component"  || 
     $market->getSubtype() == "accessory" ) && 
     $market->canEdit()) {
	
	// Get owning user
	$owner = get_entity($market->getOwnerGUID());
				
	// Delete the images

	$prefix = "market/".$guid;
		
	$tiny = $prefix."tiny.jpg";
	$small = $prefix."small.jpg";
	$medium = $prefix."medium.jpg";
	$large = $prefix."large.jpg";
	$master = $prefix.".jpg";
				
	if ($tiny) {
		$delfile = new ElggFile();
		$delfile->owner_guid = $owner->guid;
		$delfile->setFilename($tiny);
		$delfile->delete();
	}

	if ($small) {
		$delfile = new ElggFile();
		$delfile->owner_guid = $owner->guid;
		$delfile->setFilename($small);
		$delfile->delete();
	}

	if ($medium) {

		$delfile = new ElggFile();
		$delfile->owner_guid = $owner->guid;
		$delfile->setFilename($medium);
		$delfile->delete();
	}

	if ($large) {

		$delfile = new ElggFile();
		$delfile->owner_guid = $owner->guid;
		$delfile->setFilename($large);
		$delfile->delete();
	}

	if ($master) {

		$delfile = new ElggFile();
		$delfile->owner_guid = $owner->guid;
		$delfile->setFilename($master);
		$delfile->delete();
	}

	$prefix = "icons/$market->icon";
	$icon_sizes = array('topbar', 'tiny', 'small', 'medium', 'large', 'taggable', 'master', '800x200','325x200', '0', '');
	foreach ($icon_sizes as $key=>$size){
	    $delfile = new ElggFile();
	    $delfile->owner_guid = $owner->guid;
		$delfile->setFilename($prefix.$size.'.jpg');
		$delfile->delete();
	}
				
		// Delete the market post
				$rowsaffected = $market->delete();
				if ($rowsaffected > 0) {
		
		// Success message
//					system_message(elgg_echo("market:deleted", array($market->title)));
				} else {
					register_error(elgg_echo("market:notdeleted"));
				}
				
			// Forward to the referring page
//			forward(elgg_get_site_url() . "market");
			forward($container->getURL(), 'action');
		}
		
?>
