<!-- page: mod\market\pages\market\view.php -->
<!-- view: mod\market\views\default\object\component.php -->

<?php
$full          = elgg_extract('full_view', $vars, FALSE);
$section       = elgg_extract('this_section', $vars, 'Summary');
$component     = elgg_extract('entity', $vars, FALSE);
$item_guid     = elgg_extract('asset', $vars, $component->asset);
$item          = get_entity($item_guid);
$view          = get_subtype_from_id($component->guid);
$owner         = $component->getOwnerEntity();
$tu            = $component->time_updated;
$container     = $component->getContainerEntity();
$jot           = elgg_get_excerpt($component->description);
$asset_link    = elgg_view('output/url', array(
				 'href' => "market/view/$item->guid",
				 'text' => $item->title,
)).'<br>';
$owner_link    = elgg_view('output/url', array(
				'href' => "jot/owned/$owner->username",
				'text' => $owner->name,
)).'<br>';
$tags          = elgg_view('output/tags', array('tags' => $component->tags)).'<br>';
$date          = elgg_view_friendly_time($component->time_created).'<br>';
$comments_text = elgg_echo("comments") . " ($comments_count)";
$comments_link = elgg_view('output/url', array(
			 	 'href' => $component->getURL() . '#jot-comments',
				 'text' => $comments_text,
));
$comments_count = $component->countComments();
if ($comments_count == 0) {$comments_link = '';}
if (!$component->tags)  {$tags='';}

$metadata = elgg_view_menu('entity', array(
	'entity'  => $component,    // for $entity  part of "$handler/edit/{$entity->getGUID()}"
	'handler' => 'market',      // for $handler part of "$handler/edit/{$entity->getGUID()}"
	'sort_by' => 'priority',
	'class'   => 'elgg-menu-hz',
));
$subtitle = "$asset_link $owner_link $tags $date $comments_link";
//	$subtitle = "{$category}{$custom}{$supplemental}{$pick}<br>{$author_text} {$date} {$comments_link}";

	$params = array(
		'header' => $header,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);

	$owner_icon = elgg_view_entity_icon($owner, 'small');

	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$component_info = elgg_view_image_block($owner_icon, $list_body);

//$text = elgg_view('output/longtext', array('value' => $component->description));
$extra = elgg_view("market/display/component",array('entity'=>$component, 'asset'=>$item, 'this_section'=>$section));
$body = "$text $extra";
//$body .= elgg_dump($vars);

echo <<<HTML
$component_info
<div class="jot elgg-content">
	$body
</div>
HTML;
