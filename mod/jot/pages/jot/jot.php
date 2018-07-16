<?php
/**
 * Further proceesing for comments. Allows the user to convert a comment into an action post.
 */

// Get input data
$guid    = (int) get_input('guid');
$type    = get_input('type');
$subtype = get_input('subtype');
$jot     = elgg_get_sticky_values('jot'); 

$comment_text = $jot['jot_text'];
$referrer = $jot['referral_path'];
$parent_guid = get_entity($guid)->container_guid;
$parent_item = get_entity($parent_guid);
$parent_item_title = get_entity($guid)->title;
$aspect = get_entity($guid)->aspect;
$jotactive = elgg_get_entities(elgg_get_logged_in_user_guid(), 'jot');
$title = elgg_echo('Jot Routing');

if (elgg_get_plugin_setting('jot_adminonly', 'jot') == 'yes') {
	admin_gatekeeper();
}

if ($type == 'tag'){
		$action    = 'jot/tag';
		$body_vars = array(
				'guid' => $guid,
				'element_type' => $subtype,
				'referrer' => $referrer,
				'access_id' => ACCESS_DEFAULT,
		);
		
		$form_vars = array('name'    => 'jotForm',
				           'enctype' => 'multipart/form-data',);

		$module_options = array(
				'show_inner'  => false,
				'module_type' => 'popup'
		);
		$tag_form       = elgg_view_form($action, $form_vars, $body_vars);
		$body = elgg_view_layout('action', array(
										'content' => $tag_form,
										'title' => "Tag $subtype",
										'filter' => '',
								));
		
		echo elgg_view_module($module_options, '', $body);
}
else {
	elgg_push_breadcrumb(elgg_echo('jot:title'), "jot/show");
	if ($parent_item->type == 'user' ){
		elgg_push_breadcrumb($parent_item->name, "jot/owned/".$parent_item->username);
	}
	//elgg_push_breadcrumb(elgg_echo("jot:category:{$aspect}"), "jot/aspect/{$aspect}");
	if ($parent_item->type == 'object' ){
		elgg_push_breadcrumb($parent_item->title, "jot/view/".$parent_guid);
	}
	elgg_push_breadcrumb($parent_item_title, $referrer);
	
	
	$action    = 'jot/route';
	$body_vars = array(
	    'item_guid' => $guid,
	    'jot' => $comment_text,
	    'referrer' => $referrer,
	    'access_id' => ACCESS_DEFAULT,
	);
	
	$form_vars = array('name' => 'jotForm',
			           'enctype' => 'multipart/form-data');

	$content = elgg_view_form($action, $form_vars, $body_vars);
	
	
	// Show jot sidebar
	$sidebar = elgg_view("jot/sidebar");
	
	$params = array(
			'content' => $content,
			'title' => $title,
			'sidebar' => $sidebar,
			);
	
	$body = elgg_view_layout('action', $params);
	//$body = elgg_view_module('popup', $params);
	//$body = elgg_view_layout('popup', $params);
	//$body = elgg_view_layout('one_sidebar', $params);
	
	echo elgg_view_page($title, $body);	
}
