<?php
/**
 * Categories pages menu
 *
 * @uses $vars['type']
 */

$type = $vars['category'];

if (empty($type)) {
	 $type = 'all';
}

//set the url
$url = $vars['url'] . "quebx/inventory/";

$cats = elgg_get_plugin_setting('quebx_categories', 'quebx');
$categories = string_to_tag_array(elgg_get_plugin_setting('quebx_categories', 'quebx'));
array_unshift($categories, "all");
$tabs = array();
foreach ($categories as $category) {
	$tabs[] = array(
		'title' => elgg_echo("quebx:category:{$category}"),
		'url' => $url . $category,
		'selected' => $category == $type,
	);
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));



