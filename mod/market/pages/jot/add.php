<?php
/**
 * Further proceesing for comments. Allows the user to convert a comment into an action post.
 */

// Get input data
$guid = (int) get_input('guid');
$jot = elgg_get_sticky_values('jot');

$description = $jot['description'];
$referrer = $jot['referrer'];
$jot_type = $jot['jot_type'];
	
$parent_guid = get_entity($guid)->container_guid;
$parent_item = get_entity($parent_guid);
$item = get_entity($guid);
$category = get_entity($guid)->marketcategory;
$mod = $jot_type.'s';

$marketactive = elgg_get_entities(elgg_get_logged_in_user_guid(), 'market');
$title = elgg_echo("jot:title:{$jot_type}");

elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
if ($parent_item->type == 'user' ){
	  elgg_push_breadcrumb($parent_item->name, "market/owned/".$parent_item->username);
}
elgg_push_breadcrumb(elgg_echo("market:category:{$category}"), "market/category/{$category}");
if ($item->type == 'object' ){
	  elgg_push_breadcrumb($item->title, $referrer);
}
elgg_push_breadcrumb(elgg_echo($jot_type));

if (elgg_get_plugin_setting('market_adminonly', 'market') == 'yes') {
	admin_gatekeeper();
}

if ($mod = 'issues'){
$body_vars = issues_prepare_form_vars(null, $guid, $referrer, $description); 
}

$form_vars = array('name' => 'jotForm', 'enctype' => 'multipart/form-data');

$content = elgg_view_form("{$mod}/add", $form_vars, $body_vars);

// Show market sidebar
$sidebar = elgg_view("market/sidebar");

$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
