<!-- page: mod\jot\pages\jot\view.php -->
<!-- view: mod\jot\views\default\object\observation.php -->

<?php
$full          = elgg_extract('full_view', $vars, FALSE);
$section       = elgg_extract('this_section', $vars, 'Summary');
$observation   = elgg_extract('entity', $vars, FALSE);
$item_guid     = elgg_extract('asset', $vars, $observation->asset);
$item          = get_entity($item_guid);
$view          = get_subtype_from_id($observation->guid);
$owner         = $observation->getOwnerEntity();
$tu            = $observation->time_updated;
$container     = $observation->getContainerEntity();
$jot           = elgg_get_excerpt($observation->description);

if ($item->type == 'object' ){
	  if ($item->getSubtype() == 'place'){
	  	$asset_href = "places/view/$item_guid";
	  }
	  else {
	  	$href = "market/view/$item_guid";
	  }
}

$asset_link    = elgg_view('output/url', array(
				 'href' => $asset_href,
//				 'href' => "market/view/$item->guid",
				 'text' => $item->title,
)).'<br>';
$owner_link    = elgg_view('output/url', array(
				'href' => "jot/owned/$owner->username",
				'text' => $owner->name,
)).'<br>';
$tags          = elgg_view('output/tags', array('tags' => $observation->tags)).'<br>';
$date          = elgg_view_friendly_time($observation->time_created).'<br>';
$comments_text = elgg_echo("comments") . " ($comments_count)";
$comments_link = elgg_view('output/url', array(
			 	 'href' => $observation->getURL() . '#jot-comments',
				 'text' => $comments_text,
));
$comments_count = $observation->countComments();
if ($comments_count == 0) {$comments_link = '';}
if (!$observation->tags)  {$tags='';}

$metadata = elgg_view_menu('entity', array(
	'entity' => $observation,    // for $entity  part of "$handler/edit/{$entity->getGUID()}"
	'handler' => 'jot',          // for $handler part of "$handler/edit/{$entity->getGUID()}"
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
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

	$observation_info = elgg_view_image_block($owner_icon, $list_body);

//$text = elgg_view('output/longtext', array('value' => $observation->description));
$extra = elgg_view("jot/display/observation",array('entity'=>$observation, 'asset'=>$item, 'this_section'=>$section));
$body = "$text $extra";
//$body .= elgg_dump($vars);

echo <<<HTML
$observation_info
<div class="jot elgg-content">
	$body
</div>
HTML;
