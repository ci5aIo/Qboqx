<!--Pages: mod\jot\pages\issues\view.php<br>
Views: mod\jot\views\default\object\issue.php
-->
<?php

$full      = elgg_extract('full_view', $vars, FALSE);
$section   = elgg_extract('this_section', $vars, 'Summary');
$issue     = elgg_extract('entity', $vars, FALSE);
$item      = get_entity($issue->asset);
$item_subtype = $item->getSubtype();
$owner     = $issue->getOwnerEntity();
$tu        = $issue->time_updated;
$container = $issue->getContainerEntity();
$jot       = elgg_get_excerpt($issue->description);
$item_link = elgg_view('output/url', array(
			     'href' => "jot/$item_subtype/$item->guid",
				 'text' => $item->title,
)).'<br>';
$owner_link = elgg_view('output/url', array(
				'href' => "jot/owned/{$owner->username}",
				'text' => $owner->name,
));

$tags = elgg_view('output/tags', array('tags' => $issue->tags));
$date = elgg_view_friendly_time($issue->time_created);
$comments_count = $issue->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $issue->getURL() . '#jot-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $issue,
//	'entity' => $vars['entity'],
	'handler' => 'jot',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));
$text = elgg_view('output/longtext', array('value' => $issue->description));
$extra = elgg_view('jot/display/issue',array('entity'=>$issue, 'asset'=>$item, 'this_section'=>$section));
$body = "$text $extra";


$subtitle = "$item_link $owner_link $tags $date $comments_link";
//$subtitle = "{$category}{$custom}{$supplemental}{$pick}<br>{$author_text} {$date} {$comments_link}";

	$params = array(
//		'entity' => $issue,
		'header' => $header,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);

	$owner_icon = elgg_view_entity_icon($owner, 'small');

	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$issue_info = elgg_view_image_block($owner_icon, $list_body);

echo <<<HTML
$issue_info
<div class="jot elgg-content">
	$body
</div>
HTML;
