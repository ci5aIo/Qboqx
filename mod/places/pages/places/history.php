<?php
/**
 * History of revisions of a place
 *
 * @package Elggplaces
 */

$place_guid = get_input('guid');

$place = get_entity($place_guid);
if (!places_is_place($place)) {
	forward('', '404');
}

$container = $place->getContainerEntity();
if (!$container) {
	forward('', '404');
}

elgg_set_page_owner_guid($container->getGUID());

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "places/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($container->name, "places/owner/$container->username");
}
places_prepare_parent_breadcrumbs($place);
elgg_push_breadcrumb($place->title, $place->getURL());
elgg_push_breadcrumb(elgg_echo('places:history'));

$title = $place->title . ": " . elgg_echo('places:history');

$content = elgg_list_annotations(array(
	'guid' => $place_guid,
	'annotation_name' => 'place',
	'limit' => 20,
	'order_by' => "n_table.time_created desc",
));

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('places/sidebar/navigation', array('place' => $place)),
));

echo elgg_view_page($title, $body);
