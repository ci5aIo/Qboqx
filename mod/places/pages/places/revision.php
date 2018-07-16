<?php
/**
 * View a revision of place
 *
 * @package Elggplaces
 */

$id = get_input('id');
$annotation = elgg_get_annotation_from_id($id);
if (!$annotation) {
	forward();
}

$place = get_entity($annotation->entity_guid);
if (!places_is_place($place)) {
	forward(REFERER);
}

elgg_set_page_owner_guid($place->getContainerGUID());

elgg_group_gatekeeper();

$container = elgg_get_page_owner_entity();
if (!$container) {
	forward(REFERER);
}

$title = $place->title . ": " . elgg_echo('places:revision');

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "places/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($container->name, "places/owner/$container->username");
}
places_prepare_parent_breadcrumbs($place);
elgg_push_breadcrumb($place->title, $place->getURL());
elgg_push_breadcrumb(elgg_echo('places:revision'));

$content = elgg_view('object/place_top', array(
	'entity' => $place,
	'revision' => $annotation,
	'full_view' => true,
));

$sidebar = elgg_view('places/sidebar/history', array('place' => $place));

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
