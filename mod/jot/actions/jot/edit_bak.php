<?php
/**
 * Elgg jot Plugin
 * @package jot
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 */

// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get input data
$guid        = (int) get_input('guid');
$parent_guid = (int) get_input('parent_guid');
$aspect      = get_input('aspect');
$aspect_02   = get_input('aspect_02');
//$title       = get_input('title');
// Receive jot information
$this_jot    = get_input('jot');
// Receive line items
$items        = get_input('item');

$entity      = get_entity($guid);
/*
$title = get_input('title');
$category = get_input('category');
$body = get_input('body');
$access = get_input('access_id');
$tags = get_input('tags');
*/
register_error('$guid: '.$guid);
//register_error('$title: '.$title);
register_error('$aspect: '.$aspect);
register_error('$aspect_02: '.$aspect_02);

if ($aspect_02){
	$variables = elgg_get_config("{$aspect}_{$aspect_02}");	
}
else {
	$variables = elgg_get_config("{$aspect}s");	
}

// Process jot 
$input = array();
foreach ($variables as $name => $type) {
	$input[$name] = get_input($name);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

$title = $input['title'];

register_error('$title: '.$title);
//system_message(elgg_dump($variables));
//system_message(elgg_echo($aspect));

elgg_make_sticky_form('jot');

// Convert string of tags into a preformatted array
$tagarray = string_to_tag_array($tags);
$error = FALSE;

// Make sure the title isn't blank
if (empty($title)) {
	register_error(elgg_echo("jot:blank"));
	$error =  TRUE;
} else {

  if ($guid) {
    // editing an existing jot item
    $jot = get_entity($guid);
	$parent_item = get_entity($parent_guid);

  } else {
    // creating a new jot item
    $user_guid = elgg_get_logged_in_user_guid();
    $jot = new ElggObject();
    $jot->subtype = 'jot';
    $jot->owner_guid = $user_guid;
    $jot->container_guid = $user_guid;
  }

  if (!$error) {
	  $jot->access_id = $access;
	  $jot->title = $title;
	  $jot->description = $body;
	  $jot->jotcategory = $category;
	  $jot->tags = $tagarray;
	  // Process input
	  foreach($input as $key => $value){
			$jot->$key = $value;
	  }
	  // Process line items
      foreach ($items as $key => $value) {
			$jot->$key = $value;
		}

	  if (!$jot->save()) {
	    register_error(elgg_echo('jot:save:failure'));
	    $error = TRUE;
	  }
  }

  if ($error) {
    if ($guid) {
      forward("jot/edit/" . $guid);
    } else {
      forward("jot/add");
    }
  }

  // no errors, so clear the sticky form and save any file attachment

	elgg_clear_sticky_form('jot');

	// Now see if we have a file icon
	if ((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) {

		$prefix = "jot/".$jot->guid;
		$filehandler = new ElggFile();
		$filehandler->owner_guid = $jot->owner_guid;
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
			$thumb->owner_guid = $jot->owner_guid;
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
	system_message(elgg_echo("jot:posted"));

	if (elgg_view_exists("forms/jot/edit/$category")) {
	  forward("jot/edit_more/{$jot->guid}/$category");
	} else {
	  // Forward to the main jot page
	  forward("jot");
	}
}
