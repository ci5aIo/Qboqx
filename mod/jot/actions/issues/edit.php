<?php
/**
 * Create or edit a issue
 *
 * @package ElggPages
 */

$variables = elgg_get_config('issues');
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
$issue_guid = (int)get_input('issue_guid');
$container_guid = (int)get_input('container_guid');
$parent_guid = (int)get_input('parent_guid');

elgg_make_sticky_form('issue');

if (!$input['title']) {
	register_error(elgg_echo('issues:error:no_title'));
	forward(REFERER);
}

if ($issue_guid) {
	$issue = get_entity($issue_guid);
	if (!$issue || !$issue->canEdit()) {
		register_error(elgg_echo('issues:error:no_save'));
		forward(REFERER);
	}
	$new_issue = false;
} else {
	$issue = new ElggObject();
	if ($parent_guid) {
		$issue->subtype = 'issue';
	} else {
		$issue->subtype = 'issue_top';
	}
	$new_issue = true;
}

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$issue->$name = $value;
		//echo "$issue->$name = $value";
	}
}

// need to add check to make sure user can write to container
$issue->container_guid = $container_guid;

if ($parent_guid) {
	$issue->parent_guid = $parent_guid;
}

if ($issue->save()) { 

	elgg_clear_sticky_form('issue');

	// Now save description as an annotation
	$issue->annotate('issue', $issue->description, $issue->access_id);

	system_message(elgg_echo('issues:saved'));

	if ($new_issue) {
		
		elgg_create_river_item([
				'view'=>'river/object/jot/create',
				'action_type'=> 'create',
				'subject_guid' => elgg_get_logged_in_user_guid(),
				'object_guid' =>  $issue->guid]);
	}

	forward($issue->getURL());
} else {
	register_error(elgg_echo('issues:error:no_save'));
	forward(REFERER);
}
