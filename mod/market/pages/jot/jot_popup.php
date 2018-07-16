<?php
/**
 * Further proceesing for comments. Allows the user to convert a comment into an action post.
 */

// Get input data
$guid = (int) get_input('guid');
$annotation = elgg_get_sticky_values('jot'); 
$comment_text = $annotation['jot_text'];
$referrer = $annotation['referral_path'];
	
$parent_guid = get_entity($guid)->container_guid;
$parent_item = get_entity($parent_guid);
$title = get_entity($guid)->title;
$category = get_entity($guid)->marketcategory;
$marketactive = elgg_get_entities(elgg_get_logged_in_user_guid(), 'market');
$title = elgg_echo('Jot Routing');


if (elgg_get_plugin_setting('market_adminonly', 'market') == 'yes') {
	admin_gatekeeper();
}
		
$body_vars = array(
    'item_guid' => $guid,
    'jot' => $comment_text,
    'referrer' => $referrer,
    'access_id' => ACCESS_PUBLIC,
);

$form_vars = array('name' => 'jotForm', 'enctype' => 'multipart/form-data');

$content = elgg_view_form("jot/routing_popup", $form_vars, $body_vars);

$params = array(
		'content' => $content,
		'title' => $title,
		);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
