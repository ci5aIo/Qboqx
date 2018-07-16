<?php
/**
 * View for place object
 *
 * @package Elggplaces
 *
 * @uses $vars['entity']    The place object
 * @uses $vars['full_view'] Whether to display the full view
 * @uses $vars['revision']  This parameter not supported by elgg_view_entity()
 */

//echo elgg_dump($vars);
$full          = elgg_extract('full_view', $vars, FALSE);
$section       = elgg_extract('this_section', $vars, 'Summary');
$place         = elgg_extract('entity', $vars, FALSE);
$item_guid     = elgg_extract('asset', $vars, $place->asset);
$item          = get_entity($item_guid);
$revision      = elgg_extract('revision', $vars, FALSE);

if (!$place) {
	return TRUE;
}

// places used to use Public for write access
if ($place->write_access_id == ACCESS_PUBLIC) {
	// this works because this metadata is public
	$place->write_access_id = ACCESS_LOGGED_IN;
}


if ($revision) {
	$annotation = $revision;
} else {
	$annotation = $place->getAnnotations(array(
		'annotation_name' => 'place',
		'limit' => 1,
		'reverse_order_by' => true,
	));
	if ($annotation) {
		$annotation = $annotation[0];
	}
}

$place_icon = elgg_view('places/icon', array('annotation' => $annotation, 'size' => 'small'));

$editor = get_entity($annotation->owner_guid);
$editor_link = elgg_view('output/url', array(
	'href' => "places/owner/$editor->username",
	'text' => $editor->name,
	'is_trusted' => true,
));

$date = elgg_view_friendly_time($annotation->time_created);
$editor_text = elgg_echo('places:strapline', array($date, $editor_link));
$categories = elgg_view('output/categories', $vars);

$comments_count = $place->countComments();
//only display if there are commments
if ($comments_count != 0 && !$revision) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $place->getURL() . '#comments',
		'text' => $text,
		'is_trusted' => true,
	));
} else {
	$comments_link = '';
}

$subtitle = "$editor_text $asset_link $owner_link $tags $comments_link";
//$subtitle = "$editor_text $comments_link $categories";

// do not show the metadata and controls in widget view
if (!elgg_in_context('widgets')) {
	// If we're looking at a revision, display annotation menu
	if ($revision) {
		$metadata = elgg_view_menu('annotation', array(
			'annotation' => $annotation,
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz float-alt',
		));
	} else {
		// Regular entity menu
		$metadata = elgg_view_menu('entity', array(
			'entity' => $vars['entity'],
			'handler' => 'places',
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		));
	}
}

if ($full) {
	$text = elgg_view('output/longtext', array('value' => $annotation->value));
//	$body = elgg_view('output/longtext', array('value' => $annotation->value));

	$params = array(
		'entity' => $place,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	
	$summary = elgg_view('object/elements/summary', $params);
	$extra = elgg_view("display/place",array('entity'=>$place, 'asset'=>$item, 'this_section'=>$section));
    $body = "$text $extra";
	
	echo elgg_view('object/elements/full', array(
		'entity'  => $place,
		'title'   => false,
		'icon'    => $place_icon,
		'summary' => $summary,
		'body'    => $body,
	));

} else {
	// brief view

	$excerpt = elgg_get_excerpt($place->description);

	$params = array(
		'entity' => $place,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($place_icon, $list_body);
}
