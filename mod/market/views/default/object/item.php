<!-- page: mod\market\pages\market\view.php -->
<!-- view: mod\market\views\default\object\item.php -->

<?php
$full          = elgg_extract('full_view', $vars, FALSE);
$section       = elgg_extract('this_section', $vars, 'Summary');
$item          = elgg_extract('entity', $vars, FALSE);
$asset_guid    = elgg_extract('asset', $vars, $item->asset);
$asset         = get_entity($asset_guid);
$view          = get_subtype_from_id($item->guid);
$owner         = $item->getOwnerEntity();
$tu            = $item->time_updated;
$container     = $item->getContainerEntity();
$jot           = elgg_get_excerpt($item->description);
$asset_link    = elgg_view('output/url', array(
				 'href' => "market/view/$asset->guid",
				 'text' => $asset->title,
)).'<br>';
$owner_link    = elgg_view('output/url', array(
				'href' => "jot/owned/$owner->username",
				'text' => $owner->name,
)).'<br>';
$tags          = elgg_view('output/tags', array('tags' => $item->tags)).'<br>';
$date          = elgg_view_friendly_time($item->time_created).'<br>';
$comments_text = elgg_echo("comments") . " ($comments_count)";
$comments_link = elgg_view('output/url', array(
			 	 'href' => $item->getURL() . '#jot-comments',
				 'text' => $comments_text,
));
$comments_count = $item->countComments();
if ($comments_count == 0) {$comments_link = '';}
if (!$item->tags)  {$tags='';}

$metadata = elgg_view_menu('entity', array(
	'entity'  => $item,    // for $entity  part of "$handler/edit/{$entity->getGUID()}"
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

	$item_info = elgg_view_image_block($owner_icon, $list_body);

//$text = elgg_view('output/longtext', array('value' => $item->description));
$extra = elgg_view("market/display/item",array('entity'=>$item, 'asset'=>$asset, 'this_section'=>$section));
$body = "$text $extra";
//$body .= elgg_dump($vars);

echo <<<HTML
$item_info
<div class="jot elgg-content">
	$body
</div>
HTML;
