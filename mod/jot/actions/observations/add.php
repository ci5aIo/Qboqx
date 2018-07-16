<?php

// Make sure we're logged in
gatekeeper();

// Get input data
/*$variables = elgg_get_config('observations');
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
*/
elgg_make_sticky_form('observation');

$item_guid        = (int)get_input('item_guid');
$observation_guid = (int)get_input('observation_guid');
$container_guid   = (int)get_input('container_guid');

$type             = get_input('type');
$subtype          = get_input('subtype');
$title            = get_input('title');
$description      = get_input('description');
$observation_type = get_input('observation_type');
$number           = get_input('observation_number');
$aspect           = get_input('aspect');
$referrer         = get_input('referrer');
$access_id        = get_input('access_id');
$write_access_id  = get_input('write_access_id');
$tags             = get_input('tags');
$container_guid   = get_input('container_guid');
$guid             = get_input('guid');
$entity           = get_input('entity');
$item             = get_entity($item_guid);
$asset            = get_entity('asset');

$user             = elgg_get_logged_in_user_entity();
$relationship     = $subtype;

// Convert string of tags into a preformatted array
$tagarray         = string_to_tag_array($tags);
	
// Make sure the title / description aren't blank
if (empty($title) || empty($description)) {
/*
if ($observation_guid) {
	$observation = get_entity($observation_guid);
	if (!$observation || !$observation->canEdit()) {
		register_error(elgg_echo('jot:error:no_save'));
		forward(REFERER);
	}
	$new_observation = false;
} else {
	$observation = new ElggObject();
	$observation->subtype = 'observation';
	$new_observation = true;
}
	
if (!$title || !$description) {
*/
	register_error(elgg_echo("jot:blank"));
	forward(REFERER);

} else {
    	
	// save the observation
	
/*	if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$observation->$name = $value;
		//echo "$task->$name = $value";
	}
}
*/
//    $observation = observations_save_observation($title, $description, $status, $number, $asset, $tags, $access);
	// Initialise a new ElggObject
    $observation                     = new ElggObject();
	$new_observation                 = true;

	// Tell the system it's an observation
	$observation->subtype            = $subtype;

	// Set its owner to the current user
	$observation->owner_guid         = elgg_get_logged_in_user_guid();

	// Set its access_id
	$observation->access_id          = $access;

	// Set its title and description
	$observation->title              = $title;
	$observation->description        = $description;
	$observation->type               = $type;
	$observation->status             = $status;
	$observation->observation_type   = $observation_type;
	$observation->observation_number = $number;
    $observation->asset              = $item_guid;
	$observation->ts                 = time();
	$observation->start_date         = $start_date;
	$observation->end_date           = $end_date;
	$observation->number             = $number;
	$observation->status             = $status;
	$observation->assigned_to        = $assigned_to;
	     
	if (is_array($tagarray)) {
	  $observation->tags             = $tagarray;
	}
	
	if (!$observation->save()) {
		register_error(elgg_echo("jot:error:no_save"));
		forward(REFERRER);
//		forward("mod/jot/add/observation");
	}

    elgg_clear_sticky_form('observation');

	// Now save description as an annotation
	$observation->annotate('observation', $description, $access);

		// Success message
	system_message(elgg_echo("jot:posted"));

	// Now see if we have a file icon
	if ((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) {

	  $prefix                        = "jot/".$observation->guid;
	  $filehandler                   = new ElggFile();
	  $filehandler->owner_guid       = $observation->owner_guid;
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
	    $thumb->owner_guid = $observation->owner_guid;
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

	// Extract the new observation into an array
	$element = get_entity($observation->guid);
	register_error(elgg_echo(elgg_dump($element)));
	
	// Create a relationship to the item, provided one exists
	if ($relationship) {
	// Create a relationship
	add_entity_relationship($element->guid, $relationship, $item_guid);
	}

	// Add to river
	
	elgg_create_river_item([
			'view'=>'river/object/jot/create',
			'action_type'=> 'create',
			'subject_guid' => $element->owner_guid,
			'object_guid' => $element->guid]);

    // Forward to the originating item
	forward($referrer);
}
