<?php
/**
 * Create or edit an insight
 *
 * @package ElggPages
 */

$variables = elgg_get_config('insights');
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
$insight_guid     = (int)get_input('insight_guid');
$container_guid   = (int)get_input('container_guid');
$parent_guid      = (int)get_input('parent_guid');
$referrer         = get_input('referrer');

elgg_make_sticky_form('insight');

if (!$input['title']) {
	register_error(elgg_echo('insights:error:no_title'));
	continue;
}

if ($insight_guid) {
	$insight = get_entity($insight_guid);
	if (!$insight || !$insight->canEdit()) {
		register_error(elgg_echo('insights:error:no_save'));
	}
	$new_insight = false;
} else {
	$insight          = new ElggObject();
	$insight->subtype = 'insight';
	$new_insight      = true;
}

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$insight->$name = $value;
	}
}

// need to add check to make sure user can write to container
$insight->container_guid = $container_guid;

if ($parent_guid) {
	$insight->parent_guid = $parent_guid;
}

if ($insight->save()) { 

	elgg_clear_sticky_form('insight');

	// Now save description as an annotation
	$insight->annotate('insight', $insight->description, $insight->access_id);

	system_message(elgg_echo('insights:saved'));

	if ($new_insight) {
		
		elgg_create_river_item([
				'view'=>'river/object/jot/create',
				'action_type'=> 'create',
				'subject_guid' => elgg_get_logged_in_user_guid(),
				'object_guid' => $insight->guid]);
	}
} else {
	register_error(elgg_echo('insights:error:no_save'));
}

forward($referrer);