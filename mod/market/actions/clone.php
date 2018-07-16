<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity || !$entity->canEdit()) {
	register_error('Invalid entity');
	forward(REFERER);
}

// clone the entity
$new = new ElggObject();
$new->subtype = $entity->getSubtype();
$new->access_id = $entity->access_id;
$new->owner_guid = elgg_get_logged_in_user_guid();//owned by the cloning user.  A user can clone the items of another user.
//$new->owner_guid = $entity->owner_guid;
$new->container_guid = $entity->container_guid;
$new->title = $entity->title;
$new->description = $entity->description;

if (!$new->save()) {
	register_error('There was an error adding the new entity');
	forward(REFERER);
}

// clone metadata
$metadata = elgg_get_metadata(array(
	'guid' => $entity->guid,
	'limit' => false
));

$names = array();
foreach ($metadata as $m) {
	if (in_array($m, $names)) {
		continue;
	}
	$names[] = $m->name;
	$attr = $m->name;
	
	$new->$attr = $entity->$attr;
}



// clone relationships
$dbprefix = elgg_get_config('dbprefix');
$q = "SELECT * FROM {$dbprefix}entity_relationships WHERE guid_one = {$entity->guid}";
$data = get_data($q);

foreach ($data as $d) {
	add_entity_relationship($new->guid, $d->relationship, $d->guid_two);
}

// clone reverse relationships
$dbprefix = elgg_get_config('dbprefix');
$q = "SELECT * FROM {$dbprefix}entity_relationships WHERE guid_two = {$entity->guid}";
$data = get_data($q);

foreach ($data as $d) {
	add_entity_relationship($d->guid_one, $d->relationship, $new->guid);
}

// clone the icon
$sizes = array('tiny', 'small', 'medium', 'large');
foreach ($sizes as $size) {
	$original = new ElggFile();
	$original->owner_guid = $entity->owner_guid;
	$original->setFilename("market/" . $entity->guid . $size . ".jpg");
	
	$prefix = "market/".$new->guid;
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $new->owner_guid;
	$filehandler->setMimeType('image/jpeg');
	$filehandler->setFilename($prefix . $size . ".jpg");
	$filehandler->open("write");
	copy($original->getFilenameOnFilestore(), $filehandler->getFilenameOnFilestore());
	$filehandler->close();
}

system_message('New item has been added');
forward(REFERER);