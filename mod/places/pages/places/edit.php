<?php
/**
 * Edit a place
 *
 * @package Elggplaces
 */

elgg_gatekeeper();

$place_guid = (int)get_input('guid');
$revision = (int)get_input('annotation_id');
$place = get_entity($place_guid);
if (!places_is_place($place)) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

$container = $place->getContainerEntity();
if (!$container) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb($place->title, $place->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo("places:edit");

if ($place->canEdit()) {

	if ($revision) {
		$revision = elgg_get_annotation_from_id($revision);
		if (!$revision || !($revision->entity_guid == $place_guid)) {
			register_error(elgg_echo('places:revision:not_found'));
			forward(REFERER);
		}
	}

	$vars = places_prepare_form_vars($place, $place->parent_guid, $revision);
	
	$content = elgg_view_form('places/edit', array(), $vars);
} else {
	$content = elgg_echo("places:noaccess");
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
