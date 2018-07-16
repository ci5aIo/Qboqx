<?php
/**
 * List a user's friends' places
 *
 * @package Elggplaces
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('', '404');
}

elgg_push_breadcrumb($owner->name, "places/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button();

$title = elgg_echo('places:friends');

$content = elgg_list_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'place_top',
	'full_view' => false,
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'relationship_join_on' => 'container_guid',
	'no_results' => elgg_echo('places:none'),
));

$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
