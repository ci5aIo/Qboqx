<?php
/**
 * History of this item
 *
 * @uses $vars['market']
 */

$title = elgg_echo('places:history');

if ($vars['market']) {
	$options = array(
		'guid' => $vars['market']->guid,
		'annotation_name' => 'place',
		'limit' => 20,
		'reverse_order_by' => true
	);
	elgg_push_context('widgets');
	$content = elgg_list_annotations($options);
}

echo elgg_view_module('aside', $title, $content);