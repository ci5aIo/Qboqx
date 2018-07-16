<?php
/**
 * Group places
 *
 * @package Elggplaces
 */


$group = elgg_get_page_owner_entity();

if ($group->places_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "places/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));


elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'place_top',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('places:none'),
);
$content = elgg_list_entities($options);
elgg_pop_context();

$new_link = elgg_view('output/url', array(
	'href' => "places/add/$group->guid",
	'text' => elgg_echo('places:add'),
	'is_trusted' => true,
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('places:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
