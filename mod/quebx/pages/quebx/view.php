<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Get the specified market post
$post = (int) get_input('marketpost');
$marketpost = get_entity($post);
$guid = $marketpost->guid;
$parent_guid = $marketpost->container_guid;
$parent_item = get_entity($parent_guid);
// If we can get out the market post ...
if ($marketpost = get_entity($post)) {

	$category = $marketpost->marketcategory;

	/*
	 * This is one way to display menu items specific to a page.
	 * May also include in start.php.  See mod/pages/start.php for an example.  
	 */
	
	$menu_item = new ElggMenuItem("0inventory", "Manage Inventory", "market/edit_more/{$post}/{$category}/family");
	elgg_register_menu_item('page', $menu_item);

	elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
	if ($parent_item->type == 'user' ){
		  elgg_push_breadcrumb($parent_item->name, "market/owned/".$parent_item->username);
	}
	elgg_push_breadcrumb(elgg_echo("market:category:{$category}"), "market/category/{$category}");
	if ($parent_item->type == 'object' ){
		  elgg_push_breadcrumb($parent_item->title, "market/view/".$parent_guid);
	}
	elgg_push_breadcrumb($marketpost->title);

	// Display it
//	$content = elgg_view_list_item($marketpost, array('full_view' => true));
	$content = elgg_view_entity($marketpost, array('full_view' => true));
	if (elgg_get_plugin_setting('market_comments', 'market') == 'yes') {
		$content .= elgg_view_comments($marketpost);
	}

	// Set the title appropriately
	$title = elgg_echo("market:title") . ":" . elgg_echo("market:{$category}"). ":".elgg_echo($marketpost->title.":type:".$marketpost->type.":".":subtype:".$marketpost->getSubtype());

} else {

	// Display the 'post not found' page instead
	$content = elgg_view_title(elgg_echo("market:notfound"));
	$title = elgg_echo("market:notfound");

}

// Show market sidebar
/* Reference works.  Replaced by page_menu above.
 * $sidebar = elgg_view("market/sidebar_item"); // sidebar for individual item
 */
$sidebar .= elgg_view("market/sidebar");     // sidebar for all items

$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		'filter' => '',
		'header' => '',
		);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

