<?php

$element_type     = get_input('element_type');
$container_guid   = (int) get_input('guid');
$element_title    = get_input('element_title');
$asset            = (int) get_input('asset');
$container_entity = get_entity($container_guid);
$subtype          = 'item';
$aspect           = $element_type;
$relationship     = $element_type;
$access_id        = elgg_extract('access_id', $vars, get_default_access());
/*
  if ($element_type == 'accessory'){
      $subtype = $container_entity->getSubtype();
  }else {
  	  $subtype = $element_type;
  }
*/
if (empty($asset        )) {$asset         = $container_guid;}
if (empty($element_title)) {$element_title = "new $element_type";}
if (empty($subtype      )) {$subtype       = $container_entity->getSubtype();}

if (!$container_entity || !$container_entity->canEdit()) {
	register_error('Invalid container');
	forward(REFERER);
}

// Create the element
$new_element                 = new ElggObject();
$new_element->subtype        = $subtype;
$new_element->access_id      = $access_id;
$new_element->owner_guid     = elgg_get_logged_in_user_guid();
$new_element->container_guid = $container_guid;
$new_element->asset          = $asset;
$new_element->title          = $element_title;
$new_element->description    = '';


if (!$new_element->save()) {
	register_error('Action Failed: Error creating '.$element_type);
	forward(REFERER);
}

// Now see if we have a file icon
if ((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) {

	$prefix                  = "market/".$new_element->guid;
	$filehandler             = new ElggFile();
	$filehandler->owner_guid = $new_element->owner_guid;
	$filehandler->setFilename($prefix . ".jpg");
	$filehandler->open("write");
	$filehandler->write(get_uploaded_file('upload'));
	$filehandler->close();

	$thumbtiny   = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
	$thumbsmall  = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
	$thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),153,153, true);
	$thumblarge  = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
	if ($thumbtiny) {

		$thumb             = new ElggFile();
		$thumb->owner_guid = $new_element->owner_guid;
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


$element = get_entity($new_element->guid);

// Create the relationship
add_entity_relationship($element->guid, $relationship, $container_guid);


//system_message('Created new '.$element_type);
//forward('market/add/'.$element->guid);
//forward('market/edit/'.$element->guid);
//forward(REFERER);