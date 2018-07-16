<?php
/**
 * Create or edit a cause
 *
 * @package ElggPages
 */

$variables = elgg_get_config('causes');
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
$cause_guid = (int)get_input('cause_guid');
$container_guid = (int)get_input('container_guid');
$parent_guid = (int)get_input('parent_guid');

elgg_make_sticky_form('cause');

if (!$input['title']) {
	register_error(elgg_echo('causes:error:no_title'));
	forward(REFERER);
}

if ($cause_guid) {
	$cause = get_entity($cause_guid);
	if (!$cause || !$cause->canEdit()) {
		register_error(elgg_echo('causes:error:no_save'));
		forward(REFERER);
	}
	$new_cause = false;
} else {
	$cause = new ElggObject();
	if ($parent_guid) {
		$cause->subtype = 'cause';
	} else {
		$cause->subtype = 'cause_top';
	}
	$new_cause = true;
}

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$cause->$name = $value;
	}
}

// need to add check to make sure user can write to container
$cause->container_guid = $container_guid;

if ($parent_guid) {
	$cause->parent_guid = $parent_guid;
}

if ($cause->save()) { 

	elgg_clear_sticky_form('cause');

	// Now save description as an annotation
	$cause->annotate('cause', $cause->description, $cause->access_id);

	system_message(elgg_echo('causes:saved'));

	if ($new_cause) {

	elgg_create_river_item([
			'view'=>'river/object/jot/create',
			'action_type'=> 'create',
			'subject_guid' => elgg_get_logged_in_user_guid(),
			'object_guid' => $cause->guid]);
		
	}
	forward("jot/cause/$cause_guid");
	//forward($cause->getURL());
} else {
	register_error(elgg_echo('causes:error:no_save'));
	forward(REFERER);
}
