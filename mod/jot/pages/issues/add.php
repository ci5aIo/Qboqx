<?php
/**
 * Create a single issue directly, bypassing the Jot Box
 * D*R*A*F*T*
 *
 * @package ElggPages
 */

$item_guid = (int) get_input('item_guid');
$item = get_entity($item_guid);
$referrer = REFERRER;
/*
if (!$item) {
	register_error(elgg_echo("item not found"));
	forward($referrer);
}

elgg_set_page_owner_guid($item->getContainerGUID());
*/
//group_gatekeeper();
/*
$container = elgg_get_page_owner_entity();
if (!$container) {
}
*/
$title = $item->title;

if ($item->type == 'user' ){
	  elgg_push_breadcrumb($item->name, "jot/owned/".$parent_item->username);
}
if ($item->type == 'object' ){
	  elgg_push_breadcrumb($item->title, $referrer);
}
elgg_push_breadcrumb($title);

$body_vars = issues_prepare_form_vars(null, $item_guid, $referrer); 

$form_vars = array('name' => 'issueForm', 'enctype' => 'multipart/form-data');

// Display it
$content = elgg_view_form("issues/add", $form_vars, $body_vars);
/*
if (elgg_get_logged_in_user_guid() == $issue->getOwnerGuid()) {
	$url = "issues/add/$issue->guid";
	elgg_register_menu_item('title', array(
			'name' => 'subissue',
			'href' => $url,
			'text' => elgg_echo('issues:newchild'),
			'link_class' => 'elgg-button elgg-button-action',
	));
}
*/
$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('jot/sidebar'),
));

echo elgg_view_page($title, $body);
