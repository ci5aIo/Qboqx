<?php
/**
 * Revision view for history place
 *
 * @package Elggplaces
 */

$annotation = $vars['annotation'];
$place = get_entity($annotation->entity_guid);
if (!places_is_place($place)) {
	return;
}

$icon = elgg_view("places/icon", array(
	'annotation' => $annotation,
	'size' => 'small',
));

$owner_guid = $annotation->owner_guid;
$owner = get_entity($owner_guid);
if (!$owner) {
	return;
}
$owner_link = elgg_view('output/url', array(
	'href' => $owner->getURL(),
	'text' => $owner->name,
	'is_trusted' => true,
));

$date = elgg_view_friendly_time($annotation->time_created);

$title_link = elgg_view('output/url', array(
	'href' => $annotation->getURL(),
	'text' => $place->title,
	'is_trusted' => true,
));

$subtitle = elgg_echo('places:revision:subtitle', array($date, $owner_link));

$body = <<< HTML
<h3>$title_link</h3>
<p class="elgg-subtext">$subtitle</p>
HTML;

if (!elgg_in_context('widgets')) {
	$menu = elgg_view_menu('annotation', array(
		'annotation' => $annotation,
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz float-alt',
	));
}

$body = <<<HTML
<div class="mbn">
	$menu
	<h3>$title_link</h3>
	<span class="elgg-subtext">
		$subtitle
	</span>
</div>
HTML;

echo elgg_view_image_block($icon, $body);