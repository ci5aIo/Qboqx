<!-- page: mod\jot\pages\jot\view.php -->
<!-- view: mod\jot\views\default\object\jot.php -->

<?php
$full          = elgg_extract('full_view', $vars, FALSE);
$section       = elgg_extract('this_section', $vars, 'Summary');
$jot           = elgg_extract('entity', $vars, FALSE);
$item_guid     = elgg_extract('asset', $vars, $jot->asset) ?: $jot->assets[0]; 
$item          = get_entity($item_guid);
$view          = get_subtype_from_id($jot->guid);
$owner         = $jot->getOwnerEntity();
$tu            = $jot->time_updated;
$container     = $jot->getContainerEntity();
$jot_text      = elgg_get_excerpt($jot->description);

if ($item->type == 'object' ){
	  if ($item->getSubtype() == 'place'){
	  	$href = "places/view/$item_guid";
	  }
	  else {
	  	$href = "market/view/$item_guid";
	  }
}

$asset_link    = elgg_view('output/url', array(
				 'href' => $href,
				 'text' => $item->title,
)).'<br>';
$owner_link    = elgg_view('output/url', array(
				'href' => "jot/owned/$owner->username",
				'text' => $owner->name,
)).'<br>';
$tags          = elgg_view('output/tags', array('tags' => $jot->tags)).'<br>';
$date          = elgg_view_friendly_time($jot->time_created).'<br>';
$comments_text = elgg_echo("comments") . " ($comments_count)";
$comments_link = elgg_view('output/url', array(
			 	 'href' => $jot->getURL() . '#jot-comments',
				 'text' => $comments_text,
));
$comments_count = $jot->countComments();
if ($comments_count == 0) {$comments_link = '';}
if (!$jot->tags)  {$tags='';}

$metadata = elgg_view_menu('entity', array(
	'entity' => $jot,    // for $entity  part of "$handler/edit/{$entity->getGUID()}"
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

	$jot_info = elgg_view_image_block($owner_icon, $list_body);

//$text = elgg_view('output/longtext', array('value' => $jot->description));
$extra = elgg_view("jot/display/jot",array('entity'=>$jot, 'asset'=>$item, 'this_section'=>$section));
$body = "$text $extra";
//$body .= elgg_dump($vars);

echo <<<HTML
$jot_info
<div class="jot elgg-content">
	$body here
</div>
HTML;
