<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */

// Get variables
$access_id      = (int) get_input("access_id");
$owner_guid     = (int) get_input('owner_guid'    , elgg_get_logged_in_user_guid());
$container_guid = (int) get_input('container_guid', $owner_guid);
$location       = (int) get_input('places_list');
$item_guid      = (int) get_input('item_guid'     , 0);
$element_type   =       get_input("element_type");
$attachments    =       get_input('attach_guids');
system_message("$element_type attached to $location");

$relationship = $element_type;

elgg_make_sticky_form('files_selection');

if (!$attachments){
register_error('No Attachments');
	forward(REFERER);
}

if ($attachments){
foreach($attachments as $i){
	if(!check_entity_relationship($i, $relationship, $item_guid)){
		add_entity_relationship($i, $relationship, $item_guid);
		system_message("$element_type attached");
	}	
}

	forward(REFERER);
}

