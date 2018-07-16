<?php
/**
 * Elgg Market Plugin
 * @package quebx
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get input data
$guid = (int) get_input('guid');
$parent_guid = (int) get_input('parent_guid');

$title = get_input('itemtitle');
$category = get_input('itemcategory');
$body = get_input('itembody');
$access = get_input('access_id');
$tags = get_input('itemlabels');

elgg_make_sticky_form('quebx');

// Convert string of tags into a preformatted array
$tagarray = string_to_tag_array($tags);
$error = FALSE;

// Make sure the title / description aren't blank
if (empty($title) || empty($body)) {
	register_error(elgg_echo("quebx:blank").' here');
	$error =  TRUE;
} else {

  if ($guid) {
    // editing an existing quebx item
    // Make sure we actually have permission to edit
    $item_profile = get_entity($guid);
	$parent_item = get_entity($parent_guid);
/*    // This works (11/19/2013)
    if (!elgg_instanceof($marketpost, 'object','market') && $marketpost->canEdit()) {
      register_error(elgg_echo('market:notfound'));
*/

/*  // This errors out
     if ((!elgg_instanceof($marketpost, 'object','market')   ||
		 !elgg_instanceof($marketpost,'object','accessory') || 
		 !elgg_instanceof($marketpost,'object','component')) && $marketpost->canEdit()) {
      register_error(elgg_echo('market:notfound').' '.$marketpost->getSubtype().' '.$guid.' '.$parent_guid.' '.$marketpost->canEdit());
//      register_error(elgg_echo('market:notfound'));
      $error = TRUE;
    }*/
  } else {
    // creating a new market item
    $user_guid = elgg_get_logged_in_user_guid();
    $quebxpost = new ElggObject();
    $quebxpost->subtype = 'item';
    $quebxpost->owner_guid = $user_guid;
    $quebxpost->container_guid = $user_guid;
  }

  if (!$error) {
	  $quebxpost->access_id = $access;
	  $quebxpost->title = $title;
	  $quebxpost->description = $body;
	  $quebxpost->quebxcategory = $category; // until I understand how these parts relate, leave as original "quebxcategory"
	  $quebxpost->tags = $tagarray;

	  if (!$quebxpost->save()) {
	    register_error(elgg_echo('quebx:save:failure'));
	    $error = TRUE;
	  }
  }

  if ($error) {
    if ($guid) {
      forward("quebx/edit/" . $guid);
    } else {
      forward("quebx/add");
    }
  }

  // no errors, so clear the sticky form and save any file attachment

	elgg_clear_sticky_form('quebx');

	// Now see if we have a file icon
	if ((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) {

		$prefix = "quebx/".$item_profile->guid;
		$filehandler = new ElggFile();
		$filehandler->owner_guid = $item_profile->owner_guid;
		$filehandler->setFilename($prefix . ".jpg");
		$filehandler->open("write");
		$filehandler->write(get_uploaded_file('upload'));
		$filehandler->close();

		$thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
		$thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
		$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),153,153, true);
		$thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
		if ($thumbtiny) {

			$thumb = new ElggFile();
			$thumb->owner_guid = $item_profile->owner_guid;
			$thumb->setMimeType('image/jpeg');
			$thumb->setFilename($prefix."tiny.jpg");
			$thumb->open("write");
			$thumb->write($thumbtiny);
			$thumb->close();
			$thumb->setFilename($prefix."small.jpg");
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			$thumb->setFilename($prefix."medium.jpg");
			$thumb->open("write");
			$thumb->write($thumbmedium);
			$thumb->close();
			$thumb->setFilename($prefix."large.jpg");
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
		}
	}

	// Success message
	system_message(elgg_echo("quebx:posted"));

	if (elgg_view_exists("forms/quebx/edit/$category")) {
	  forward("quebx/edit_more/{$marketpost->guid}/$category");
	} else {
	  // Forward to the main market page
	  forward("market");
	}
}
