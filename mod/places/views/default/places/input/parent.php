<?php
/**
 * Parent picker
 *
 * @uses $vars['value']          The current value, if any
 * @uses $vars['options_values']
 * @uses $vars['name']           The name of the input field
 * @uses $vars['entity']         Optional. The child entity (uses container_guid)
 */

elgg_load_library('elgg:places');

if (empty($vars['entity'])) {
	$container = elgg_get_page_owner_entity();
} else {
	$container = $vars['entity']->getContainerEntity();
}

$places = places_get_navigation_tree($container);
$options = array();

foreach ($places as $place) {
	$spacing = "";
	for ($i = 0; $i < $place['depth']; $i++) {
		$spacing .= "--";
	}
	$options[$place['guid']] = "$spacing " . $place['title'];
}

$defaults = array(
	'class' => 'elgg-places-input-parent-picker',
	'options_values' => $options,
);

$vars = array_merge($defaults, $vars);

echo elgg_view('input/dropdown', $vars);
