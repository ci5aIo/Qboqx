<?php
/**
 * Create a new place
 *
 * @package Elggplaces
 */

elgg_gatekeeper();

$container_guid = (int) get_input('guid');
$container = get_entity($container_guid);
if (!$container) {
	forward(REFERER);
}

$parent_guid = 0;
$place_owner = $container;
if (elgg_instanceof($container, 'object')) {
	$parent_guid = $container->getGUID();
	$place_owner = $container->getContainerEntity();
}

elgg_set_page_owner_guid($place_owner->getGUID());

$title = elgg_echo('places:add');
elgg_push_breadcrumb($title);

$vars = places_prepare_form_vars(null, $parent_guid);
$content = elgg_view_form('places/edit', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
