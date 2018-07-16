<?php
/**
 * Elgg jot Plugin
 * @package jot
 */

// Get the specified jot post
$item_guid   = (int) get_input('item');
$section     = get_input('section');
$item        = get_entity($item_guid);
$parent_guid = $item->container_guid;
$parent_item = get_entity($parent_guid);
// If we can get the jot ...
if ($item = get_entity($item_guid)) {

	// Load fancybox
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');

	$category = $item->jotcategory;

	/*
	 * This is one way to display menu items specific to a page.
	 * May also include in start.php.  See mod/pages/start.php for an example.  
	 */
	
	$menu_item = new ElggMenuItem("0inventory", "Manage Inventory", "jot/edit_more/{$item_guid}/{$category}/family");
	elgg_register_menu_item('page', $menu_item);

	elgg_push_breadcrumb(elgg_echo('jot:title'), "jot/category");
	if ($parent_item->type == 'user' ){
		  elgg_push_breadcrumb($parent_item->name, "jot/owned/".$parent_item->username);
	}
	elgg_push_breadcrumb(elgg_echo("jot:category:{$category}"), "jot/category/{$category}");
	if ($parent_item->type == 'object' ){
		  elgg_push_breadcrumb($parent_item->title, "jot/view/".$parent_guid);
	}
	elgg_push_breadcrumb($item->title);

	// Display it
//	$content = elgg_view_list_item($item, array('full_view' => true));
	$content = elgg_view_entity($item, array('full_view' => true));
	if (elgg_get_plugin_setting('jot_comments', 'jot') == 'yes') {
		$content .= elgg_view_comments($item);
	}

	// Set the title appropriately
	$title = elgg_echo("jot:title") . ":" . elgg_echo("jot:{$category}"). ":".elgg_echo($item->title.":type:".$item->type.":".":subtype:".$item->getSubtype());

} else {

	// Display the 'post not found' page instead
	$content = elgg_view_title(elgg_echo("jot:notfound"));
	$title = elgg_echo("jot:notfound");

}

// Show jot sidebar
/* Reference works.  Replaced by page_menu above.
 * $sidebar = elgg_view("jot/sidebar_item"); // sidebar for individual item
 */
$sidebar .= elgg_view("jot/sidebar");     // sidebar for all items

$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		'filter' => '',
		'header' => '',
		);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

