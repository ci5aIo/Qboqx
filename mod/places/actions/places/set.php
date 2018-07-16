<?php
/**
 * QuebX set place action
 *
 * @package ElggFile
 */

// Get variables
$access_id      = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid', 0);
$owner_guid     = (int) get_input('owner_guid'    , elgg_get_logged_in_user_guid());
$location_guid  = (int) get_input('place');
$item_guid      = (int) get_input('item_guid'     , 0);
$element_type   =       get_input('element_type', 'place');
$location_label =       get_input('element_title'); 
//$element_type   = $item->getSubtype();
//system_message("$container_guid set to $location_guid");
//system_message("location label: $location_label");

$relationship = 'place';
$relationships = get_entity_relationships ($container_guid, $inverse_relationship=false);

//echo elgg_view('input/file', array('name' => 'upload')); 

if ($item_guid == 0) {
	$item_guid = $container_guid;
}
$item           = get_entity($item_guid);
if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}
elgg_make_sticky_form('places_selection');

if (!$location_guid && !$location_label){
register_error('No Places');
//	forward(REFERER);
}

if ($location_label){
	$item->location = $location_label;
//	forward(REFERER); 
}

if ($location_guid){
	$item->location = $location_guid;
	if(!check_entity_relationship($location_guid, $relationship, $container_guid)){
		add_entity_relationship($location_guid, $relationship, $container_guid);
//		system_message("$container_guid set to $location_guid $relationship");
    }
/*$contents = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'contents',
		'relationship_guid' => $item_guid,
		'inverse_relationship' => true,
		'limit' => false,
));
*/
	if(!check_entity_relationship($container_guid, 'contents', $location_guid)){
		add_entity_relationship($container_guid, 'contents', $location_guid);
    }
//	forward(REFERER);
}

