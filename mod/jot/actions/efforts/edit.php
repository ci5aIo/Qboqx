<?php
/**
 * Create or edit an effort
 *
 * @package ElggPages
 */

$variables = elgg_get_config('efforts');
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

// Get guids
$effort_guid      = (int)get_input('effort_guid');
$container_guid   = (int)get_input('container_guid');
$parent_guid      = (int)get_input('parent_guid');
$referrer         = get_input('referrer');

$effort           = get_entity($effort_guid);
$effort_state     = $input['state'];
//system_message('Effort state: '.$effort_state);
//system_message('Effort state: '.$effort->state);

// Set parts as components for efforts that are "Fixed"
if ($effort_state == 5){
	$relationship          = 'component';
	$parts = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'relationship' => 'part',
		'relationship_guid' => $effort_guid,
	    'inverse_relationship' => true,
		'limit' => false,
	));
	foreach ($parts as $component){
		add_entity_relationship($component->guid, $relationship, $effort->asset);
	}
}

elgg_make_sticky_form('effort');

if (!$input['title']) {
	register_error(elgg_echo('efforts:error:no_title'));
	continue;
}

if ($effort_guid) {
	$effort = get_entity($effort_guid);
	if (!$effort || !$effort->canEdit()) {
		register_error(elgg_echo('efforts:error:no_save'));
	}
	$new_effort = false;
} else {
	$effort = new ElggObject();
	$effort->subtype = 'effort';
	$new_effort = true;
}

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$effort->$name = $value;
	}
}

// need to add check to make sure user can write to container
$effort->container_guid = $container_guid;

if ($parent_guid) {
	$effort->parent_guid = $parent_guid;
}

if ($effort->save()) { 

	elgg_clear_sticky_form('effort');

	// Now save description as an annotation
	$effort->annotate('effort', $effort->description, $effort->access_id);

	system_message(elgg_echo('efforts:saved'));

	if ($new_effort) {
		elgg_create_river_item([
			'view'=>'river/object/jot/create',
			'action_type'=> 'create',
			'subject_guid' => elgg_get_logged_in_user_guid(),
			'object_guid' => $effort->guid]);
	}

} else {
	register_error(elgg_echo('efforts:error:no_save'));
}

//forward($referrer);