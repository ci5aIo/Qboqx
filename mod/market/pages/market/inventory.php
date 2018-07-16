<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine
 * @copyright 2008-2013 Arck Interactive, LLC
 * @link http://arckinteractive.com
 * @version 1.8
 *
 * Adds information to a newly created item
 * Forked from edit_more.php
 */

elgg_load_js('lightbox');
elgg_load_css('lightbox');

$guid = $page[1];
//$hierarchy = array('category','family','parent','individual','element');
//$struct = '';
$h = '';
for($i=2;$i<=6;$i++) {
  if (isset($page[$i])) {
    //$struct .= $hierarchy[$i-2];
    $h .= "/".$page[$i];
  } else {
    break;
  }
}
$parent_guid = get_entity($guid)->container_guid;
$parent_item = get_entity($parent_guid);
$title = get_entity($guid)->title;
$category = get_entity($guid)->marketcategory;


elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
if ($parent_item->type == 'user' ){
	  elgg_push_breadcrumb($parent_item->name, "market/owned/".$parent_item->username);
}
elgg_push_breadcrumb(elgg_echo("market:category:{$category}"), "market/category/{$category}");
if ($parent_item->type == 'object' ){
	  elgg_push_breadcrumb($parent_item->title, "market/view/".$parent_guid);
}
/*
if ($parent_guid){
	  elgg_push_breadcrumb($parent_item->title, "market/view/".$parent_guid);
} else {
	  elgg_push_breadcrumb('no parent');
}*/
elgg_push_breadcrumb($title, "market/view/".$guid);

if (elgg_get_plugin_setting('market_adminonly', 'market') == 'yes') {
	admin_gatekeeper();
}

//count_user_objects() was deprecated in favor of elgg_get_entities()
//$marketactive = count_user_objects(elgg_get_logged_in_user_guid(), 'market');
$marketactive = elgg_get_entities(elgg_get_logged_in_user_guid(), 'market');

//$title = elgg_echo('market:add_more');

// use multipart/form-data in case any of the forms provide file fields
// and always use the market/edit_more action regardless of the form
$form_vars = array('name' => 'marketForm', 'enctype' => 'multipart/form-data', 'action'=>'action/market/inventory');
$body_vars = array('guid' => $guid, 'h'=>$h);
$content = elgg_view_form("market/edit".$h, $form_vars, $body_vars);

// Show market sidebar
$sidebar = elgg_view("market/sidebar");

$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
