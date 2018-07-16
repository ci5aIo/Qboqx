<?php
/**
 * Create or edit a place
 *
 * @package Elggplaces
 */

$variables = elgg_get_config('places');
$input = array();
foreach ($variables as $name => $type) {
	if ($name == 'title') {
		$input[$name] = htmlspecialchars(get_input($name, '', false), ENT_QUOTES, 'UTF-8');
	} else {
		$input[$name] = get_input($name);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

// Get guids
$place_guid = (int)get_input('place_guid');
$container_guid = (int)get_input('container_guid');
$parent_guid = (int)get_input('parent_guid');

elgg_make_sticky_form('place');

if (!$input['title']) {
	register_error(elgg_echo('places:error:no_title'));
	forward(REFERER);
}

if ($place_guid) {
	$place = get_entity($place_guid);
	if (!places_is_place($place) || !$place->canEdit()) {
		register_error(elgg_echo('places:cantedit'));
		forward(REFERER);
	}
	$new_place = false;
} else {
	$place = new ElggObject();
	if ($parent_guid) {
		$place->subtype = 'place';
	} else {
		$place->subtype = 'place_top';
	}
	$new_place = true;
}

if (sizeof($input) > 0) {
	// don't change access if not an owner/admin
	$user = elgg_get_logged_in_user_entity();
	$can_change_access = true;

	if ($user && $place) {
		$can_change_access = $user->isAdmin() || $user->getGUID() == $place->owner_guid;
	}
	
	foreach ($input as $name => $value) {
		if (($name == 'access_id' || $name == 'write_access_id') && !$can_change_access) {
			continue;
		}
		if ($name == 'parent_guid') {
			continue;
		}

		$place->$name = $value;
	}
}

// need to add check to make sure user can write to container
$place->container_guid = $container_guid;

if ($parent_guid && $parent_guid != $place_guid) {
	// Check if parent isn't below the place in the tree
	if ($place_guid) {
		$tree_place = get_entity($parent_guid);
		while ($tree_place->parent_guid > 0 && $place_guid != $tree_place->guid) {
			$tree_place = get_entity($tree_place->parent_guid);
		}
		// If is below, bring all child elements forward
		if ($place_guid == $tree_place->guid) {
			$previous_parent = $place->parent_guid;
			$children = elgg_get_entities_from_metadata(array(
				'metadata_name' => 'parent_guid',
				'metadata_value' => $place->getGUID()
			));
			if ($children) {
				foreach ($children as $child) {
					$child->parent_guid = $previous_parent;
				}
			}
		}
	}
	$place->parent_guid = $parent_guid;
}

if ($place->save()) {

	elgg_clear_sticky_form('place');

	// Now save description as an annotation
	$place->annotate('place', $place->description, $place->access_id);

	// Create the relationship
	if ($container_guid){
		add_entity_relationship($place->guid, 'space', $container_guid);
	}

	system_message(elgg_echo('places:saved'));
	
	if ($new_place) {
		elgg_create_river_item(array(
			'view' => 'river/object/place/create',
			'action_type' => 'create',
			'subject_guid' => elgg_get_logged_in_user_guid(),
			'object_guid' => $place->guid,
		));
	}

	forward($place->getURL());
} else {
	register_error(elgg_echo('places:notsaved'));
	forward(REFERER);
}
