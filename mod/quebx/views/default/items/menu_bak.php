<?php
/**
 * Item sections pages menu
 *
 * @uses $vars['section']
 */

$selected = $vars['this_section'];
$title     = $vars['title'];
$this_guid = $vars['guid'];

if (empty($selected)) {
	 $selected = 'Summary';
}

if (empty($title)) {
	 $task  = get_entity($this_guid);
	 $title = $task->title;
}

//set the url
$url = elgg_get_site_url() . "market/view";

$sections = array();
$sections[] = 'Summary';
$sections[] = 'Details';
$sections[] = 'Maintenance';
$sections[] = 'Inventory';
$sections[] = 'Management';
$sections[] = 'Accounting';
$sections[] = 'Gallery';
$sections[] = 'Reports';
$sections[] = 'Timeline';

$tabs = array();

foreach ($sections as $section) {
	$tabs[] = array(
		'title' => elgg_echo("$section"),
		'url' => "$url/$this_guid/$title/$section",
		'selected' => $section == $selected,
	);
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));



