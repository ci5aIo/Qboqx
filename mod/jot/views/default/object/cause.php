<!-- page: mod\jot\pages\jot\view.php -->
<!-- view: mod\jot\views\default\object\cause.php -->

<?php
$full       = elgg_extract('full_view', $vars, FALSE);
$section    = elgg_extract('this_section', $vars, 'Summary');
$cause      = elgg_extract('entity', $vars, FALSE);
$item       = elgg_extract('asset', $vars, FALSE);
$owner      = $cause->getOwnerEntity();
$tu         = $cause->time_updated;
$container  = $cause->getContainerEntity();
$jot        = elgg_get_excerpt($cause->description);
$container_link    = elgg_view('output/url', array(
				 'href' => "jot/view/$container->guid",
				 'text' => $container->title,
)).'<br>';
$owner_link = elgg_view('output/url', array(
	'href' => "jot/owned/{$owner->username}",
	'text' => $owner->name,
));

$tags = elgg_view('output/tags', array('tags' => $cause->tags));
$date = elgg_view_friendly_time($cause->time_created);
$comments_count = $cause->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $cause->getURL() . '#jot-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $cause,
	'handler' => 'jot',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));
$text = elgg_view('output/longtext', array('value' => $cause->description));
$extra = elgg_view('jot/display/cause',array('entity'=>$cause, 'asset'=>$item, 'this_section'=>$section));
$body = "$text $extra";


$subtitle = "$container_link $owner_link $tags $date $comments_link";

	$params = array(
//		'entity' => $cause,
		'header' => $header,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);

	$owner_icon = elgg_view_entity_icon($owner, 'small');

	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$cause_info = elgg_view_image_block($owner_icon, $list_body);

echo <<<HTML
$cause_info
<div class="jot elgg-content">
	$body
</div>
HTML;
