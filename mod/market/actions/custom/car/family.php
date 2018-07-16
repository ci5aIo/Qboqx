<?php

$guid = get_input('guid');
$entity = get_entity($guid);

// create relationships for drivers
$drivers = get_input('drivers');
if (is_string($drivers)) {
    $drivers = explode(',', $drivers);
}

// first clear old relationships
remove_entity_relationships($guid, 'driver', true);

// now add new ones
if (is_array($drivers)) {
	foreach ($drivers as $driver_guid) {
		add_entity_relationship($driver_guid, 'driver', $guid);
	}
}



// Shoes
$shoes = get_input('shoes');
if (is_string($shoes)) {
//    $shoes = explode(',', $drivers); // 2013-10-28 - Original appears to be incorrect. Replaced with below.
    $shoes = explode(',', $shoes);
}

// first clear old relationships
remove_entity_relationships($guid, 'car_shoes', true);

// now add new ones
if (is_array($shoes)) {
	foreach ($shoes as $shoe_guid) {
		add_entity_relationship($shoe_guid, 'car_shoes', $guid);
	}
}

// Accessories
$accessories = get_input('accessories');
if (is_string($accessories)) {
    $accessories = explode(',', $accessories);
}

// first clear old relationships
remove_entity_relationships($guid, 'accessory', true);

// now add new ones
if (is_array($accessories)) {
	foreach ($accessories as $accessory_guid) {
		add_entity_relationship($accessory_guid, 'accessory', $guid);
	}
}

// Groups
$groups = get_input('groups');
if (is_string($groups)) {
    $groups = explode(',', $groups);
}

// first clear old relationships
remove_entity_relationships($guid, 'shared', true);

// now add new ones
if (is_array($groups)) {
	foreach ($groups as $group_guid) {
		add_entity_relationship($group_guid, 'shared', $guid);
	}
}

/***
 * 
 *		HANDLE FILE UPLOAD
 * 
 */

if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] == 0) {
	$prefix = "marketfile/{$entity->guid}/";
	$time = time();
	$ext = end(explode(".", $_FILES['upload']['name']));
	$name = 'uploadedfile';
	if ($ext) {
		$name .= '.' . elgg_strtolower($ext);
	}
//	$filestorename = elgg_strtolower($time . $name);
	$filestorename = elgg_strtolower($time .'/'. $name);
	
	$file = new ElggFile();
	$file->owner_guid = $entity->owner_guid;
	$file->container_guid = $entity->owner_guid;
	$mime_type = $file->detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);
	$file->setFilename($prefix . $filestorename);
	$file->setMimeType($mime_type);
	$file->originalfilename = $_FILES['upload']['name'];
	$file->simpletype = elgg_get_file_simple_type($mime_type);

	// Open the file to guarantee the directory exists
	$file->open("write");
	$file->close();
	$result = move_uploaded_file($_FILES['upload']['tmp_name'], $file->getFilenameOnFilestore());
	
	if ($result) {
		// save our file url
		// we can get the original file from this
		// at [url]/market/file/[guid]/[time]/[name]
		// you can create your own file system organization however you want
		// ideally this is added as a new elgg entity, but that's a bigger scope == more time right now
		// see /pages/market/file.php
		$entity->upload = $entity->guid . '/' . $time . '/' . $name;
	}
}

/*
 * Metadata storage - string vs array

$entity->upload = 'path/to/file';

$entity->upload = array(
	'path/to/file1',
	'path/to/file2',
	'path/to/file3'
);
 * 
 * 
 */


// Finally, lets identify this object as part of this family
// turn family values into a unique string
// this is unique per content type and depends on what is considered important to define the family
$string = $entity->title . $entity->manufacturer;

// components is family
// components/values
$names = $entity->component_names;
$values = $entity->component_values;

foreach ($names as $key => $name) {
	if ($name === '' || $values[$key] == '') {
		// don't show empty values
		continue;
	}
	
	$string .= $name . $values[$key];
}


// relationships
if (is_array($drivers)) {
	foreach ($drivers as $driver_guid) {
		$string .= $driver_guid;
	}
}

if (is_array($shoes)) {
	foreach ($shoes as $shoe_guid) {
		$string .= $shoe_guid;
	}
}
if (is_array($accessories)) {
	foreach ($accessories as $accessory_guid) {
		$string .= $accessory_guid;
	}
}
if (is_array($groups)) {
	foreach ($groups as $group_guid) {
		$string .= $group_guid;
	}
}


// this is how we can tell what's related
// this token will get updated each time an entity family information gets updated
// so changing family info will 'fork' it
$entity->family_token = md5($string);