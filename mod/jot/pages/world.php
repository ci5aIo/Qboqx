<?php
/**
 * List all places
 *
 * @package Elggplaces
 */

$title = elgg_echo('places:all');

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('places'));

elgg_register_title_button();

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'place_top',
	'full_view' => false,
	'no_results' => elgg_echo('places:none'),
));

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('places/sidebar'),
));

echo elgg_view_page($title, $body);
