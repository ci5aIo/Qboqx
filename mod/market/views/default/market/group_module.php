<?php
/**
 * Group items
 *
 * @package Elggitems
 */


$group = elgg_get_page_owner_entity();

if ($group->items_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "market/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));


elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'item',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('items:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "market/add/$group->guid",
	'text' => elgg_echo('items:add'),
	'is_trusted' => true,
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('items:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
