<!-- View: tasks\views\default\object\que.php-->
<?php
/**
 * Adopted from Elgg Market Plugin
 * Path: tasks\views\default\object\que.php
 */

$full       = elgg_extract('full_view', $vars, FALSE);
$section    = elgg_extract('this_section', $vars, 'Summary');
$que       = elgg_extract('entity', $vars, FALSE);
$asset_guid = $que->asset;
$asset     = get_entity($asset_guid);
$view_type  = $vars['view_type'];

if (!$que) {
	return TRUE;
}
$currency = elgg_get_plugin_setting('market_currency', 'market');

$owner = $que->getOwnerEntity();
$tu = $que->time_updated;
$container = $que->getContainerEntity();
$asset_link = elgg_view('output/url', array(
	'href' => "market/view/$asset_guid/$asset->title/Maintenance",
	'text' => $asset->title,
));
$this_asset = "<b>Asset:</b> " . $asset_link;
//$category = "<b>" . elgg_echo('market:category') . ":</b> " . elgg_echo("market:category:{$que->marketcategory}");

$excerpt = elgg_get_excerpt($que->description);

$owner_link = elgg_view('output/url', array(
	'href' => "market/owned/{$owner->username}",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$image = elgg_view('market/thumbnail', array('marketguid' => $que->guid, 'size' => 'medium', 'tu' => $tu));
$market_img = elgg_view('output/url', array(
	'href' => "market/view/$owner->username",
	'text' => $image,
));

$tags = elgg_view('output/tags', array('tags' => $que->tags));
$date = elgg_view_friendly_time($que->time_created);

if(isset($que->custom) && elgg_get_plugin_setting('market_custom', 'market') == 'yes'){
	$custom = "<br><b>" . elgg_echo('market:custom:text') . ": </b>" . elgg_echo($que->custom);
}

$comments_count = $que->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $que->getURL() . '#market-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

// Create the general view menu.  Some items, such as cars, have their own specific view menu.
$metadata = elgg_view_menu('entity', array(
	'entity' => $que,
	'handler' => 'que',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));
$extra    = elgg_view('tasks/display/que',array('entity'=>$que, 'this_section'=>$section));
$subtitle = "{$this_asset}{$custom}{$supplemental}{$pick}<br>{$author_text} {$date} {$comments_link}";
$params   = array(
	'entity'   => $que,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	"class"    => "",
);
$params   = array_merge($params, $vars);
$summary  = elgg_view('object/elements/summary', $params);

$text     = elgg_view('output/longtext', array('value' => $que->description));
$body     = $text.$extra;
$list_body = elgg_view('object/elements/full', array(
	'entity'  => $que,
	'summary' => $summary,
	'body'    => $body,
));


echo $list_body;
