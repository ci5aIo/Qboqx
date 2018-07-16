<?php
/**
 * View a single issue
 *
 */
// Get input data
 $issue_guid = (int) get_input('guid');
 $section    = get_input('section');
 $issue      = get_entity($issue_guid);
 $item       = get_entity($issue->asset);
 $item_guid  = $item->guid;
 $item_category = $item->marketcategory;
 elgg_set_context('view_issue');

if (!$issue) {
	register_error(elgg_echo("issue not found: {$issue_guid}"));
	REFERRER;
}
if (empty($section)) {
	 $section = 'summary';
}

$tabs = elgg_view('issues/menu', array('guid' =>$issue_guid, 'this_section' => $section)); // path: mod\jot\views\default\issues\menu.php

group_gatekeeper();

$options = array(
	'type' => 'object',
	'subtype' => 'issue',
	'full_view' => true,
);

$container = $issue->getOwnerEntity();
if (!$container) {
}

$title = $issue->title;

	elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
	elgg_push_breadcrumb(elgg_echo("market:category:{$item_category}"), "market/category/{$item_category}");

	issues_prepare_parent_breadcrumbs($issue);
if ($item->type == 'object' ){
	  elgg_push_breadcrumb($item->title, "market/view/".$item_guid);
}

elgg_push_breadcrumb($title);

// Display it
$content = elgg_view_list_item($issue, array('full_view' => true, 'this_section'=>$section));
$content .= elgg_view_comments($issue);

/* @TODO change*/
$sidebar = elgg_view("market/sidebar/navigation");     

$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		'filter' => $tabs,
		'header' => 'Issue',
		);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
